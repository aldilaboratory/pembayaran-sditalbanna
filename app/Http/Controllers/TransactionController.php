<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show(Transaction $transaction)
    {
        // Pastikan transaksi milik siswa yang login
        abort_unless($transaction->student_id === auth()->user()->student->id, 403);

        $transaction->load(['student','schoolFee.academicYear','schoolFee.student.studentClass']);

        return view('siswa.transaksi.show', compact('transaction'));
    }

    public function pdf(Transaction $transaction)
    {
        // Ambil student_id dari guard 'siswa' dulu, fallback ke default 'web'
        $studentId = optional(auth()->user()?->student)->id;

        abort_if(!$studentId || $transaction->student_id !== $studentId, 403);

        $transaction->load(['student','schoolFee.academicYear','schoolFee.student.studentClass']);

        $logoPath = public_path('images/albanna.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext  = pathinfo($logoPath, PATHINFO_EXTENSION); // png/jpg/svg
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/'.$ext.';base64,'.base64_encode($data);
        }

        // Pastikan DomPDF aman untuk path lokal/asset
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => true, // kalau ada asset()/http img
                    'chroot'               => public_path(), // optional, amankan root akses file
                ])->loadView('pdf.invoice', [
                    'transaction' => $transaction,
                    'student'     => $transaction->student,
                    'fee'         => $transaction->schoolFee,
                    'logoBase64'  => $logoBase64,
                ])->setPaper('a4','portrait');

        return $pdf->download('Invoice-'.$transaction->invoice_code.'.pdf');
    }
}
