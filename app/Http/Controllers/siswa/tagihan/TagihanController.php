<?php

namespace App\Http\Controllers\Siswa\Tagihan;

use App\Http\Controllers\Controller;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TagihanController extends Controller
{
    public function index(){
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan');
        }
        
        $tagihanSiswa = SchoolFee::where('student_id', $student->id)
                                ->orderBy('jatuh_tempo', 'desc')
                                ->get();

        return view('siswa.tagihan.index', compact('tagihanSiswa', 'student'));
    }

    public function process(Request $request, $schoolFeeId)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $schoolFee = SchoolFee::findOrFail($schoolFeeId);

        // Validasi apakah tagihan milik siswa ini
        if ($schoolFee->student_id !== $student->id) {
            return redirect()->back()->with('error', 'Tagihan tidak valid');
        }

        // Cek apakah sudah lunas
        if ($schoolFee->status === 'lunas') {
            return redirect()->back()->with('error', 'Tagihan sudah lunas');
        }

        // Cek apakah ada transaksi pending
        $activeTransaction = Transaction::where('school_fee_id', $schoolFee->id)
                                        ->where('student_id', $student->id)
                                        ->where('status', 'pending')
                                        ->first();

        if ($activeTransaction) {
            return redirect()->route('checkout', $activeTransaction->id);
        }

        // Jika tidak ada transaksi pending, atau yang ada sudah expired/failed
        // Buat transaksi baru
        $transaction = Transaction::create([
            'student_id' => $student->id,
            'school_fee_id' => $schoolFee->id,
            'jumlah' => $schoolFee->jumlah,
            'status' => 'pending',
        ]);

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$clientKey = config('midtrans.clientKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = $transaction->invoice_code; // -> "INV-202409-000123"

        // 3. Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $transaction->jumlah,
            ],
            'customer_details' => [
                'name' => $student->nama,
                // 'email' => $student->email,
                'phone' => $student->nomor_whatsapp_orang_tua_wali ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'inv-' . $schoolFee->id,
                    'price' => (int)$schoolFee->jumlah,
                    'quantity' => 1,
                    'name' => $schoolFee->jenis_tagihan_label . ' - ' . $schoolFee->nama_bulan,
                ]
            ],
            'expiry' => array(
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute', 
                'duration' => 60
            ),
            'callbacks' => [
                'finish' => route('midtrans.finish'),
                'unfinish' => route('midtrans.unfinish'),
                'error' => route('midtrans.error'),
            ],
        ];
        
        try {
            // Debug parameter sebelum kirim ke Midtrans
            Log::info('Midtrans params:', $params);
            
            // Dapatkan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            Log::info('Generated snap token:', ['token' => $snapToken]);

            $transaction->snap_token = $snapToken;
            $transaction->save();

            // Redirect ke halaman checkout
            return redirect()->route('checkout', $transaction->id);
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        }
    }

    public function checkout(Transaction $transaction)
    {
        // Validasi transaksi milik siswa ini
        $student = Student::where('user_id', Auth::id())->first();

        if ($transaction->student_id !== $student->id) {
            abort(403, 'Transaksi tidak valid');
        }

        // Jika transaksi expired/failed/canceled, redirect ke process baru
        if (in_array($transaction->status, ['expired', 'failed', 'canceled'])) {
            return redirect()->route('checkout-process', $transaction->school_fee_id);
        }

        // Jika transaksi success, redirect ke tagihan
        if ($transaction->status === 'success') {
            return redirect()->route('siswa.tagihan.index')
                            ->with('success', 'Tagihan sudah lunas');
        }

        $schoolFee = $transaction->schoolFee;

        return view('siswa.tagihan.checkout', compact('transaction', 'schoolFee', 'student'));
    }
}
