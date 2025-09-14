<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ProfilSiswaController extends Controller
{
    public function edit(Student $student) {
        return view('admin.profil_siswa.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'nama'   => 'required|string|max:255',
            'nis'    => 'required|string|max:30',
            'nik'    => 'nullable|digits_between:8,20',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'agama'  => 'nullable|in:islam,kristen,katolik,hindu,buddha,konghucu',
            'alamat' => 'nullable|string|max:255',
            'tinggal_dengan' => 'nullable|in:dengan orang tua,dengan wali,sendiri',
            'kewarganegaraan' => 'nullable|in:wni,wna',
            'nama_ibu_kandung'  => 'nullable|string|max:255',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nama_wali'         => 'nullable|string|max:255',
            'nomor_whatsapp_orang_tua_wali' => 'nullable|string|max:20',
        ]);

        $student->update($data);

        return redirect()
            ->route('admin.profil_siswa.edit', $student)
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
