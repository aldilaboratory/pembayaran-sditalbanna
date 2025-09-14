<x-kepala_sekolah.layout>
            <div class="row">
              <div class="col-md-12">
                <a class="btn btn-light border-primary" href="{{ route('kepala_sekolah.laporan_penerimaan.pdf', request()->query()) }}"><i class="mdi mdi-download align-middle"></i> Download Laporan (pdf)</a>
                <div class="card rounded mt-3">
                  <div class="card-body">
                    <h4 class="card-title">Laporan Penerimaan Pembayaran Siswa</h4>
                    <div class="d-flex gap-3">
                      {{-- Filter Tahun Ajaran --}}
                      <form method="GET" action="{{ route('kepala_sekolah.laporan_penerimaan') }}" class="d-flex gap-3">
                        <div class="d-flex flex-column">
                          <p class="mb-1">Tahun Ajaran:</p>
                          <select name="academic_year_id" class="form-select text-reset" onchange="this.form.submit()">
                            <option disabled selected>Tahun Ajaran</option>
                            @foreach ($academicYears as $ay)
                              <option value="{{ $ay->id }}" @selected($ay->id == $academicYearId)>{{ $ay->academic_year }}</option>
                            @endforeach
                          </select>
                        </div>
                      </form>
                    </div>

                    @php
                      $fmt = fn($v) => 'Rp'.number_format((int)$v, 0, ',', '.');
                    @endphp

                    <div class="table-responsive mt-3">
                      <table class="table table-bordered">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Nama Bulan</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Tahun</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 1</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 2</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 3</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 4</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 5</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">SPP Kelas 6</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Daftar Ulang</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Biaya Pengembangan</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Biaya Operasional</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Total Penerimaan</span>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse ($rows as $r)
                          <tr>
                            <td class="text-center">
                              {{ $loop->iteration }}
                            </td>
                            <td>
                              {{ $r['bulan'] }}
                            </td>
                            <td class="text-end">
                              {{ $r['tahun'] }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp1']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp2']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp3']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp4']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp5']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['spp6']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['du']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['bp']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['bo']) }}
                            </td>
                            <td class="text-end">
                              {{ $fmt($r['total']) }}
                            </td>
                          </tr>
                          @empty
                            <tr><td colspan="13" class="text-center text-muted">Tidak ada data</td></tr>
                          @endforelse
                          @if (!empty($rows))
                            <tr>
                              <td colspan="12" class="text-end fw-bold">Total Penerimaan</td>
                              <td class="text-end fw-bold">{{ $fmt($grandTotal) }}</td>
                            </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</x-kepala_sekolah.layout>