<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardKepalaSekolahController extends Controller
{
    public function index() {
        $user = Auth::user();
        
        return view('kepala_sekolah.dashboard', compact('user'));
    }
}
