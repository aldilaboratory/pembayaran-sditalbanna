<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Log;
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

    public function create(Request $request)
    {
        $studentClasses = StudentClass::orderBy('class')->get(['id','class']);
        $academicYears  = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')->get(['id','academic_year']);

        $selectedClassId = $request->query('class_id'); // filter dari query
        $students = collect();

        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)
                ->where('status', 'aktif')
                ->orderBy('nama')
                ->get(['id','nis','nama']);
        }

        return view('admin.data_tagihan_siswa.create', compact(
            'studentClasses','academicYears','selectedClassId','students'
        ));
    }

    public function students(Request $request)
    {
        try {
            $validated = $request->validate([
                'class_id' => ['required','exists:student_classes,id'],
            ]);

            $q = Student::query()
                ->select('id','nis','nama')
                ->where('class_id', (int)$validated['class_id'])
                ->orderBy('nama');

            // filter hanya jika kolom 'status' memang ada di DB production
            if (Schema::hasColumn('students', 'status')) {
                $q->where(function ($w) {
                    $w->whereRaw('LOWER(status)=?', ['aktif'])
                    ->orWhereRaw('LOWER(status)=?', ['active'])
                    ->orWhereIn('status', [1,'1',true]);
                });
            }

            $students = $q->get();

            return response()->json([
                'count'    => $students->count(),
                'students' => $students,
            ]);
        } catch (\Throwable $e) {
            Log::error('students endpoint error: '.$e->getMessage());
            return response()->json(['message'=>'Server error on students endpoint'], 500);
        }
    }

    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'jenis_tagihan' => 'required|string',
            'bulan' => 'required|string',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $duplicates = [];
        
        foreach ($request->student_ids as $studentId) {
            $exists = SchoolFee::where('academic_year_id', $request->academic_year_id)
                ->where('jenis_tagihan', $request->jenis_tagihan)
                ->where('bulan', $request->bulan)
                ->where('student_id', $studentId)
                ->exists();

            if ($exists) {
                $student = Student::find($studentId);
                $duplicates[] = $student->nama;
            }
        }

        if (!empty($duplicates)) {
            return response()->json([
                'duplicate' => true,
                'message' => 'Tagihan sudah ada untuk siswa: ' . implode(', ', $duplicates)
            ]);
        }

        return response()->json(['duplicate' => false]);
    }

    public function store(StoreSchoolFeesRequest $request)
    {
        try {
            DB::beginTransaction();

            // Ambil array siswa terpilih
            $studentIds = $request->input('student_ids', []);
            if (empty($studentIds)) {
                return back()->with('error', 'Pilih minimal satu siswa.')->withInput();
            }

            // Pengecekan khusus untuk jumlah tagihan = 0
            $jumlah = (int) $request->input('jumlah', 0);
            if ($jumlah === 0) {
                return back()->with('error', 'Gagal menambah data tagihan. Jumlah tagihan tidak boleh 0.')->withInput();
            }

            $created = 0; $skipped = 0;

            foreach ($studentIds as $sid) {
                // Hanya siswa aktif (hardening)
                $isActive = \App\Models\Student::where('id',$sid)
                            ->where('status','aktif')->exists();
                if (!$isActive) { $skipped++; continue; }

                // Kunci unik
                $attrs = [
                    'student_id'        => (int) $sid,
                    'academic_year_id'  => (int) $request->tahun_ajaran,
                    'bulan'             => (int) $request->bulan,
                    'jenis_tagihan'     => (string) $request->jenis_tagihan,
                ];

                // Nilai jika belum ada
                $vals = [
                    'jumlah'        => (int) $request->jumlah,
                    'sisa'          => (int) $request->jumlah,
                    'jatuh_tempo'   => $request->jatuh_tempo,
                    'status'        => 'belum_lunas',
                ];

                $fee = SchoolFee::firstOrCreate($attrs, $vals);
                if ($fee->wasRecentlyCreated) $created++; else $skipped++;
            }

            DB::commit();

            return redirect()
                ->route('admin.data_tagihan_siswa.create', ['class_id' => $request->class_id])
                ->with('success', "Tagihan dibuat: {$created}");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan: '.$e->getMessage())->withInput();
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
                // 'download_url' => $result['download_url'],
                'invoice_code' => $result['invoice_code'],
            ]);
        } catch (\Throwable $e) {
            \Log::error('markPaidOffline error: '.$e->getMessage());
            return response()->json(['ok'=>false,'message'=>'Terjadi kesalahan server.'], 500);
        }
    }

    public function destroy(Request $request, SchoolFee $fee)
    {
        // Larangan: sudah lunas → tak boleh dihapus
        if ($fee->status === 'lunas') {
            return $request->expectsJson()
                ? response()->json(['ok'=>false,'message'=>'Tidak bisa menghapus tagihan yang sudah lunas.'], 422)
                : back()->with('error','Tidak bisa menghapus tagihan yang sudah lunas.');
        }

        // Jika sudah ada transaksi sukses → tak boleh dihapus
        $hasSuccessTx = Transaction::where('school_fee_id', $fee->id)
                            ->where('status','success')->exists();
        if ($hasSuccessTx) {
            return $request->expectsJson()
                ? response()->json(['ok'=>false,'message'=>'Tagihan punya transaksi sukses.'], 422)
                : back()->with('error','Tagihan punya transaksi sukses.');
        }

        DB::transaction(function () use ($fee) {
            // Bersihkan transaksi non-sukses (pending/expired/failed/canceled)
            Transaction::where('school_fee_id', $fee->id)
                ->whereIn('status', ['pending','expired','failed','canceled'])
                ->delete();

            $fee->delete();
        });

        return $request->expectsJson()
            ? response()->json(['ok'=>true])
            : back()->with('success','Tagihan berhasil dihapus.');
    }
}
