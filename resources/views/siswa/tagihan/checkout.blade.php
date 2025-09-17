<x-siswa.layout title="Checkout Pembayaran">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0"><i class="mdi mdi-credit-card"></i> Pembayaran Tagihan</h4>
            </div>
            <div class="card-body">
              <!-- Detail Siswa -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <h6 class="text-muted">Nama Siswa</h6>
                  <p class="fw-bold">{{ $student->nama }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Kelas</h6>
                  <p class="fw-bold">{{ $student->studentClass->class ?? '-' }}</p>
                </div>
              </div>

              <hr>

              <!-- Detail Tagihan -->
              <div class="row mb-4">
                <div class="col-12">
                  <h5 class="mb-3">Detail Tagihan</h5>
                  <div class="table-responsive">
                    <table class="table table-borderless">
                      <tr>
                        <td><strong>Jenis Tagihan</strong></td>
                        <td>: {{ $schoolFee->jenis_tagihan_label }}</td>
                      </tr>
                      <tr>
                        <td><strong>Periode</strong></td>
                        <td>: {{ $schoolFee->nama_bulan }}</td>
                      </tr>
                      <tr>
                        <td><strong>Jatuh Tempo</strong></td>
                        <td>: {{ \Carbon\Carbon::parse($schoolFee->jatuh_tempo)->format('d F Y') }}</td>
                      </tr>
                      <tr>
                        <td><strong>Jumlah Tagihan</strong></td>
                        <td>: <span class="h5 text-primary">Rp{{ number_format($schoolFee->jumlah) }}</span></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>

              <hr>

              <!-- Status Pembayaran -->
              <div class="row mb-4">
                <div class="col-12 text-center">
                  <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Status:</strong> Menunggu Pembayaran
                  </div>
                </div>
              </div>

              <!-- Tombol Bayar -->
              <div class="row">
                <div class="col-12 text-center">
                  <button id="pay-button" class="btn btn-success btn-lg px-5">
                    <i class="mdi mdi-credit-card"></i> Bayar Sekarang
                  </button>
                  <br><br>
                  <a href="{{ route('siswa.tagihan.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar Tagihan
                  </a>
                </div>
              </div>

              <!-- Debug Info (Remove in production) -->
              {{-- <div class="row mb-4">
                <div class="col-12">
                  <div class="alert alert-secondary">
                    <h6>Debug Info:</h6>
                    <small>
                      <strong>Transaction ID:</strong> {{ $transaction->id }}<br>
                      <strong>Snap Token:</strong> {{ $transaction->snap_token ? 'Available' : 'Not Available' }}<br>
                      <strong>Client Key:</strong> {{ config('midtrans.clientKey') ?: 'NOT FOUND' }}<br>
                      <strong>Server Key:</strong> {{ config('midtrans.serverKey') ? 'Available' : 'NOT FOUND' }}
                    </small>
                  </div>
                </div> --}}
              

              <!-- Info Metode Pembayaran -->
              <div class="row mt-4">
                <div class="col-12">
                  <div class="alert alert-light">
                    <h6><i class="mdi mdi-information-outline"></i> Metode Pembayaran Tersedia:</h6>
                    <ul class="mb-0">
                      <li>Transfer Bank (BCA, BNI, BRI, Mandiri, Permata)</li>
                      <li>Virtual Account</li>
                      <li>QRIS</li>
                      <li>E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
                      <li>Kartu Kredit/Debit</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  <script
    src="{{ config('midtrans.isProduction')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('midtrans.clientKey') }}"></script>
  <script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // Show loading
        document.getElementById('pay-button').innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';
        document.getElementById('pay-button').disabled = true;

        snap.pay('{{ $transaction->snap_token }}', {
            onSuccess: function(result){
                console.log('Payment Success:', result);
                window.location.href = "{{ route('siswa.tagihan.index') }}";
            },
            onPending: function(result){
                console.log('Payment Pending:', result);
                window.location.href = "{{ route('siswa.tagihan.index') }}";
            },
            onError: function(result){
                console.log('Payment Error:', result);
                alert("Pembayaran gagal. Silakan coba lagi.");
                // Reset button
                document.getElementById('pay-button').innerHTML = '<i class="mdi mdi-credit-card"></i> Bayar Sekarang';
                document.getElementById('pay-button').disabled = false;
            },
        });
    };
  </script>

</x-siswa.layout>