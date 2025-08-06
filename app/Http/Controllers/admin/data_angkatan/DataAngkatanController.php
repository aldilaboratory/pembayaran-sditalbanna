<?php

namespace App\Http\Controllers\admin\data_angkatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataAngkatanController extends Controller
{
    public function index() {
        return view('admin.data_angkatan.index');
    }
}
