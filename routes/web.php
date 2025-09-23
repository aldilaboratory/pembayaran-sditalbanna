<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
// Route untuk Siswa
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\ProfilController;
use App\Http\Controllers\Siswa\TagihanController;
use App\Http\Controllers\Siswa\PembayaranController;

// Route untuk Super Admin
use App\Http\Controllers\Super_Admin\DashboardSuperAdminController;
use App\Http\Controllers\Super_Admin\SA_DataSiswaAdminController;
use App\Http\Controllers\Super_Admin\SA_LaporanTunggakanSiswaController;
use App\Http\Controllers\Super_Admin\SA_LaporanPenerimaanController;
use App\Http\Controllers\Super_Admin\SA_DataTagihanSiswaAdminController;
use App\Http\Controllers\Super_Admin\SA_DataKelasController;
use App\Http\Controllers\Super_Admin\SA_DataAngkatanController;
use App\Http\Controllers\Super_Admin\SA_DataTahunAjaranController;
use App\Http\Controllers\Super_Admin\SA_DataAdminController;
use App\Http\Controllers\Super_Admin\SA_ProfilSiswaController;

// Route untuk Admin
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DataSiswaAdminController;
use App\Http\Controllers\Admin\LaporanTunggakanSiswaController;
use App\Http\Controllers\Admin\LaporanPenerimaanController;
use App\Http\Controllers\Admin\DataTagihanSiswaAdminController;
use App\Http\Controllers\Admin\DataKelasController;
use App\Http\Controllers\Admin\DataAngkatanController;
use App\Http\Controllers\Admin\DataTahunAjaranController;
use App\Http\Controllers\Admin\DataAdminController;
use App\Http\Controllers\Admin\ProfilSiswaController;

// Route untuk Kepala Sekolah
use App\Http\Controllers\Kepala_Sekolah\DashboardKepalaSekolahController;
use App\Http\Controllers\Kepala_Sekolah\KS_LaporanTunggakanSiswaController;
use App\Http\Controllers\Kepala_Sekolah\KS_LaporanPenerimaanController;
use App\Http\Controllers\Kepala_Sekolah\KS_DataAdminController;

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
        } elseif (Auth::user()->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard');
        } elseif (Auth::user()->isSiswa()) {
            return redirect()->route('siswa.dashboard');
        } elseif (Auth::user()->isKepalaSekolah()) {
            return redirect()->route('kepala_sekolah.dashboard');
        }
    }
    return redirect()->route('login');
});

