@php
  $fmt = fn($v) => number_format((int)$v, 0, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Laporan Penerimaan</title>
<style>
  @page { size: A4 landscape; margin: 6mm; }
  * { font-family: DejaVu Sans, Arial, sans-serif; }
  h2 { margin: 0 0 6px 0; }
  .meta { margin: 0 0 10px 0; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: .6px solid #555; padding: 3px 4px; font-size: 10px; line-height: 1.2; }
  th { background: #f3f3f3; }
  thead { display: table-header-group; }
  .right { text-align: right; }
  .center { text-align: center; }
</style>
</head>
<body>
  <h2 class="center">Laporan Penerimaan Pembayaran Siswa</h2>
  <p class="meta">Tahun Ajaran: <strong>{{ $ayLabel }}</strong></p>

  <table>
    {{-- kunci lebar kolom agar muat di A4 landscape --}}
    <colgroup>
      <col style="width:8mm">    {{-- # --}}
      <col style="width:28mm">   {{-- Nama Bulan --}}
      <col style="width:14mm">   {{-- Tahun --}}
      <col style="width:18mm">   {{-- SPP1 --}}
      <col style="width:18mm">   {{-- SPP2 --}}
      <col style="width:18mm">   {{-- SPP3 --}}
      <col style="width:18mm">   {{-- SPP4 --}}
      <col style="width:18mm">   {{-- SPP5 --}}
      <col style="width:18mm">   {{-- SPP6 --}}
      <col style="width:22mm">   {{-- Daftar Ulang --}}
      <col style="width:24mm">   {{-- Pengembangan --}}
      <col style="width:24mm">   {{-- Operasional --}}
      <col style="width:26mm">   {{-- Total --}}
    </colgroup>

    <thead>
      <tr>
        <th class="center">#</th>
        <th>Nama Bulan</th>
        <th class="center">Tahun</th>
        <th class="center">SPP Kelas 1</th>
        <th class="center">SPP Kelas 2</th>
        <th class="center">SPP Kelas 3</th>
        <th class="center">SPP Kelas 4</th>
        <th class="center">SPP Kelas 5</th>
        <th class="center">SPP Kelas 6</th>
        <th class="center">Daftar Ulang</th>
        <th class="center">Biaya Pengembangan</th>
        <th class="center">Biaya Operasional</th>
        <th class="center">Total Penerimaan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rows as $r)
        <tr>
          <td class="center">{{ $r['no'] }}</td>
          <td>{{ $r['bulan'] }}</td>
          <td class="center">{{ $r['tahun'] }}</td>
          <td class="right">{{ $fmt($r['spp1']) }}</td>
          <td class="right">{{ $fmt($r['spp2']) }}</td>
          <td class="right">{{ $fmt($r['spp3']) }}</td>
          <td class="right">{{ $fmt($r['spp4']) }}</td>
          <td class="right">{{ $fmt($r['spp5']) }}</td>
          <td class="right">{{ $fmt($r['spp6']) }}</td>
          <td class="right">{{ $fmt($r['du']) }}</td>
          <td class="right">{{ $fmt($r['bp']) }}</td>
          <td class="right">{{ $fmt($r['bo']) }}</td>
          <td class="right"><strong>{{ $fmt($r['total']) }}</strong></td>
        </tr>
      @endforeach
      <tr>
        <td colspan="12" class="right"><strong>Total Penerimaan</strong></td>
        <td class="right"><strong>{{ $fmt($grandTotal) }}</strong></td>
      </tr>
    </tbody>
  </table>
  <p>*Nilai dalam Rupiah</p>
</body>
</html>
