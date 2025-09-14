<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index() {
        $studentId = auth()->user()->student->id;

        $transactions = \App\Models\Transaction::with(['schoolFee', 'student'])
        ->where('student_id', $studentId)
        ->latest('paid_at')   // paling baru di atas; fallback kalau null akan pakai created_at
        ->latest('created_at')
        ->get();
        
        return view('siswa.pembayaran.index', compact('transactions'));
    }
}
