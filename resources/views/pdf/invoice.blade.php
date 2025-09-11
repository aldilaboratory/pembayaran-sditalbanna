@php
  \Carbon\Carbon::setLocale('id');
  $paidAt = optional($transaction->paid_at)
      ? \Carbon\Carbon::parse($transaction->paid_at)->timezone('Asia/Makassar')->translatedFormat('d F Y H:i')
      : '-';

  $logoPath = public_path('images/albanna.png'); // gunakan path lokal agar aman di DomPDF
  $kelas = $transaction->schoolFee?->student?->studentClass?->class ?? '-';
  $tahunAjar = $transaction->schoolFee?->academicYear?->academic_year ?? '-';
  $jenis = strtoupper($transaction->schoolFee?->jenis_tagihan ?? '-');
  $bulan = $transaction->schoolFee?->nama_bulan ?? '-';
  $jumlah = 'Rp'.number_format($transaction->jumlah ?? $transaction->schoolFee?->jumlah ?? 0, 0, ',', '.');
  $statusLabel = $transaction->status === 'success' ? 'Lunas' : ucfirst($transaction->status ?? '-');
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Invoice {{ ('INV-'.$transaction->id) }}</title>
  <style>
    @page { margin: 20mm 15mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    .center { text-align: center; }
    .mb-0 { margin-bottom: 0; } .mb-1 { margin-bottom: 4px; } .mb-2 { margin-bottom: 8px; } .mb-3 { margin-bottom: 12px; } .mb-4 { margin-bottom: 16px; }
    .fs-5 { font-size: 14px; } .fw-bold { font-weight: bold; }
    .hr { height:1px; background:#ccc; border:0; margin: 12px 0; }
    .header { text-align: center; }
    .header img { width: 240px; margin-bottom: 8px; }
    .table { width:100%; border-collapse: collapse; table-layout: fixed; }
    .table td { vertical-align: top; padding: 6px 8px; }
    .table th { text-align:left; padding: 6px 8px; }
    .table.bordered th, .table.bordered td { border: 1px solid #ddd; }
    .w-50 { width: 50%; }
    .title { font-size: 16px; font-weight: bold; margin: 10px 0 6px; }
    .muted { color:#666; }
    .label { display:inline-block; padding:2px 6px; border-radius:3px; background:#28a745; color:#fff; font-size: 11px; }
    .label.warn { background:#f0ad4e; }
    .text-right { text-align: right; }
  </style>
</head>
<body>
  <div class="header">
    @if(!empty($logoBase64))
    <img src="{{ $logoBase64 }}" alt="Logo Albanna">
  @endif

    <p class="mb-2 fs-5"><strong>SEKOLAH DASAR ISLAM TERPADU ALBANNA</strong></p>
    <p class="mb-0">Jalan Tukad Yeh Ho III/16 Denpasar, Dangin Puri Klod, Kec. Denpasar Timur, Kota Denpasar Prov. Bali</p>
    <p class="mb-0">Phone: 088 999 444 | Email: info@albanna.sch.id</p>
  </div>

  <div class="hr"></div>

  <div class="title">Invoice #{{ $transaction->invoice_code }}</div>

  <table class="table">
    <tr>
      <td class="w-50">
        <div class="mb-0"><strong>Nama Siswa</strong></div>
        <div class="mb-2">{{ $transaction->student->nama }}</div>

        <div class="mb-0"><strong>Kelas</strong></div>
        <div class="mb-2">{{ $kelas }}</div>

        <div class="mb-0"><strong>Tahun Ajaran</strong></div>
        <div class="mb-2">{{ $tahunAjar }}</div>

        <div class="mb-0"><strong>Tanggal Lunas</strong></div>
        <div>{{ $paidAt }}</div>
      </td>
      <td class="w-50">
        <div class="mb-0"><strong>Jenis Tagihan</strong></div>
        <div class="mb-2">{{ $jenis }}</div>

        <div class="mb-0"><strong>Bulan</strong></div>
        <div class="mb-2">{{ $bulan }}</div>

        <div class="mb-0"><strong>Status</strong></div>
        <div class="mb-2">
          @if($transaction->status === 'success')
            <span>Lunas</span>
          @else
            <span>{{ $statusLabel }}</span>
          @endif
        </div>

        <div class="mb-0"><strong>Jumlah</strong></div>
        <div>{{ $jumlah }}</div>
      </td>
    </tr>
  </table>

  <div class="hr"></div>
  <p class="muted mb-0">Invoice ini dihasilkan otomatis oleh sistem.</p>
</body>
</html>
