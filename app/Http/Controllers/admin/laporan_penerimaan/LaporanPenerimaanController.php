<?php

namespace App\Http\Controllers\admin\laporan_penerimaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanPenerimaanController extends Controller
{
    public function index() {
        return view('admin.laporan_penerimaan.index');
    }
}
