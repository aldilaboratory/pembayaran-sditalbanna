{{-- resources/views/siswa/transaksi/show.blade.php --}}
<x-siswa.layout title="Detail Transaksi">
  <div class="main-panel">
    <div class="content-wrapper">
      <a class="btn btn-light border-primary mb-3" href="{{ route('siswa.transaksi.pdf', $transaction) }}"><i class="mdi mdi-download align-middle"></i> Download Invoice (pdf)</a>
      <div class="card rounded">
        <div class="card-body">
          <div class="row">
            <div class="col-12 text-center">
              <img src="{{ asset('images/albanna.png') }}" alt="Logo Albanna" class="w-25 mb-3">

              <p class="mb-2 fs-5"><strong>SEKOLAH DASAR ISLAM TERPADU ALBANNA</strong></p>
              <p class="mb-0">Jalan Tukad Yeh Ho III/16 Denpasar, Dangin Puri Klod, Kec. Denpasar Timur, Kota Denpasar Prov. Bali</p>
              <p class="mb-0">Phone: 088 999 444 | Email: info@albanna.sch.id</p>
            </div>
          </div>

          <hr>
          
          <h4 class="card-title">Invoice #{{ $transaction->invoice_code }}</h4>

          <div class="row">
            <div class="col-md-6">
              <p class="mb-0"><strong>Nama Siswa</strong></p>
              <p>{{ $transaction->student->nama }}</p>

              <p class="mb-0"><strong>Kelas</strong></p>
              <p>{{ $transaction->schoolFee?->student?->studentClass?->class ?? '-' }}</p>

              <p class="mb-0"><strong>Tahun Ajaran</strong></p>
              <p>{{ $transaction->schoolFee?->academicYear?->academic_year ?? '-' }}</p>

              <p class="mb-0"><strong>Tanggal Lunas</strong></p>
              <p>{{ optional($transaction->paid_at)->timezone('Asia/Makassar')->format('d F Y H:i') ?? '-' }}</p>
            </div>
            <div class="col-md-6">
              <p class="mb-0"><strong>Jenis Tagihan</strong></p>
              <p>{{ strtoupper($transaction->schoolFee?->jenis_tagihan ?? '-') }}</p>

              <p class="mb-0"><strong>Bulan</strong></p>
              <p>{{ $transaction->schoolFee?->nama_bulan ?? '-' }}</p>

              <p class="mb-0"><strong>Status</strong></p>
              <p>
                @if($transaction->status === 'success')
                  <span>Lunas</span>
                @else
                  <span class="badge badge-warning">{{ ucfirst($transaction->status) }}</span>
                @endif
              </p>

              <p class="mb-1"><strong>Jumlah</strong></p>
              <p>Rp{{ number_format($transaction->jumlah ?? $transaction->schoolFee?->jumlah ?? 0, 0, ',', '.') }}</p>

              
            </div>
          </div>

          <hr>
          <p class="text-muted mb-0">Invoice ini dihasilkan otomatis oleh sistem.</p>
        </div>
      </div>
    </div>
  </div>
</x-siswa.layout>
