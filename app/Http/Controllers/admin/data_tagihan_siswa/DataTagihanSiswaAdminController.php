<?php

namespace App\Http\Controllers\Admin\Data_Tagihan_Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataTagihanSiswaAdminController extends Controller
{
    public function index() {
        return view('admin.data_tagihan_siswa.index');
    }
}
