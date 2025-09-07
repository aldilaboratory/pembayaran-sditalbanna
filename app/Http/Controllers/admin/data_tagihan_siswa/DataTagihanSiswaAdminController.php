<?php

namespace App\Http\Controllers\Admin\Data_Tagihan_Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolFeesRequest;
use App\Http\Requests\StoreSchoolYearsRequest;
use App\Models\AcademicYear;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataTagihanSiswaAdminController extends Controller
{
    public function index() {
        return view('admin.data_tagihan_siswa.index');
    }

    public function autocomplete(Request $request)
    {
        $search = $request->get('q');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $students = Student::where('nis', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->with('studentClass')
            ->limit(10)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'nis' => $student->nis,
                    'nama' => $student->nama,
                    'kelas' => $student->studentClass->class ?? '-',
                    // display untuk dropdown list
                    'display' => $student->nis . ' - ' . $student->nama . ' (' . ($student->studentClass->class) . ')' ?? '-',
                    // value yang akan ditampilkan di input setelah dipilih
                    'inputValue' => $student->nis . ' - ' . $student->nama . ' (' . ($student->studentClass->class) . ')' ?? '-',
                ];
            });

        return response()->json($students);
    }

    /**
     * Search by student ID (dari autocomplete)
     */
    public function searchById(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        $studentId = $request->student_id;
        
        $student = Student::with([
            'studentClass', 
            'schoolYear',
            'schoolFee' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
        ])->find($studentId);

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('admin.data_tagihan_siswa.index', compact('student'));
    }

    public function search(Request $request) {
        $request->validate([
            'search' => 'required|string|min:1',
        ]);

        $search = $request->search;

        // Cari siswa berdasarkan NIS atau Nama
        $student = Student::where('nis', $search)
                            ->orWhere('nama', 'like', "%{$search}%")
                            ->with(['studentClass', 'academicYear'])
                            ->first();

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan');
        }

        return view('admin.data_tagihan_siswa.index', compact('student'));
    }

    public function create() {
        $studentClasses = StudentClass::all()->sortBy('class');
        $academicYears = AcademicYear::all()->sortBy('academic_year');
        
        return view('admin.data_tagihan_siswa.create', compact('studentClasses', 'academicYears'));
    }

    public function store(StoreSchoolFeesRequest $request) {
        try {
            DB::beginTransaction();
            
            // Ambil semua siswa di kelas yang dipilih
            $students = Student::where('class_id', $request->class_id)
                              ->where('status', 'aktif') // Hanya siswa aktif
                              ->get();
            
            if ($students->isEmpty()) {
                return back()->with('error', 'Tidak ada siswa aktif di kelas yang dipilih.');
            }
            
            foreach ($students as $student) {
                // Buat tagihan baru
                SchoolFee::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $request->tahun_ajaran,
                    'bulan' => $request->bulan,
                    'jenis_tagihan' => $request->jenis_tagihan,
                    'jumlah' => $request->jumlah,
                    'sisa' => $request->jumlah, // Sisa sama dengan jumlah saat di awal
                    'jatuh_tempo' => $request->jatuh_tempo,
                    'status' => 'belum_lunas',
                ]);
            }
            
            DB::commit();
            $count = $students->count();
            return redirect()
                ->route('admin.data_tagihan_siswa.create')
                ->with('success', "Berhasil membuat tagihan untuk {$count} siswa aktif.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Gagal membuat tagihan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
