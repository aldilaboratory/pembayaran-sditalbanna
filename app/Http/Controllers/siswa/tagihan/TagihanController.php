<?php

namespace App\Http\Controllers\Siswa\Tagihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(){
        return view('siswa.tagihan.index');
    }
}