// ---------------------------
//
// {--- Route untuk Siswa ---}
//
// ---------------------------
Route::middleware(['auth', 'siswa'])->prefix('siswa')->group(function() {
    Route::get('/', function () {
        return redirect()->route('siswa.dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('siswa.dashboard');

    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('siswa.profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('siswa.profil.update');

    Route::get('/tagihan', [TagihanController::class, 'index'])->name('siswa.tagihan.index');
    Route::get('/riwayat-pembayaran', [PembayaranController::class, 'index'])->name('siswa.pembayaran.index');

    // Routes pembayaran - perbaiki parameter
    Route::post('/tagihan/{tagihanId}/process', [TagihanController::class, 'process'])->name("checkout-process");
    Route::get('/checkout/{transaction}', [TagihanController::class, 'checkout'])->name("checkout");

    // Download PDF invoice
    Route::get('/transaksi/{transaction}/pdf', [TransactionController::class, 'pdf'])
        ->name('siswa.transaksi.pdf');

    // Detail transaksi (HTML)
    Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])
        ->name('siswa.transaksi.show');
});

// Webhook routes
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// Tambahkan routes untuk Midtrans redirect
Route::get('/midtrans/finish', [MidtransController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [MidtransController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [MidtransController::class, 'error'])->name('midtrans.error');


// ---------------------------
//
// {--- Route untuk Super Admin ---}
//
// ---------------------------
Route::middleware(['auth', 'super_admin'])->prefix('super_admin')->group(function() {
    // Route untuk Dashboard
    Route::get('/', function () {
        return redirect()->route('super_admin.dashboard');
    });
    Route::get('/dashboard', [DashboardSuperAdminController::class, 'index'])->name('super_admin.dashboard');

    // Route untuk Data Siswa
    Route::get('/data_siswa', [SA_DataSiswaAdminController::class, 'index'])->name('super_admin.data_siswa');
    Route::get('/data_siswa/create', [SA_DataSiswaAdminController::class, 'create'])->name('super_admin.data_siswa.create');
    Route::get('/data_siswa/{id}/edit', [SA_DataSiswaAdminController::class, 'edit'])->name('super_admin.data_siswa.edit');
    Route::put('/data_siswa/{id}', [SA_DataSiswaAdminController::class, 'update'])->name('super_admin.data_siswa.update');
    Route::delete('/data_siswa/{id}', [SA_DataSiswaAdminController::class, 'destroy'])->name('super_admin.data_siswa.destroy');
    Route::post('/data_siswa', [SA_DataSiswaAdminController::class, 'store'])->name('super_admin.data_siswa.store');

    Route::get('/profil_siswa/{student}/edit', [SA_ProfilSiswaController::class, 'edit'])->name('super_admin.profil_siswa.edit');
    Route::put('/profil_siswa/{student}', [SA_ProfilSiswaController::class, 'update'])->name('super_admin.profil_siswa.update');

    // Route untuk Data Tagihan Siswa
    Route::get('/data_tagihan_siswa', [SA_DataTagihanSiswaAdminController::class, 'index'])->name('super_admin.data_tagihan_siswa');
    Route::get('/data_tagihan_siswa/create', [SA_DataTagihanSiswaAdminController::class, 'create'])->name('super_admin.data_tagihan_siswa.create');
    Route::post('/data_tagihan_siswa/store', [SA_DataTagihanSiswaAdminController::class, 'store'])->name('super_admin.data_tagihan_siswa.store');
    Route::post('/data_tagihan_siswa/check_duplicate', [SA_DataTagihanSiswaAdminController::class, 'checkDuplicate'])->name('super_admin.data_tagihan_siswa.check_duplicate');
    Route::get('/data_tagihan_siswa/autocomplete', [SA_DataTagihanSiswaAdminController::class, 'autocomplete'])->name('super_admin.data_tagihan_siswa.autocomplete');
    Route::post('/data_tagihan_siswa', [SA_DataTagihanSiswaAdminController::class, 'searchById'])->name('super_admin.data_tagihan_siswa.search.byId');
    Route::post('/data_tagihan_siswa/search', [SA_DataTagihanSiswaAdminController::class, 'search'])->name('super_admin.data_tagihan_siswa.search');
    Route::get('/data_tagihan_siswa/students', [SA_DataTagihanSiswaAdminController::class, 'students'])->name('super_admin.data_tagihan_siswa.students');
    Route::delete('/data_tagihan_siswa/{fee}', [SA_DataTagihanSiswaAdminController::class,'destroy'])->name('super_admin.data_tagihan_siswa.destroy');

    // Route untuk Laporan Tunggakan Siswa
    Route::get('/laporan_tunggakan_siswa', [SA_LaporanTunggakanSiswaController::class, 'index'])->name('super_admin.laporan_tunggakan_siswa');
    Route::get('/laporan-tunggakan-siswa/pdf',  [SA_LaporanTunggakanSiswaController::class, 'pdf'])->name('super_admin.laporan_tunggakan_siswa.pdf');
    
    // Route untuk Laporan Penerimaan
    Route::get('/laporan_penerimaan', [SA_LaporanPenerimaanController::class, 'index'])->name('super_admin.laporan_penerimaan');
    Route::get('laporan-penerimaan/pdf',  [SA_LaporanPenerimaanController::class,'pdf'])->name('super_admin.laporan_penerimaan.pdf');

    // Route untuk Data Kelas
    Route::get('/data_kelas', [SA_DataKelasController::class, 'index'])->name('super_admin.data_kelas');
    Route::get('/data_kelas/create', [SA_DataKelasController::class, 'create'])->name('super_admin.data_kelas.create');
    Route::get('/data_kelas/{id}/edit', [SA_DataKelasController::class, 'edit'])->name('super_admin.data_kelas.edit');
    Route::put('/data_kelas/{id}', [SA_DataKelasController::class, 'update'])->name('super_admin.data_kelas.update');
    Route::delete('/data_kelas/{id}', [SA_DataKelasController::class, 'destroy'])->name('super_admin.data_kelas.destroy');
    Route::post('/data_kelas', [SA_DataKelasController::class, 'store'])->name('super_admin.data_kelas.store');
    
    // Route untuk Data Angkatan
    Route::get('/data_angkatan', [SA_DataAngkatanController::class, 'index'])->name('super_admin.data_angkatan');
    Route::get('/data_angkatan/create', [SA_DataAngkatanController::class, 'create'])->name('super_admin.data_angkatan.create');
    Route::get('/data_angkatan/{id}/edit', [SA_DataAngkatanController::class, 'edit'])->name('super_admin.data_angkatan.edit');
    Route::put('/data_angkatan/{id}', [SA_DataAngkatanController::class, 'update'])->name('super_admin.data_angkatan.update');
    Route::delete('/data_angkatan/{id}', [SA_DataAngkatanController::class, 'destroy'])->name('super_admin.data_angkatan.destroy');
    Route::post('/data_angkatan', [SA_DataAngkatanController::class, 'store'])->name('super_admin.data_angkatan.store');

    // Route untuk Data Tahun Ajaran
    Route::get('/data_tahun_ajaran', [SA_DataTahunAjaranController::class, 'index'])->name('super_admin.data_tahun_ajaran');
    Route::get('/data_tahun_ajaran/create', [SA_DataTahunAjaranController::class, 'create'])->name('super_admin.data_tahun_ajaran.create');
    Route::get('/data_tahun_ajaran/{id}/edit', [SA_DataTahunAjaranController::class, 'edit'])->name('super_admin.data_tahun_ajaran.edit');
    Route::put('/data_tahun_ajaran/{id}', [SA_DataTahunAjaranController::class, 'update'])->name('super_admin.data_tahun_ajaran.update');
    Route::delete('/data_tahun_ajaran/{id}', [SA_DataTahunAjaranController::class, 'destroy'])->name('super_admin.data_tahun_ajaran.destroy');
    Route::post('/data_tahun_ajaran', [SA_DataTahunAjaranController::class, 'store'])->name('super_admin.data_tahun_ajaran.store');

    // Route untuk Data Admin
    Route::get('/data_admin', [SA_DataAdminController::class, 'index'])->name('super_admin.data_admin');
    Route::get('/data_admin/create', [SA_DataAdminController::class, 'create'])->name('super_admin.data_admin.create');
    Route::get('/data_admin/{id}/edit', [SA_DataAdminController::class, 'edit'])->name('super_admin.data_admin.edit');
    Route::put('/data_admin/{id}', [SA_DataAdminController::class, 'update'])->name('super_admin.data_admin.update');
    Route::delete('/data_admin/{id}', [SA_DataAdminController::class, 'destroy'])->name('super_admin.data_admin.destroy');
    Route::post('/data_admin', [SA_DataAdminController::class, 'store'])->name('super_admin.data_admin.store');

    Route::post('/school-fees/{fee}/mark-paid-offline',
        [DataTagihanSiswaAdminController::class, 'markPaidOffline'])
        ->name('super_admin.school-fees.mark-paid-offline');

    // PDF invoice untuk admin (tanpa guard siswa)
    Route::get('/transactions/{transaction}/invoice',
        [TransactionController::class, 'pdfAdmin'])
        ->name('super_admin.transactions.pdf');

    Route::get('/transactions/{transaction}', [TransactionController::class, 'showAdmin'])
        ->name('super_admin.transactions.show');

});


// ---------------------------
//
// {--- Route untuk Admin ---}
//
// ---------------------------
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

    Route::get('/profil_siswa/{student}/edit', [ProfilSiswaController::class, 'edit'])->name('admin.profil_siswa.edit');
    Route::put('/profil_siswa/{student}', [ProfilSiswaController::class, 'update'])->name('admin.profil_siswa.update');

    // Route untuk Data Tagihan Siswa
    Route::get('/data_tagihan_siswa', [DataTagihanSiswaAdminController::class, 'index'])->name('admin.data_tagihan_siswa');
    Route::get('/data_tagihan_siswa/create', [DataTagihanSiswaAdminController::class, 'create'])->name('admin.data_tagihan_siswa.create');
    Route::post('/data_tagihan_siswa/store', [DataTagihanSiswaAdminController::class, 'store'])->name('admin.data_tagihan_siswa.store');
    Route::post('/data_tagihan_siswa/check_duplicate', [DataTagihanSiswaAdminController::class, 'checkDuplicate'])->name('admin.data_tagihan_siswa.check_duplicate');
    Route::get('/data_tagihan_siswa/autocomplete', [DataTagihanSiswaAdminController::class, 'autocomplete'])->name('admin.data_tagihan_siswa.autocomplete');
    Route::post('/data_tagihan_siswa', [DataTagihanSiswaAdminController::class, 'searchById'])->name('admin.data_tagihan_siswa.search.byId');
    Route::post('/data_tagihan_siswa/search', [DataTagihanSiswaAdminController::class, 'search'])->name('admin.data_tagihan_siswa.search');
    Route::get('/data_tagihan_siswa/students', [DataTagihanSiswaAdminController::class, 'students'])->name('admin.data_tagihan_siswa.students');
    Route::delete('/data_tagihan_siswa/{fee}', [DataTagihanSiswaAdminController::class,'destroy'])->name('admin.data_tagihan_siswa.destroy');

    // Route untuk Laporan Tunggakan Siswa
    Route::get('/laporan_tunggakan_siswa', [LaporanTunggakanSiswaController::class, 'index'])->name('admin.laporan_tunggakan_siswa');
    Route::get('/laporan-tunggakan-siswa/pdf',  [LaporanTunggakanSiswaController::class, 'pdf'])->name('admin.laporan_tunggakan_siswa.pdf');
    
    // Route untuk Laporan Penerimaan
    Route::get('/laporan_penerimaan', [LaporanPenerimaanController::class, 'index'])->name('admin.laporan_penerimaan');
    Route::get('laporan-penerimaan/pdf',  [LaporanPenerimaanController::class,'pdf'])->name('admin.laporan_penerimaan.pdf');

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

    // Route untuk Data Admin
    Route::get('/data_admin', [DataAdminController::class, 'index'])->name('admin.data_admin');
    Route::get('/data_admin/create', [DataAdminController::class, 'create'])->name('admin.data_admin.create');
    Route::get('/data_admin/{id}/edit', [DataAdminController::class, 'edit'])->name('admin.data_admin.edit');
    Route::put('/data_admin/{id}', [DataAdminController::class, 'update'])->name('admin.data_admin.update');
    Route::delete('/data_admin/{id}', [DataAdminController::class, 'destroy'])->name('admin.data_admin.destroy');
    Route::post('/data_admin', [DataAdminController::class, 'store'])->name('admin.data_admin.store');

    Route::post('/school-fees/{fee}/mark-paid-offline',
        [DataTagihanSiswaAdminController::class, 'markPaidOffline'])
        ->name('admin.school-fees.mark-paid-offline');

    // PDF invoice untuk admin (tanpa guard siswa)
    Route::get('/transactions/{transaction}/invoice',
        [TransactionController::class, 'pdfAdmin'])
        ->name('admin.transactions.pdf');

    Route::get('/transactions/{transaction}', [TransactionController::class, 'showAdmin'])
        ->name('admin.transactions.show');

});



Route::get('/dev/send-invoice/{transaction}', [TransactionController::class, 'testSendInvoice'])
    ->middleware(['auth']); // batasi akses

// Route untuk Kepala Sekolah
Route::middleware(['auth', 'kepala_sekolah'])->prefix('kepala_sekolah')->group(function() {
    // Route untuk Dashboard
    Route::get('/', function () {
        return redirect()->route('kepala_sekolah.dashboard');
    });
    Route::get('/dashboard', [DashboardKepalaSekolahController::class, 'index'])->name('kepala_sekolah.dashboard');

    // Laporan Tunggakan (lihat + pdf)
        Route::get('/laporan_tunggakan_siswa', [KS_LaporanTunggakanSiswaController::class, 'index'])
            ->name('kepala_sekolah.laporan_tunggakan_siswa');
        Route::get('/laporan-tunggakan-siswa/pdf', [KS_LaporanTunggakanSiswaController::class, 'pdf'])
            ->name('kepala_sekolah.laporan_tunggakan_siswa.pdf');

        // Laporan Penerimaan (lihat + pdf)
        Route::get('/laporan_penerimaan', [KS_LaporanPenerimaanController::class, 'index'])
            ->name('kepala_sekolah.laporan_penerimaan');
        Route::get('/laporan-penerimaan/pdf', [KS_LaporanPenerimaanController::class, 'pdf'])
            ->name('kepala_sekolah.laporan_penerimaan.pdf');

        // Data Admin (read-only untuk kepala sekolah)
        Route::get('/data_admin', [KS_DataAdminController::class, 'index'])
            ->name('kepala_sekolah.data_admin');
});

require __DIR__.'/auth.php';
