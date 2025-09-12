<?php

namespace App\Http\Controllers\Admin\Data_Tagihan_Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolFeesRequest;
use App\Http\Requests\StoreSchoolYearsRequest;
use App\Models\AcademicYear;
use App\Models\SchoolFee;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

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
                              ->where('academic_year_id', $request->tahun_ajaran)
                              ->where('status', 'aktif') // Hanya siswa aktif
                              ->get();
            
            if ($students->isEmpty()) {
                return back()->with('error', 'Tidak ada siswa aktif di kelas yang dipilih.');
            }
            
            foreach ($students as $student) {
                // Buat tagihan baru
                SchoolFee::firstOrCreate([
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

    public function markPaidOffline(Request $request, SchoolFee $fee)
    {
        $v = Validator::make($request->all(), [
            'bukti'   => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'catatan' => 'nullable|string|max:255',
            // tidak ada 'jumlah' di sini
        ]);

        if ($v->fails()) {
            return response()->json(['ok'=>false,'errors'=>$v->errors()], 422);
        }

        if ($fee->status === 'lunas') {
            return response()->json(['ok'=>false,'message'=>'Tagihan sudah berstatus lunas.'], 422);
        }

        try {
            $result = DB::transaction(function () use ($request, $fee) {
                $path = $request->file('bukti')->store('payment_proofs', 'public');

                // Pakai nilai dari DB, bukan dari request
                $amount = (int) $fee->jumlah;

                $fee->status        = 'lunas';
                $fee->tanggal_lunas = now();
                $fee->save();

                $tx = Transaction::create([
                    'student_id'         => $fee->student_id,
                    'school_fee_id'      => $fee->id,
                    'jumlah'             => $amount,
                    'status'             => 'success',
                    'payment_type'       => 'offline',
                    'paid_at'            => now(),
                    'payment_proof_path' => $path,
                    'admin_note'         => $request->catatan,
                ]);

                return [
                    'download_url' => route('admin.transactions.pdf', $tx),
                    'invoice_code' => $tx->invoice_code,
                ];
            });

            return response()->json([
                'ok'           => true,
                'message'      => 'Tagihan ditandai lunas dan transaksi dibuat.',
                'download_url' => $result['download_url'],
                'invoice_code' => $result['invoice_code'],
            ]);
        } catch (\Throwable $e) {
            \Log::error('markPaidOffline error: '.$e->getMessage());
            return response()->json(['ok'=>false,'message'=>'Terjadi kesalahan server.'], 500);
        }
    }
}
