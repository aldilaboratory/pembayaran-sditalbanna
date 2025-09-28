<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $classes = StudentClass::orderBy('class')->get(['id','class']);
        $academicYears = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')->get(['id','academic_year']);

        // optional: preselect berdasar query
        $selectedFrom = $request->query('from_class_id');
        $selectedTo   = $request->query('to_class_id');
        $selectedAy   = $request->query('academic_year_id');

        return view('admin.kenaikan.index', compact('classes','academicYears','selectedFrom','selectedTo','selectedAy'));
    }

    // AJAX: daftar siswa aktif pada kelas sumber
    public function students(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required','exists:student_classes,id'],
        ]);

        $q = Student::query()
            ->select('id','nis','nama','class_id','status')
            ->where('class_id', $validated['class_id'])
            ->orderBy('nama');

        // filter status aktif bila ada kolomnya
        if (Schema::hasColumn('students','status')) {
            $q->where(function ($w){
                $w->whereRaw('LOWER(status)=?', ['aktif'])
                  ->orWhereRaw('LOWER(status)=?', ['active'])
                  ->orWhereIn('status',[1,'1',true]);
            });
        }

        $rows = $q->get();

        return response()->json([
            'count'    => $rows->count(),
            'students' => $rows, // [{id, nis, nama}]
        ]);
    }

    // Proses kenaikan
    public function promote(Request $request)
{
    $mode = $request->string('mode', 'promote')->toString();

    $baseRules = [
        'from_class_id' => ['required','exists:student_classes,id'],
        'student_ids'   => ['required','array','min:1'],
        'student_ids.*' => ['integer','exists:students,id'],
    ];
    if ($mode === 'promote') {
        $baseRules['to_class_id'] = ['required','different:from_class_id','exists:student_classes,id'];
    }

    $validated = $request->validate($baseRules);

    $ids = collect($validated['student_ids']);

    // Hanya siswa dari kelas awal & status aktif
    $students = \App\Models\Student::whereIn('id', $ids)
        ->where('class_id', $validated['from_class_id'])
        ->where('status', 'aktif')
        ->get();

    if ($students->isEmpty()) {
        return back()->with('error','Tidak ada siswa aktif yang cocok dengan kelas awal.');
    }

    if ($mode === 'graduate') {
        // BLOKIR jika masih ada tunggakan
        $unpaid = \App\Models\SchoolFee::whereIn('student_id', $students->pluck('id'))
            ->whereNotIn('status', ['lunas'])   // belum_lunas, cicilan, pending, dsb
            ->count();

        if ($unpaid > 0) {
            return back()->with('error', "Tidak bisa meluluskan: ada {$unpaid} tagihan yang belum lunas. Selesaikan dulu.");
        }

        \DB::transaction(function() use ($students) {
            foreach ($students as $s) {
                $s->update([
                    'status'       => 'lulus',     // atau 'alumni'
                    'graduated_at' => now(),       // pastikan kolom ini ada (lihat migration)
                    // 'class_id'   => null,       // opsional: kosongkan kelas
                ]);
            }
        });

        return back()->with('success', 'Siswa berhasil diluluskan.');
    }

    // MODE: PROMOTE (naik kelas)
    $toClassId = (int) $validated['to_class_id'];
    \DB::transaction(function() use ($students, $toClassId) {
        foreach ($students as $s) {
            $s->update(['class_id' => $toClassId]);
        }
    });

    return back()->with('success', 'Siswa berhasil dinaikkan kelas.');
}
}
