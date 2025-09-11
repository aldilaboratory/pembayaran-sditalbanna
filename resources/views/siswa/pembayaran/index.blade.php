<x-siswa.layout title="Riwayat Pembayaran">
<!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Riwayat Pembayaran</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Tanggal Pembayaran
                            </th>
                            <th class="text-center">
                              Kode Pembayaran
                            </th>
                            <th class="text-center">
                              Total
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
                          @foreach ($transactions as $transaction)
                            @if ($transaction->status === 'success')
                              <tr>
                                <td class="text-center">
                                  {{ $loop->iteration }}
                                </td>
                                <td>
                                  {{ $transaction->paid_at }}
                                </td>
                                <td>
                                  {{ $transaction->invoice_code }}
                                </td>
                                <td class="text-end">
                                  Rp{{ number_format($transaction->jumlah) }}
                                </td>
                                <td class="text-center">
                                  @if ($transaction['status'] == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                  @elseif ($transaction['status'] == 'success')
                                    <span class="badge bg-success">Success</span>
                                  @elseif ($transaction['status'] == 'expired')
                                    <span class="badge bg-secondary">Expired</span>
                                  @elseif ($transaction['status'] == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                  @elseif ($transaction['status'] == 'canceled')
                                    <span class="badge bg-dark">Canceled</span>
                                  @endif
                                </td>
                                <td class="text-center">
                                  {{-- Detail transaksi (selalu bisa diklik, authorization dijaga di controller show) --}}
                                  <a href="{{ route('siswa.transaksi.show', $transaction) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-history align-middle"></i> Detail Transaksi
                                  </a>
                                </td>
                              </tr>
                            @endif
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