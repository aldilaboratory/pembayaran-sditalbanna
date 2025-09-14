<x-admin.layout>
            <div class="row">
              <div class="col-md-12">
                <a class="btn btn-light border-primary" href="{{ route('admin.laporan_tunggakan_siswa.pdf', request()->query()) }}"><i class="mdi mdi-download align-middle"></i> Download Laporan (pdf)</a>
                <div class="card rounded mt-3">
                  <div class="card-body">
                    <h4 class="card-title">Laporan Tunggakan Siswa</h4>
                    <form method="GET" action="{{ route('admin.laporan_tunggakan_siswa') }}" class="d-flex gap-3">
                    <div class="d-flex flex-column">
                      <p class="mb-1">Tahun Ajaran:</p>
                      <select name="academic_year_id" class="form-select text-reset" onchange="this.form.submit()">
                        <option disabled {{ empty($academicYearId) ? 'selected' : '' }}>Tahun Ajaran</option>
                        @foreach ($academicYears as $ay)
                          <option value="{{ $ay->id }}" @selected($ay->id == $academicYearId)>{{ $ay->academic_year }}</option>
                        @endforeach
                      </select>
                    </div>

                      <div class="d-flex flex-column">
                        <p class="mb-1">Kelas:</p>
                        <select name="class_id" class="form-select text-reset" onchange="this.form.submit()">
                          <option value="" @selected(empty($classId))>Kelas</option>
                          @foreach ($classes as $c)
                            <option value="{{ $c->id }}" @selected($c->id == $classId)>{{ $c->class }}</option>
                          @endforeach
                        </select>
                      </div>
                    </form>
                    <div class="table-responsive mt-3">
                        @php
                          $fmt = fn($v) => is_numeric($v) ? 'Rp'.number_format($v, 0, ',', '.') : $v;
                        @endphp
                      <table class="table table-bordered display">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">NIK</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Nama Siswa</span>
                            </th>
                            @foreach ($months as $m)
                              <th><span class="fw-bold">{{ $monthLabels[$m] }}</span></th>
                            @endforeach
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
                              <span class="fw-bold">Total Tunggakan</span>
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
                              {{ $r['nik'] }}
                            </td>
                            <td>
                              {{ $r['nama'] }}
                            </td>
                            @foreach ($months as $m)
                              <td class="text-end">{{ $fmt($r['m_'.$m] ?? '-') }}</td>
                            @endforeach
                            <td class="text-end">{{ $fmt($r['du']) }}</td>
                            <td class="text-end">{{ $fmt($r['bp']) }}</td>
                            <td class="text-end">{{ $fmt($r['bo']) }}</td>
                            <td class="text-end">
                              {{ $fmt($r['total']) }}
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="{{ 3 + count($months) + 4 }}" class="text-center text-muted">Tidak ada data.</td>
                          </tr>
                          @endforelse
                          @if (!empty($rows))
                            <tr>
                              <td colspan="18" class="text-end fw-bold">
                                Total Tunggakan
                              </td>
                              <td class="text-end fw-bold">
                                {{ $fmt($grandTotal) }}
                              </td>
                            </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>