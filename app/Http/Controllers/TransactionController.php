<?php

namespace App\Http\Controllers;

use App\Mail\InvoicePaidMail;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function testSendInvoice(Transaction $transaction)
    {
        // Ambil email siswa: pakai kolom students.email atau fallback ke user->email
        $student = $transaction->student;
        $to = $student->email ?? $student->user->email ?? null;
        if (!$to) {
            \Log::warning('Email siswa kosong pada transaksi '.$transaction->id);
            abort(422, 'Email siswa tidak tersedia');
        }

        // Siapkan logo base64 (cara paling aman untuk DomPDF)
        $logoPath = public_path('images/albanna.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext  = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($logoPath));
        }

        // Generate PDF (pakai view pdf.invoice milikmu)
        $pdf = Pdf::setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => true,
                    'chroot'               => public_path(),
                ])
                ->loadView('pdf.invoice', [
                    'transaction' => $transaction,
                    'student'     => $transaction->student,
                    'fee'         => $transaction->schoolFee,
                    'logoBase64'  => $logoBase64, // pastikan blade pakai ini jika tersedia
                ])
                ->setPaper('a4','portrait');

        // Kirim via Mailtrap
        try {
            Mail::to($to)->send(new InvoicePaidMail($transaction, $pdf->output()));
            return 'OK: invoice terkirim ke '.$to;
        } catch (\Throwable $e) {
            \Log::error('Mailtrap send error: '.$e->getMessage());
            abort(500, 'Gagal mengirim email: '.$e->getMessage());
        }
    }

    public function pdfAdmin(Transaction $transaction)
    {
        // route ini sudah dilindungi middleware admin → tidak perlu cek pemilik siswa
        $transaction->load(['student','schoolFee.academicYear','schoolFee.student.studentClass']);

        // Siapkan logo base64 (aman untuk DomPDF)
        $logoPath = public_path('images/albanna.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $ext  = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($logoPath));
        }

        $pdf = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'chroot'               => public_path(),
            ])->loadView('pdf.invoice', [
                'transaction' => $transaction,
                'student'     => $transaction->student,
                'fee'         => $transaction->schoolFee,
                'logoBase64'  => $logoBase64,
            ])->setPaper('a4','portrait');

        return $pdf->download('Invoice-'.$transaction->invoice_code.'.pdf');
    }
}
