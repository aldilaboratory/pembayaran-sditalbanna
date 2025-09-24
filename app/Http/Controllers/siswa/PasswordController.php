<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function edit()
    {
        // Kalau perlu tampilkan data siswa/username di form
        $student = Auth::user()->student; 
        return view('siswa.ubah_password.edit', compact('student'));
    }

    public function update(Request $request)
    {
        // Validasi: current_password harus cocok, password baru wajib konfirmasi
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed'                => 'Konfirmasi password baru tidak sama.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        // ganti remember_token agar sesi lama invalid (opsional tapi bagus)
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Kalau ingin tendang sesi di device lain:
        // Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
