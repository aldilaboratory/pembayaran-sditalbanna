<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        // Load/sambungkan user dengan relasi student dan relasi lainnya
        $user = User::with([
            'student',
            'student.studentClass',
            'student.schoolYear',
            'student.academicYear',
            'student.schoolFee'
        ])->find(Auth::id());

        // Cek apakah data student ada
        if (!$user->student) {
            // Jika data student belum ada, bisa redirect atau tampilkan pesan
            return view('siswa.dashboard', compact('user'))
                ->with('warning', 'Data siswa belum lengkap. Silakan hubungi admin.');
        }

        // Hitung total tagihan yang belum lunas
        $totalTagihan = $user->student->schoolFee->where('status', '!=', 'lunas')->sum('jumlah');

        return view('siswa.dashboard', compact('user', 'totalTagihan'));
    }
}
