<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProfileController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route untuk guest (belum login)
Route::middleware('guest')->group(function() {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Route logout untuk semua user yang sudah login
Route::middleware('auth')->group(function() {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Redirect root berdasarkan auth status
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isSiswa()) {
            return redirect()->route('siswa.dashboard');
        } elseif (Auth::user()->isKepalaSekolah()) {
            return redirect()->route('kepala_sekolah.dashboard');
        }
    }
    return redirect()->route('login');
});

// Route untuk Siswa
Route::middleware(['auth', 'siswa'])->prefix('siswa')->group(function() {
    Route::get('/', function () {
        return redirect()->route('siswa.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('siswa.profil.index');
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('siswa.tagihan.index');
    Route::get('/riwayat-pembayaran', [PembayaranController::class, 'index'])->name('siswa.pembayaran.index');

    // Routes pembayaran - perbaiki parameter
    Route::post('/tagihan/{tagihanId}/process', [TagihanController::class, 'process'])->name("checkout-process");
    Route::get('/checkout/{transaction}', [TagihanController::class, 'checkout'])->name("checkout");
});

// Webhook routes
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// Tambahkan routes untuk Midtrans redirect
Route::get('/midtrans/finish', [MidtransController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [MidtransController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [MidtransController::class, 'error'])->name('midtrans.error');

// Route untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function() {
    // Route untuk Dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Route untuk Data Siswa
    Route::get('/data_siswa', [DataSiswaAdminController::class, 'index'])->name('admin.data_siswa');
    Route::get('/data_siswa/create', [DataSiswaAdminController::class, 'create'])->name('admin.data_siswa.create');
    Route::get('/data_siswa/{id}/edit', [DataSiswaAdminController::class, 'edit'])->name('admin.data_siswa.edit');
    Route::put('/data_siswa/{id}', [DataSiswaAdminController::class, 'update'])->name('admin.data_siswa.update');
    Route::delete('/data_siswa/{id}', [DataSiswaAdminController::class, 'destroy'])->name('admin.data_siswa.destroy');
    Route::post('/data_siswa', [DataSiswaAdminController::class, 'store'])->name('admin.data_siswa.store');

    // Route untuk Data Tagihan Siswa
    Route::get('/data_tagihan_siswa', [DataTagihanSiswaAdminController::class, 'index'])->name('admin.data_tagihan_siswa');
    Route::get('/data_tagihan_siswa/create', [DataTagihanSiswaAdminController::class, 'create'])->name('admin.data_tagihan_siswa.create');
    Route::post('/data_tagihan_siswa/store', [DataTagihanSiswaAdminController::class, 'store'])->name('admin.data_tagihan_siswa.store');
    Route::get('/data_tagihan_siswa/autocomplete', [DataTagihanSiswaAdminController::class, 'autocomplete'])->name('admin.data_tagihan_siswa.autocomplete');
    Route::post('/data_tagihan_siswa', [DataTagihanSiswaAdminController::class, 'searchById'])->name('admin.data_tagihan_siswa.search.byId');
    Route::post('/data_tagihan_siswa/search', [DataTagihanSiswaAdminController::class, 'search'])->name('admin.data_tagihan_siswa.search');

    // Route untuk Laporan Tunggakan Siswa
    Route::get('/laporan_tunggakan_siswa', [LaporanTunggakanSiswaController::class, 'index'])->name('admin.laporan_tunggakan_siswa');
    
    // Route untuk Laporan Penerimaan
    Route::get('/laporan_penerimaan', [LaporanPenerimaanController::class, 'index'])->name('admin.laporan_penerimaan');

    // Route untuk Data Kelas
    Route::get('/data_kelas', [DataKelasController::class, 'index'])->name('admin.data_kelas');
    Route::get('/data_kelas/create', [DataKelasController::class, 'create'])->name('admin.data_kelas.create');
    Route::get('/data_kelas/{id}/edit', [DataKelasController::class, 'edit'])->name('admin.data_kelas.edit');
    Route::put('/data_kelas/{id}', [DataKelasController::class, 'update'])->name('admin.data_kelas.update');
    Route::delete('/data_kelas/{id}', [DataKelasController::class, 'destroy'])->name('admin.data_kelas.destroy');
    Route::post('/data_kelas', [DataKelasController::class, 'store'])->name('admin.data_kelas.store');
    
    // Route untuk Data Angkatan
    Route::get('/data_angkatan', [DataAngkatanController::class, 'index'])->name('admin.data_angkatan');
    Route::get('/data_angkatan/create', [DataAngkatanController::class, 'create'])->name('admin.data_angkatan.create');
    Route::get('/data_angkatan/{id}/edit', [DataAngkatanController::class, 'edit'])->name('admin.data_angkatan.edit');
    Route::put('/data_angkatan/{id}', [DataAngkatanController::class, 'update'])->name('admin.data_angkatan.update');
    Route::delete('/data_angkatan/{id}', [DataAngkatanController::class, 'destroy'])->name('admin.data_angkatan.destroy');
    Route::post('/data_angkatan', [DataAngkatanController::class, 'store'])->name('admin.data_angkatan.store');

    // Route untuk Data Tahun Ajaran
    Route::get('/data_tahun_ajaran', [DataTahunAjaranController::class, 'index'])->name('admin.data_tahun_ajaran');
    Route::get('/data_tahun_ajaran/create', [DataTahunAjaranController::class, 'create'])->name('admin.data_tahun_ajaran.create');
    Route::get('/data_tahun_ajaran/{id}/edit', [DataTahunAjaranController::class, 'edit'])->name('admin.data_tahun_ajaran.edit');
    Route::put('/data_tahun_ajaran/{id}', [DataTahunAjaranController::class, 'update'])->name('admin.data_tahun_ajaran.update');
    Route::delete('/data_tahun_ajaran/{id}', [DataTahunAjaranController::class, 'destroy'])->name('admin.data_tahun_ajaran.destroy');
    Route::post('/data_tahun_ajaran', [DataTahunAjaranController::class, 'store'])->name('admin.data_tahun_ajaran.store');
});

require __DIR__.'/auth.php';
