<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolYearsRequest;
use App\Http\Requests\UpdateSchoolYearsRequest;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DataAngkatanController extends Controller
{
    public function index() {
        $schoolYears = SchoolYear::all()->sortBy('school_year');

        return view('admin.data_angkatan.index', compact('schoolYears'));
    }

    public function create() {
        return view('admin.data_angkatan.create');
    }

    public function store(StoreSchoolYearsRequest $request) {
        SchoolYear::create($request->validated());

        return redirect()->route('admin.data_angkatan')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id) {
        $schoolYears = SchoolYear::findOrFail($id);

        return view('admin.data_angkatan.edit', compact('schoolYears'));
    }

    public function update(UpdateSchoolYearsRequest $request, $id) {
        $schoolYears = SchoolYear::findOrFail($id);
        $schoolYears->update($request->validated());

        return redirect()->route('admin.data_angkatan')
                         ->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id) {
        $schoolYears = SchoolYear::findOrFail($id);
        // PRE-CHECK: apakah masih dipakai siswa?
        $count = $schoolYears->student()->count();
        if ($count > 0) {
            return redirect()
                ->route('admin.data_angkatan')
                ->with('error', "Tahun angkatan $schoolYears->school_year tidak bisa dihapus: masih dipakai oleh {$count} siswa.");
        }
        $schoolYears->delete();

        return redirect()->route('admin.data_angkatan')
                         ->with('success', 'Data berhasil dihapus!');
    }
}
