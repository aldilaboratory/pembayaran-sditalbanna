<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function edit() {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

        return view('siswa.profil.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Student::where('user_id', auth()->id())->firstOrFail();

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

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
