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
        Log::info('Request Method: ' . $request->method());
        Log::info('Request Body: ', $request->all());
        
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);

        try {
            $notification = new \Midtrans\Notification();
            
            Log::info('Notification Object: ', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'payment_type' => $notification->payment_type,
                'fraud_status' => $notification->fraud_status ?? 'N/A'
            ]);

            // Parse order_id untuk mendapatkan transaction ID
            // Format: SCHOOL-FEE-{transaction_id}-{timestamp}
            $orderParts = explode('-', $notification->order_id);
            if (count($orderParts) < 3 || $orderParts[0] !== 'SCHOOL' || $orderParts[1] !== 'FEE') {
                Log::error('Invalid order ID format: ' . $notification->order_id);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID format']);
            }

            $transactionId = $orderParts[2];
            $transaction = Transaction::find($transactionId);

            if (!$transaction) {
                Log::error('Transaction not found: ' . $transactionId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found']);
            }

            Log::info('Processing transaction: ' . $transaction->id . ' with status: ' . $notification->transaction_status);

            // Update status berdasarkan notification dari Midtrans
            switch ($notification->transaction_status) {
                case 'capture':
                    if ($notification->payment_type == 'credit_card') {
                        if ($notification->fraud_status == 'challenge') {
                            $transaction->status = 'challenge';
                        } else {
                            $this->setTransactionSuccess($transaction, $notification);
                        }
                    }
                    break;

                case 'settlement':
                    $this->setTransactionSuccess($transaction, $notification);
                    break;

                case 'pending':
                    $transaction->status = 'pending';
                    break;

                case 'deny':
                    $transaction->status = 'failed';
                    break;

                case 'expire':
                    $transaction->status = 'expired';
                    break;

                case 'cancel':
                    $transaction->status = 'canceled';
                    break;

                case 'failure':
                    $transaction->status = 'failed';
                    break;

                default:
                    $transaction->status = $notification->transaction_status;
                    break;
            }

            $transaction->payment_type = $notification->payment_type;
            $transaction->save();

            Log::info('Transaction updated successfully: ' . $transaction->id . ' to status: ' . $transaction->status);

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
        
        // Update status tagihan menjadi lunas
        $schoolFee = $transaction->schoolFee;
        $schoolFee->status = 'lunas';
        $schoolFee->tanggal_lunas = Carbon::now();
        $schoolFee->save();

        Log::info('SchoolFee marked as paid: ' . $schoolFee->id);
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
