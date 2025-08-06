<?php

namespace App\Http\Controllers\Siswa\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index() {
        return view('siswa.profil.index');
    }
}
