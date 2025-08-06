<?php

namespace App\Http\Controllers\admin\data_kelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataKelasController extends Controller
{
    public function index() {
        return view('admin.data_kelas.index');
    }
}
