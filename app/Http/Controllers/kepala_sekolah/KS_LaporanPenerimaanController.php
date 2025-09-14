<?php

namespace App\Http\Controllers\Kepala_Sekolah;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolFee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KS_LaporanPenerimaanController extends Controller
{
    public function index(Request $request) {
        // Filter tahun ajaran (default: terbaru)
    $academicYearId = $request->query('academic_year_id')
        ?? AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')->value('id');

    $academicYears = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
        ->get(['id','academic_year']);

    $ay = AcademicYear::findOrFail($academicYearId);
    $ayLabel   = $ay->academic_year;          // "2024/2025"
    $startYear = (int) substr($ayLabel, 0, 4);
    $endYear   = (int) substr($ayLabel, 5, 4);

    // Urutan akademik: Jul→Jun
    $months = [7,8,9,10,11,12,1,2,3,4,5,6];
    $monthNames = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];

    // Ambil fee LUNAS untuk AY terpilih (grouping pakai B U L A N, bukan tanggal_lunas)
    $fees = SchoolFee::with(['student.studentClass:id,class'])
        ->where('academic_year_id', $academicYearId)
        ->where('status', 'lunas')
        ->get(['id','student_id','bulan','jenis_tagihan','jumlah']);

    $rows = [];
    $grandTotal = 0;

    foreach ($months as $i => $m) {
        $calYear = $m >= 7 ? $startYear : $endYear;

        // Semua fee yg bulan-nya = $m
        $feesInMonth = $fees->where('bulan', $m);

        // SPP per kelas 1..6 (pastikan studentClass->class berisi angka 1..6)
        $spp = [];
        $sumRow = 0;
        for ($kelas = 1; $kelas <= 6; $kelas++) {
            $jumlah = (int) $feesInMonth
                ->filter(fn($f) =>
                    $f->jenis_tagihan === 'spp'
                    && (int) ($f->student?->studentClass?->class) === $kelas
                )
                ->sum('jumlah');

            $spp[$kelas] = $jumlah;
            $sumRow += $jumlah;
        }

        // Biaya lain (akumulasi semua kelas)
        $du = (int) $feesInMonth->where('jenis_tagihan','daftar_ulang')->sum('jumlah');
        $bp = (int) $feesInMonth->where('jenis_tagihan','biaya_pengembangan')->sum('jumlah');
        $bo = (int) $feesInMonth->where('jenis_tagihan','biaya_operasional')->sum('jumlah');

        $sumRow += $du + $bp + $bo;

        $rows[] = [
            'no'    => $i + 1,
            'bulan' => $monthNames[$m],
            'tahun' => $calYear,
            'spp1'  => $spp[1],
            'spp2'  => $spp[2],
            'spp3'  => $spp[3],
            'spp4'  => $spp[4],
            'spp5'  => $spp[5],
            'spp6'  => $spp[6],
            'du'    => $du,
            'bp'    => $bp,
            'bo'    => $bo,
            'total' => $sumRow,
        ];

        $grandTotal += $sumRow;
    }

    return view('kepala_sekolah.laporan_penerimaan.index', compact(
        'academicYears','academicYearId','ayLabel','rows','grandTotal'
    ));
    }

    public function pdf(Request $request) {
        [$academicYears,$academicYearId,$ayLabel,$rows,$grandTotal] = $this->buildData($request);

        $pdf = Pdf::loadView('kepala_sekolah.laporan_penerimaan.pdf', compact(
            'ayLabel','rows','grandTotal'
        ))->setPaper('a4','landscape');

        return $pdf->download('laporan-penerimaan-'.now()->format('Ymd-His').'.pdf');
        // atau ->stream(...) untuk preview di browser
    }

    /** Kumpulkan data laporan sesuai filter */
    private function buildData(Request $request)
    {
        // Filter AY (default terbaru)
        $academicYearId = $request->query('academic_year_id')
            ?? AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')->value('id');

        $academicYears = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
            ->get(['id','academic_year']);

        $ay = AcademicYear::findOrFail($academicYearId);
        $ayLabel   = $ay->academic_year;    // "2024/2025"
        $startYear = (int) substr($ayLabel, 0, 4);
        $endYear   = (int) substr($ayLabel, 5, 4);

        // Urutan akademik: Jul→Jun
        $months = [7,8,9,10,11,12,1,2,3,4,5,6];
        $monthNames = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        // Ambil fee LUNAS pada AY terpilih
        $fees = SchoolFee::with(['student.studentClass:id,class'])
            ->where('academic_year_id', $academicYearId)
            ->where('status', 'lunas')
            ->get(['id','student_id','bulan','jenis_tagihan','jumlah']);

        // Normalizer nama jenis_tagihan (jaga-jaga ada spasi/kapitalisasi)
        $norm = fn($s) => strtolower(trim((string)$s));

        $rows = [];
        $grandTotal = 0;

        foreach ($months as $i => $m) {
            $calYear = $m >= 7 ? $startYear : $endYear;

            $feesInMonth = $fees->where('bulan', $m);

            // SPP per kelas 1..6
            $spp = []; $sumRow = 0;
            for ($kelas=1; $kelas<=6; $kelas++) {
                $jumlah = (int) $feesInMonth
                    ->filter(fn($f) =>
                        $norm($f->jenis_tagihan) === 'spp'
                        && (int) ($f->student?->studentClass?->class) === $kelas
                    )
                    ->sum('jumlah');
                $spp[$kelas] = $jumlah;
                $sumRow += $jumlah;
            }

            // Biaya lain (semua kelas)
            $du = (int) $feesInMonth->filter(fn($f) => $norm($f->jenis_tagihan)==='daftar_ulang')->sum('jumlah');
            $bp = (int) $feesInMonth->filter(fn($f) => $norm($f->jenis_tagihan)==='biaya_pengembangan')->sum('jumlah');
            $bo = (int) $feesInMonth->filter(fn($f) => $norm($f->jenis_tagihan)==='biaya_operasional')->sum('jumlah');

            $sumRow += $du + $bp + $bo;

            $rows[] = [
                'no'    => $i+1,
                'bulan' => $monthNames[$m],
                'tahun' => $calYear,
                'spp1'  => $spp[1] ?? 0,
                'spp2'  => $spp[2] ?? 0,
                'spp3'  => $spp[3] ?? 0,
                'spp4'  => $spp[4] ?? 0,
                'spp5'  => $spp[5] ?? 0,
                'spp6'  => $spp[6] ?? 0,
                'du'    => $du,
                'bp'    => $bp,
                'bo'    => $bo,
                'total' => $sumRow,
            ];

            $grandTotal += $sumRow;
        }

        return [$academicYears,$academicYearId,$ayLabel,$rows,$grandTotal];
    }
}
