<?php

namespace App\Http\Controllers\admin\laporan_tunggakan_siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTunggakanSiswaController extends Controller
{
    public function index() {
        return view('admin.laporan_tunggakan_siswa.index');
    }
}
