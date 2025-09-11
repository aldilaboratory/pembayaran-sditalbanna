<x-siswa.layout title="Tagihan Siswa">
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Daftar Tagihan</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Bulan
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
                            </th>
                            <th class="text-center">
                              Jenis Tagihan
                            </th>
                            <th class="text-center">
                              Jumlah
                            </th>
                            <th class="text-center">
                              Jatuh Tempo
                            </th>
                            <th class="text-center">
                              Tanggal Lunas
                            </th>
                            <th class="text-center">
                              Status
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($tagihanSiswa as $tagihan)
                          <tr>
                            <td class="text-center">
                              {{ $loop->iteration }}
                            </td>
                            <td>
                              {{ $tagihan->nama_bulan }}
                            </td>
                            <td class="text-center">
                              {{ $tagihan->academicYear->academic_year }}
                            </td>
                            <td>
                              {{ $tagihan->jenis_tagihan_label }}
                            </td>
                            <td class="text-end">
                              Rp{{ number_format($tagihan->jumlah) }}
                            </td>
                            <td class="text-end">
                              {{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d F Y') : '-' }}
                            </td>
                            <td class="text-end">
                              {{ $tagihan->tanggal_lunas ? \Carbon\Carbon::parse($tagihan->tanggal_lunas)->format('d F Y') : '-' }}
                            </td>
                            @if ($tagihan->status === 'lunas')
                              {{-- Kolom STATUS --}}
                              <td class="text-center">
                                <label class="badge badge-success">Lunas</label>
                              </td>

                              {{-- Kolom AKSI --}}
                              <td class="text-center">
                                @php
                                  $successTx = \App\Models\Transaction::where('school_fee_id', $tagihan->id)
                                      ->where('student_id', $student->id)
                                      ->where('status', 'success')
                                      ->latest('paid_at')
                                      ->first();
                                @endphp

                                @if ($successTx)
                                  <a href="{{ route('siswa.transaksi.show', $successTx->id) }}"
                                    class="btn btn-light text-center btn-sm">
                                    <i class="mdi mdi-history align-middle"></i> Detail Transaksi
                                  </a>
                                @else
                                  <span class="text-muted">-</span>
                                @endif
                              </td>

                            @elseif ($tagihan->status === 'belum_lunas')
                              {{-- Kolom STATUS --}}
                              <td class="text-center">
                                @php
                                  $pendingTransaction = \App\Models\Transaction::where('school_fee_id', $tagihan->id)
                                      ->where('student_id', $student->id)
                                      ->where('status', 'pending')
                                      ->first();
                                @endphp

                                @if ($pendingTransaction)
                                  <label class="badge badge-warning">Menunggu Pembayaran</label>
                                @else
                                  <label class="badge badge-danger">Belum Lunas</label>
                                @endif
                              </td>

                              {{-- Kolom AKSI --}}
                              <td class="text-center">
                                @if ($pendingTransaction)
                                  <a href="{{ route('checkout', $pendingTransaction->id) }}" 
                                    class="btn btn-warning text-center btn-sm">
                                    <i class="mdi mdi-clock-outline align-middle"></i> Lanjutkan Pembayaran
                                  </a>
                                @else
                                  <form action="{{ route('checkout-process', $tagihan->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info text-center btn-sm">
                                      <i class="mdi mdi-cash align-middle"></i> Bayar
                                    </button>
                                  </form>
                                @endif
                              </td>
                            @endif
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div
              class="d-sm-flex justify-content-center justify-content-sm-between"
            >
              <span
                class="text-muted text-center text-sm-left d-block d-sm-inline-block"
                >Copyright © 2024
                <a href="https://www.bootstrapdash.com/" target="_blank"
                  >Bootstrapdash</a
                >. All rights reserved.</span
              >
              <span
                class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center"
                >Hand-crafted & made with
                <i class="mdi mdi-heart text-danger"></i
              ></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
</x-siswa.layout>