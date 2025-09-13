<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function index() {
        $user = Auth::user();
        // Jumlah siswa (aktif)
        $students = Student::where('status', 'aktif')->count();

        // Tahun ajaran terbaru (urut dari angka depan "2024/2025")
        $latest = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')->first();

        if (!$latest) {
            // fallback jika belum ada data tahun ajaran
            $latestAcademicYear = '-';
            $penerimaanTotal = 0;
            $tunggakanTotal  = 0;
        } else {
            $latestAcademicYear = $latest->academic_year;
            $ayId = $latest->id;

            // Total penerimaan = jumlah semua tagihan status "lunas" di TA terbaru
            $penerimaanTotal = (int) SchoolFee::where('academic_year_id', $ayId)
                ->whereRaw('LOWER(COALESCE(status,"")) = "lunas"')
                ->sum('jumlah');

            // Total tunggakan
            // Opsi A (tanpa partial): jumlah semua tagihan yg bukan "lunas"
            $tunggakanTotal = (int) SchoolFee::where('academic_year_id', $ayId)
                ->where(function ($q) {
                    $q->whereNull('status')
                      ->orWhereRaw('LOWER(status) <> "lunas"');
                })
                ->sum('jumlah');

            // Opsi B (kalau kamu pakai kolom "sisa" dan selalu update saat pembayaran):
            // $tunggakanTotal = (int) SchoolFee::where('academic_year_id', $ayId)->sum('sisa');
        }

        return view('admin.dashboard', compact(
            'user', 'students', 'latestAcademicYear', 'penerimaanTotal', 'tunggakanTotal'
        ));
    }
}
