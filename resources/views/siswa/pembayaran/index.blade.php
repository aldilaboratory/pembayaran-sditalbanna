<x-siswa.layout title="Riwayat Pembayaran">
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
                            <tr>
                              <td class="text-center">
                                {{ $loop->iteration }}
                              </td>
                              <td>
                                @if ($transaction->status === 'success')
                                  {{ $transaction->paid_at ? \Carbon\Carbon::parse($transaction->paid_at)->format('d/m/Y H:i') : '-' }}
                                @else
                                  {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') : '-' }}
                                @endif
                              </td>
                              <td>
                                {{ $transaction->invoice_code }}
                              </td>
                              <td class="text-end">
                                Rp{{ number_format($transaction->jumlah) }}
                              </td>
                              <td class="text-center">
                                @if ($transaction->status == 'pending')
                                  <span class="badge bg-warning">Pending</span>
                                @elseif ($transaction->status == 'success')
                                  <span class="badge bg-success">Success</span>
                                @elseif ($transaction->status == 'expired')
                                  <span class="badge bg-secondary">Expired</span>
                                @elseif ($transaction->status == 'failed')
                                  <span class="badge bg-danger">Failed</span>
                                @elseif ($transaction->status == 'canceled')
                                  <span class="badge bg-dark">Canceled</span>
                                @else
                                  <span class="badge bg-light text-dark">{{ ucfirst($transaction->status) }}</span>
                                @endif
                              </td>
                              <td class="text-center">
                                @if ($transaction->status === 'success')
                                  {{-- Detail transaksi untuk transaksi success --}}
                                  <a href="{{ route('siswa.transaksi.show', $transaction) }}"
                                    class="btn btn-light text-center btn-sm">
                                    <i class="mdi mdi-history align-middle"></i> Detail Transaksi
                                  </a>
                                @elseif ($transaction->status === 'pending')
                                  {{-- Tombol lanjutkan pembayaran untuk transaksi pending --}}
                                  <a href="{{ route('checkout', $transaction) }}"
                                    class="btn btn-warning text-center btn-sm">
                                    <i class="mdi mdi-clock-outline align-middle"></i> Lanjutkan Pembayaran
                                  </a>
                                @else
                                  {{-- Tampilkan '-' untuk transaksi selain success dan pending --}}
                                  <span class="text-muted">-</span>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</x-siswa.layout>