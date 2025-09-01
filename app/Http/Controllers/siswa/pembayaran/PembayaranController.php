<?php

namespace App\Http\Controllers\Siswa\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index() {
        $student = Auth::user()->student->id;
        $transactions = Transaction::where('student_id', $student)->get();
        
        return view('siswa.pembayaran.index', compact('transactions'));
    }
}
