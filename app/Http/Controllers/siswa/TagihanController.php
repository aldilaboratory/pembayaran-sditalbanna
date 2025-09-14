<?php

namespace App\Http\Controllers\Siswa;

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
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $tagihanSiswa = SchoolFee::where('student_id', $student->id)
            ->orderBy('jatuh_tempo','desc')
            ->get();
        
        // Ambil pending yang MASIH valid (created_at >= now()-TTL)
        $ttlMinutes = 60; // samakan dengan expiry.duration
        $pendings = Transaction::select('id','school_fee_id','expired_at','created_at')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->where(function($q) use ($ttlMinutes){
                $q->where('expired_at', '>', now())
                ->orWhere(function($qq) use ($ttlMinutes){
                    $qq->whereNull('expired_at')
                        ->where('created_at', '>', now()->subMinutes($ttlMinutes));
                });
            })
            ->get()->keyBy('school_fee_id');

        return view('siswa.tagihan.index', compact('tagihanSiswa','student','pendings','ttlMinutes'));
    }

    public function process(Request $request, $schoolFeeId)
    {
        $user     = Auth::user();
        $student  = Student::where('user_id', $user->id)->firstOrFail();
        $schoolFee= SchoolFee::findOrFail($schoolFeeId);

        abort_if($schoolFee->student_id !== $student->id, 403, 'Tagihan tidak valid');
        if ($schoolFee->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas');
        }

        $ttl = (int) config('midtrans.pending_ttl', 60); // menit

        // 1) Tandai semua pending yang sudah kadaluarsa
        Transaction::where('student_id', $student->id)
            ->where('school_fee_id', $schoolFee->id)
            ->where('status', 'pending')
            ->where(function ($q) use ($ttl) {
                $q->where('expired_at', '<=', now())
                ->orWhere(function ($qq) use ($ttl) {
                    // fallback utk record lama yang belum punya expired_at
                    $qq->whereNull('expired_at')
                        ->where('created_at', '<=', now()->subMinutes($ttl));
                });
            })
            ->update(['status' => 'expired', 'expired_at' => now()]);

        // 2) Cek lagi apakah masih ada pending yang MASIH VALID
        $activeTransaction = Transaction::where('school_fee_id', $schoolFee->id)
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->where(function ($q) use ($ttl) {
                $q->where('expired_at', '>', now())
                ->orWhere(function ($qq) use ($ttl) {
                    $qq->whereNull('expired_at')
                        ->where('created_at', '>', now()->subMinutes($ttl));
                });
            })
            ->latest('id')
            ->first();

        if ($activeTransaction) {
            return redirect()->route('checkout', $activeTransaction->id);
        }

        $ttl = config('midtrans.pending_ttl', 60);

        // 3) Buat transaksi baru (pastikan expired_at diisi)
        $transaction = Transaction::create([
            'student_id'   => $student->id,
            'school_fee_id'=> $schoolFee->id,
            'jumlah'       => $schoolFee->jumlah,
            'status'       => 'pending',
            'expired_at'   => now()->addMinutes($ttl),
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
            'override_notification_url' => route('midtrans.notification'),
            'customer_details' => [
                'name' => $student->nama,
                'email' => $student->email,
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
                'duration' => $ttl,  
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
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        abort_if($transaction->student_id !== $student->id, 403);

        // anggap kadaluarsa jika sudah melewati expired_at
        if ($transaction->status === 'pending' && $transaction->expired_at?->lt(now())) {
            $transaction->update(['status' => 'expired']);
            return redirect()
                ->route('siswa.tagihan.index')
                ->with('warning', 'Transaksi kadaluarsa. Silakan klik Bayar lagi.');
        }

        if (in_array($transaction->status, ['expired','failed','canceled'])) {
            return redirect()
                ->route('siswa.tagihan.index')
                ->with('warning', 'Transaksi tidak aktif. Silakan klik Bayar lagi.');
        }

        // (opsional) tanya status realtime ke Midtrans
        // try {
        //   $st = \Midtrans\Transaction::status($transaction->invoice_code);
        //   if (($st->transaction_status ?? null) === 'expire') {
        //       $transaction->update(['status' => 'expired', 'expired_at' => now()]);
        //       return redirect()->route('checkout-process', $transaction->school_fee_id);
        //   }
        // } catch (\Throwable $e) {}

        if ($transaction->status === 'success') {
            return redirect()->route('siswa.tagihan.index')->with('success','Tagihan sudah lunas');
        }

        $schoolFee = $transaction->schoolFee;
        return view('siswa.tagihan.checkout', compact('transaction','schoolFee','student'));
    }
}
