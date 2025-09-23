<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicYearsRequest;
use App\Http\Requests\UpdateAcademicYearsRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class SA_DataTahunAjaranController extends Controller
{
    public function index() {
        $academicYears = AcademicYear::all()->sortBy('academic_year');

        return view('super_admin.data_tahun_ajaran.index', compact('academicYears'));
    }

    public function create() {
        return view('super_admin.data_tahun_ajaran.create');
    }

    public function store(StoreAcademicYearsRequest $request) {
        AcademicYear::create($request->validated());

        return redirect()->route('super_admin.data_tahun_ajaran')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id) {
        $academicYears = academicYear::findOrFail($id);

        return view('super_admin.data_tahun_ajaran.edit', compact('academicYears'));
    }

    public function update(UpdateAcademicYearsRequest $request, $id) {
        $academicYears = academicYear::findOrFail($id);
        $academicYears->update($request->validated());

        return redirect()->route('super_admin.data_tahun_ajaran')
                         ->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id) {
        $academicYears = academicYear::findOrFail($id);
        // PRE-CHECK: apakah masih dipakai siswa?
        $countStudentUsed = $academicYears->student()->count();
        $countSchoolFeeUsed = $academicYears->schoolFee()->count();
        if ($countStudentUsed > 0 || $countSchoolFeeUsed > 0) {
            return redirect()
                ->route('super_admin.data_tahun_ajaran')
                ->with('error', "Tahun ajaran $academicYears->academic_year tidak bisa dihapus: masih dipakai oleh {$countStudentUsed} siswa dan {$countSchoolFeeUsed} tagihan.");
        }
        $academicYears->delete();

        return redirect()->route('super_admin.data_tahun_ajaran')
                         ->with('success', 'Data berhasil dihapus!');
    }
}
