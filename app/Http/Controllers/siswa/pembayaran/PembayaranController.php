<?php

namespace App\Http\Controllers\Siswa\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index() {
        return view('siswa.pembayaran.index');
    }
}
