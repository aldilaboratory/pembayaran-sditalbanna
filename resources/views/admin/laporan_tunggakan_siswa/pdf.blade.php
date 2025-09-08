@php
  $fmt = fn($v) => is_numeric($v) ? number_format($v,0,',','.') : $v;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Tunggakan Siswa</title>
<style>
  @page { size: A4 landscape; margin: 6mm; }
  * { font-family: DejaVu Sans, Arial, sans-serif; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: .6px solid #555; padding: 2px 3px; font-size: 9px; line-height: 1.2; }
  th { background: #f3f3f3; }
  thead { display: table-header-group; } /* header ulang tiap halaman */
  .right { text-align: right; }
  .center { text-align: center; }
  .nowrap { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
</head>
<body>
  <h2 class="center">Laporan Tunggakan Siswa</h2>
  <p class="meta">
    Tahun Ajaran: <strong>{{ $selectedYearLabel ?? '-' }}</strong>
    @if(!empty($selectedClassLabel))
      &nbsp;&nbsp;|&nbsp;&nbsp; Kelas: <strong>{{ $selectedClassLabel }}</strong>
    @endif
  </p>

  <table>
    <colgroup>
    <col style="width:8mm">   {{-- # --}}
    <col style="width:22mm">  {{-- NIK --}}
    <col style="width:34mm">  {{-- Nama --}}
    @foreach ($months as $m)
      <col style="width:11mm"> {{-- 12 kolom bulan --}}
    @endforeach
    <col style="width:16mm">  {{-- Daftar Ulang --}}
    <col style="width:16mm">  {{-- Pengembangan --}}
    <col style="width:16mm">  {{-- Operasional --}}
    <col style="width:20mm">  {{-- Total --}}
  </colgroup>

  <thead>
    <tr>
      <th class="center">#</th>
      <th class="center">NIK</th>
      <th>Nama Siswa</th>
      <th class="center">Jul</th><th class="center">Ags</th><th class="center">Sep</th>
      <th class="center">Okt</th><th class="center">Nov</th><th class="center">Des</th>
      <th class="center">Jan</th><th class="center">Feb</th><th class="center">Mar</th>
      <th class="center">Apr</th><th class="center">Mei</th><th class="center">Jun</th>
      <th class="center">Daftar Ulang</th>
      <th class="center">Pengembangan</th>
      <th class="center">Operasional</th>
      <th class="center">Total</th>
    </tr>
  </thead>
    <tbody>
    @foreach ($rows as $r)
        <tr>
        <td class="center w-no">{{ $r['no'] }}</td>
        <td class="center w-nik nowrap">{{ $r['nik'] }}</td>
        <td class="w-nama nowrap">{{ $r['nama'] }}</td>

        <td class="right w-month">{{ $fmt($r['m_7']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_8']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_9']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_10'] ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_11'] ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_12'] ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_1']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_2']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_3']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_4']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_5']  ?? '-') }}</td>
        <td class="right w-month">{{ $fmt($r['m_6']  ?? '-') }}</td>

        <td class="right w-fee">{{ $fmt($r['du']) }}</td>
        <td class="right w-fee">{{ $fmt($r['bp']) }}</td>
        <td class="right w-fee">{{ $fmt($r['bo']) }}</td>
        <td class="right w-total"><strong>{{ $fmt($r['total']) }}</strong></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="17"></td>
        <td class="right"><strong>Total</strong></td>
        <td class="right"><strong>{{ $fmt($grandTotal) }}</strong></td>
    </tr>
    </tbody>
  </table>
  <p>*Nilai dalam Rupiah</p>
</body>
</html>
