<?php

namespace App\Http\Controllers\Admin\Data_Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataSiswaAdminController extends Controller
{
    public function index() {
        return view('admin.data_siswa.index');
    }
}
