<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notification(Request $request) 
    {
        Log::info('=== MIDTRANS NOTIFICATION RECEIVED ===');
        Log::info('Request Body: ', $request->all());
        
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);

        try {
            $notification = new \Midtrans\Notification();
            
            Log::info('Notification details:', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'payment_type' => $notification->payment_type,
                'fraud_status' => $notification->fraud_status ?? 'N/A'
            ]);

            $orderId = $notification->order_id;
            $transaction = null;

            // Format BARU: INV-YYYYMM-000123  -> ambil 6 digit terakhir sebagai ID
            if (preg_match('/^INV-\d{6}-(\d{6})$/', $orderId, $m)) {
                $transaction = Transaction::find((int) ltrim($m[1], '0'));
            }

            // Fallback format LAMA: INV-{id}-{timestamp}
            if (!$transaction && preg_match('/^INV-(\d+)-\d+$/', $orderId, $m)) {
                $transaction = Transaction::find((int) $m[1]);
            }

            if (!$transaction) {
                \Log::error('Transaction not found for order_id: '.$orderId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found']);
            }

            Log::info('Processing transaction: ' . $transaction->id . ' with status: ' . $notification->transaction_status);

            // Update status berdasarkan notification
            switch ($notification->transaction_status) {
                case 'settlement':
                case 'capture':
                    $this->setTransactionSuccess($transaction, $notification);
                    break;
                case 'pending':
                    $transaction->status = 'pending';
                    break;
                case 'expire':
                    $this->setTransactionExpired($transaction);
                    break;
                case 'cancel':
                    $this->setTransactionCanceled($transaction);
                    break;
                case 'failure':
                    $transaction->status = 'failed';
                    break;
                default:
                    $transaction->status = $notification->transaction_status;
                    $transaction->save();
                    break;
            }

            $transaction->payment_type = $notification->payment_type;
            $transaction->save();

            Log::info('Transaction updated: ' . $transaction->id . ' to status: ' . $transaction->status);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function setTransactionSuccess($transaction, $notification)
    {
        $transaction->status = 'success';
        $transaction->paid_at = Carbon::now();
        $transaction->save();
        
        // Update SchoolFee menjadi lunas
        $schoolFee = $transaction->schoolFee;
        if ($schoolFee) {
            $schoolFee->status = 'lunas';
            $schoolFee->tanggal_lunas = Carbon::now();
            $schoolFee->save();

            Log::info('SchoolFee updated to LUNAS: ' . $schoolFee->id);
        }

        // === KIRIM WHATSAPP via Fonnte ===
        $student = $transaction->student;
        $target  = $student?->whatsapp_target; // accessor normalisasi

        if ($target) {
        // Susun pesan (sesuaikan field di database)
        $namaSiswa = $student->nama ?? 'Siswa';
        $tagihan   = $transaction->schoolFee?->jenis_tagihan ?? 'SPP';
        $periode   = $transaction->schoolFee?->nama_bulan ?? null;
        $nilai     = number_format($transaction->jumlah ?? 0, 0, ',', '.');

        $barisPeriode = $periode ? "\nPeriode: {$periode}" : '';
        $pesan = "Halo Orang Tua/Wali {$namaSiswa},\n"
               . "Pembayaran *{$tagihan}* sebesar *Rp {$nilai}* telah *DITERIMA* ✅.\n"
               . "Invoice: {$transaction->invoice_code}\n"
               . "Tanggal: ".now('Asia/Jakarta')->format('d M Y H:i').$barisPeriode."\n"
               . "Terima kasih atas kerjasamanya.";

        // Kirim (disarankan via Queue di produksi)
        app(\App\Services\FonnteService::class)->send($target, $pesan, ['typing' => true]);
    }
    }

    private function setTransactionExpired(Transaction $transaction): void
    {
        $transaction->status = 'expired';
        $transaction->expired_at = Carbon::now();
        $transaction->save();

        // Pastikan tagihan balik ke belum_lunas
        if ($transaction->schoolFee) {
            $transaction->schoolFee->status = 'belum_lunas'; // atau 'expired'
            $transaction->schoolFee->save();
            Log::info('SchoolFee reverted to BELUM_LUNAS (expired): '.$transaction->schoolFee->id);
        }
    }

    private function setTransactionCanceled(Transaction $transaction): void
    {
        $transaction->status = 'canceled';
        $transaction->canceled_at = Carbon::now();
        $transaction->save();

        if ($transaction->schoolFee) {
            $transaction->schoolFee->status = 'belum_lunas';
            $transaction->schoolFee->save();
            Log::info('SchoolFee reverted to BELUM_LUNAS (canceled): '.$transaction->schoolFee->id);
        }
    }

    public function finish(Request $request)
    {
        return redirect()->route('siswa.tagihan.index')
                        ->with('success', 'Pembayaran selesai! Tagihan Anda akan segera diperbarui.');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('siswa.tagihan.index')
                        ->with('warning', 'Pembayaran belum selesai. Silakan coba lagi jika diperlukan.');
    }

    public function error(Request $request)
    {
        return redirect()->route('siswa.tagihan.index')
                        ->with('error', 'Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
    }
}
