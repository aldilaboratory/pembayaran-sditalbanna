<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDataAdminRequest;
use App\Http\Requests\UpdateDataAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KS_DataAdminController extends Controller
{
    public function index() {
        $admins = User::where('role', 'admin')
                        ->orWhere('role', 'kepala_sekolah')
                        ->orWhere('role', 'super_admin')
                        ->get();

        return view('kepala_sekolah.data_admin.index', compact('admins'));
    }

    public function create() {
        return view('kepala_sekolah.data_admin.create');
    }


    public function store(StoreDataAdminRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']); // ← hash!

        User::create($data);

        return redirect()->route('kepala_sekolah.data_admin')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        return view('kepala_sekolah.data_admin.edit', compact('user'));
    }

    public function update(UpdateDataAdminRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validated();
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']);
        else unset($data['password']);

        $user->update($data);

        return redirect()->route('kepala_sekolah.data_admin')
                        ->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);

    if (auth()->id() === $user->id) {
        return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
    }

    try {
        $user->delete();
        return back()->with('success', 'Admin berhasil dihapus!');
    } catch (\Throwable $e) {
        if ($e->getCode() === '23000') {
            return back()->with('error', 'Gagal menghapus: data masih terhubung (foreign key).');
        }
        return back()->with('error', 'Gagal menghapus: '.$e->getMessage());
    }
}
}
