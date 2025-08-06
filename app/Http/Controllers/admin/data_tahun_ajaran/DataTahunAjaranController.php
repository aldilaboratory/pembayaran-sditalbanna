<?php

namespace App\Http\Controllers\admin\data_tahun_ajaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataTahunAjaranController extends Controller
{
    public function index() {
        return view('admin.data_tahun_ajaran.index');
    }
}
