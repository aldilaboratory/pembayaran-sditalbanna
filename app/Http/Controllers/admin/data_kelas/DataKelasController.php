<?php

namespace App\Http\Controllers\admin\data_kelas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentClassesRequest;
use App\Http\Requests\UpdateStudentClassesRequest;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class DataKelasController extends Controller
{
    public function index() {
        $classes = StudentClass::all()->sortBy('class');

        return view('admin.data_kelas.index', compact('classes'));
    }

    public function create() {
        return view('admin.data_kelas.create');
    }

    public function store(StoreStudentClassesRequest $request) {
        // $validated = $request->validate([
        //     'class' => 'required|max:50',
        // ]);
        // StudentClass::create($validated);

        StudentClass::create($request->validated());

        return redirect()->route('admin.data_kelas')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id) {
        $classes = StudentClass::findOrFail($id);

        return view('admin.data_kelas.edit', compact('classes'));
    }

    public function update(UpdateStudentClassesRequest $request, $id) {
        // $validated = $request->validate([
        //     'class' => 'required|max:50'
        // ]);=
        // StudentClass::create($validated);
        $classes = StudentClass::findOrFail($id);
        $classes->update($request->validated());

        return redirect()->route('admin.data_kelas')
                         ->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id) {
        $classes = StudentClass::findOrFail($id);
        $classes->delete();

        return redirect()->route('admin.data_kelas')
                         ->with('success', 'Data berhasil dihapus!');
    }
}

