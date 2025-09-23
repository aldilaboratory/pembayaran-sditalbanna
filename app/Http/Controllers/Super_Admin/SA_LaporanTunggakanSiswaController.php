<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SA_LaporanTunggakanSiswaController extends Controller
{
    public function index(Request $request) {
        // Ambil nilai filter dari query string
        $academicYearId = $request->query('academic_year_id');
        $classId        = $request->query('class_id');

        // Default tahun ajaran = yang terbaru kalau tidak dipilih
        if (!$academicYearId) {
            $academicYearId = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
                ->value('id');
        }
        $selectedYearLabel = AcademicYear::find($academicYearId)?->academic_year;

        // Opsi dropdown
        $academicYears = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
            ->get(['id','academic_year']);
        $classes = StudentClass::orderBy('class')->get(['id','class']);

        // Urutan bulan ajaran: Jul→Jun
        $months = [7,8,9,10,11,12,1,2,3,4,5,6];
        $monthLabels = [7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun'];

        // Ambil siswa + fee utk tahun ajaran terpilih; filter kelas jika dipilih
        $students = Student::query()
        ->when($classId, fn($q) => $q->where('class_id', $classId))
        ->whereHas('schoolFee', function ($q) use ($academicYearId) {
            $q->where('academic_year_id', $academicYearId);
        })
        ->with(['schoolFee' => function ($q) use ($academicYearId) {
            $q->where('academic_year_id', $academicYearId)
            ->select('id','student_id','bulan','jenis_tagihan','jumlah','status');
        }])
        ->orderBy('nama')
        ->get();

        // Susun data tabel
        $rows = [];
        $grandTotal = 0;

        foreach ($students as $i => $s) {
            $fees = $s->schoolFee ?? collect();

            $row = [
                'no'   => $i + 1,
                'nis'  => $s->nis,
                'nama' => $s->nama,
            ];

            $total = 0;

            // SPP per bulan
            foreach ($months as $m) {
                $sppNotPaid = $fees
                    ->where('jenis_tagihan', 'spp')
                    ->where('bulan', $m)
                    ->filter(fn($f) => strtolower($f->status ?? '') !== 'lunas')
                    ->sum('jumlah');

                if ($sppNotPaid > 0) {
                    $row['m_'.$m] = (int) $sppNotPaid;
                    $total += (int) $sppNotPaid;
                } else {
                    // Jika ada fee SPP bulan tsb tapi lunas → "Lunas", kalau gak ada fee → "-"
                    $row['m_'.$m] = $fees->where('jenis_tagihan','spp')->where('bulan',$m)->isNotEmpty() ? 'Lunas' : '-';
                }
            }

            // Biaya satu-kali/insidentil (akumulasi semua bulan)
            $duFees     = $fees->where('jenis_tagihan','daftar_ulang');
            $duNotPaid  = (int) $duFees->filter(fn($f) => strtolower($f->status ?? '') !== 'lunas')->sum('jumlah');
            $row['du']  = $duNotPaid > 0 ? $duNotPaid : ($duFees->isNotEmpty() ? 'Lunas' : '-');

            $bpFees     = $fees->where('jenis_tagihan','biaya_pengembangan');
            $bpNotPaid  = (int) $bpFees->filter(fn($f) => strtolower($f->status ?? '') !== 'lunas')->sum('jumlah');
            $row['bp']  = $bpNotPaid > 0 ? $bpNotPaid : ($bpFees->isNotEmpty() ? 'Lunas' : '-');

            $boFees     = $fees->where('jenis_tagihan','biaya_operasional');
            $boNotPaid  = (int) $boFees->filter(fn($f) => strtolower($f->status ?? '') !== 'lunas')->sum('jumlah');
            $row['bo']  = $boNotPaid > 0 ? $boNotPaid : ($boFees->isNotEmpty() ? 'Lunas' : '-');

            // total hanya angka tunggakan
            $total += $duNotPaid + $bpNotPaid + $boNotPaid;

            $row['total'] = $total;
            $grandTotal  += $total;
            $rows[] = $row;
        }

        return view('super_admin.laporan_tunggakan_siswa.index', compact(
            'rows','months','monthLabels','grandTotal','academicYears','classes','academicYearId','classId','selectedYearLabel'
        ));
    }

     public function pdf(Request $request)
    {
        [$rows,$months,$monthLabels,$grandTotal,$academicYears,$classes,$academicYearId,$classId,$selectedYearLabel,$selectedClassLabel]
            = $this->buildReportData($request);

        $pdf = Pdf::loadView('super_admin.laporan_tunggakan_siswa.pdf', compact(
            'rows','months','monthLabels','grandTotal',
            'selectedYearLabel','selectedClassLabel'
        ))
        ->setPaper('a4','landscape'); // tabel lebar → landscape

        $fname = 'laporan-tunggakan-'.now()->format('Ymd-His').'.pdf';
        return $pdf->download($fname); // atau ->stream($fname) untuk preview
    }

    /** Kumpulkan data laporan (dipakai index & pdf) */
    private function buildReportData(Request $request)
    {
        $academicYearId = $request->query('academic_year_id');
        $classId        = $request->query('class_id');

        if (!$academicYearId) {
            $academicYearId = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
                ->value('id');
        }
        $selectedYearLabel  = AcademicYear::find($academicYearId)?->academic_year;
        $selectedClassLabel = $classId ? StudentClass::find($classId)?->class : null;

        $academicYears = AcademicYear::orderByRaw('CAST(LEFT(academic_year,4) AS UNSIGNED) DESC')
            ->get(['id','academic_year']);
        $classes = StudentClass::orderBy('class')->get(['id','class']);

        $months = [7,8,9,10,11,12,1,2,3,4,5,6];
        $monthLabels = [7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun'];

        $students = Student::with(['schoolFee' => function ($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId)
                  ->select('id','student_id','bulan','jenis_tagihan','jumlah','status');
            }])
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->orderBy('nama')
            ->get();

        $rows = [];
        $grandTotal = 0;

        foreach ($students as $i => $s) {
            $fees = $s->schoolFee ?? collect();

            $row = ['no'=>$i+1,'nik'=>$s->nik,'nama'=>$s->nama];
            $total = 0;

            foreach ($months as $m) {
                $sppNotPaid = $fees->where('jenis_tagihan','spp')
                                   ->where('bulan',$m)
                                   ->filter(fn($f)=>strtolower($f->status ?? '') !== 'lunas')
                                   ->sum('jumlah');

                if ($sppNotPaid > 0) {
                    $row['m_'.$m] = (int)$sppNotPaid;
                    $total += (int)$sppNotPaid;
                } else {
                    $row['m_'.$m] = $fees->where('jenis_tagihan','spp')->where('bulan',$m)->isNotEmpty() ? 'Lunas' : '-';
                }
            }

            // Biaya insidentil
            $duFees    = $fees->where('jenis_tagihan','daftar_ulang');
            $duNotPaid = (int)$duFees->filter(fn($f)=>strtolower($f->status ?? '')!=='lunas')->sum('jumlah');
            $row['du'] = $duNotPaid > 0 ? $duNotPaid : ($duFees->isNotEmpty() ? 'Lunas' : '-');

            $bpFees    = $fees->where('jenis_tagihan','biaya_pengembangan');
            $bpNotPaid = (int)$bpFees->filter(fn($f)=>strtolower($f->status ?? '')!=='lunas')->sum('jumlah');
            $row['bp'] = $bpNotPaid > 0 ? $bpNotPaid : ($bpFees->isNotEmpty() ? 'Lunas' : '-');

            $boFees    = $fees->where('jenis_tagihan','biaya_operasional');
            $boNotPaid = (int)$boFees->filter(fn($f)=>strtolower($f->status ?? '')!=='lunas')->sum('jumlah');
            $row['bo'] = $boNotPaid > 0 ? $boNotPaid : ($boFees->isNotEmpty() ? 'Lunas' : '-');

            $total += $duNotPaid + $bpNotPaid + $boNotPaid;

            $row['total'] = $total;
            $grandTotal  += $total;
            $rows[] = $row;
        }

        return [$rows,$months,$monthLabels,$grandTotal,$academicYears,$classes,$academicYearId,$classId,$selectedYearLabel,$selectedClassLabel];
    }
}
