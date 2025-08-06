<?php


use Illuminate\Support\Facades\Route;
// Route untuk Siswa
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\Profil\ProfilController;
use App\Http\Controllers\Siswa\Tagihan\TagihanController;
use App\Http\Controllers\Siswa\Pembayaran\PembayaranController;

// Route untuk Admin
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\Data_Siswa\DataSiswaAdminController;
use App\Http\Controllers\Admin\Laporan_Tunggakan_Siswa\LaporanTunggakanSiswaController;
use App\Http\Controllers\Admin\Laporan_Penerimaan\LaporanPenerimaanController;
use App\Http\Controllers\Admin\Data_Tagihan_Siswa\DataTagihanSiswaAdminController;
use App\Http\Controllers\Admin\Data_Kelas\DataKelasController;
use App\Http\Controllers\Admin\Data_Angkatan\DataAngkatanController;
use App\Http\Controllers\Admin\Data_Tahun_Ajaran\DataTahunAjaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
        return redirect()->route('siswa.dashboard');
    });

// Route untuk Siswa
Route::prefix('siswa')->group(function() {
    Route::get('/', function () {
        return redirect()->route('siswa.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('siswa.profil.index');
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('siswa.tagihan.index');
    Route::get('/riwayat-pembayaran', [PembayaranController::class, 'index'])->name('siswa.pembayaran.index');
});

// Route untuk Admin
Route::prefix('admin')->group(function() {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/data_siswa', [DataSiswaAdminController::class, 'index'])->name('admin.data_siswa');
    Route::get('/data_tagihan_siswa', [DataTagihanSiswaAdminController::class, 'index'])->name('admin.data_tagihan_siswa');
    Route::get('/laporan_tunggakan_siswa', [LaporanTunggakanSiswaController::class, 'index'])->name('admin.laporan_tunggakan_siswa');
    Route::get('/laporan_penerimaan', [LaporanPenerimaanController::class, 'index'])->name('admin.laporan_penerimaan');
    Route::get('/data_kelas', [DataKelasController::class, 'index'])->name('admin.data_kelas');
    Route::get('/data_angkatan', [DataAngkatanController::class, 'index'])->name('admin.data_angkatan');
    Route::get('/data_tahun_ajaran', [DataTahunAjaranController::class, 'index'])->name('admin.data_tahun_ajaran');
});
