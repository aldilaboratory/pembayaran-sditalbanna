<x-admin.layout>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Tambah Data Tagihan Per Kelas</h4>
                    <form action="{{ route('admin.data_tagihan_siswa.store') }}" method="POST">
                      @csrf

                      <div class="form-group">
                          <label for="class_id">Kelas</label>
                          <select name="class_id" id="class_id" class="form-select text-black form-control">
                            <option disabled selected>Pilih Kelas</option>
                            @foreach ($studentClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->class }}</option>
                            @endforeach
                          </select>
                          @error('class_id')
                              <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                          <small class="text-muted mt-1 d-block" id="studentCount"></small>
                      </div>
                      <div class="form-group">
                          <label for="jenis_tagihan">Jenis Tagihan</label>
                          <select name="jenis_tagihan" id="jenis_tagihan" class="form-select text-black form-control @error('jenis_tagihan') is-invalid @enderror">
                            <option disabled selected>Pilih Jenis Tagihan</option>
                            <option value="spp">SPP</option>
                            <option value="daftar_ulang">Daftar Ulang</option>
                            <option value="biaya_pengembangan">Biaya Pengembangan</option>
                            <option value="biaya_operasional">Biaya Operasional</option>
                          </select>
                          @error('jenis_tagihan')
                              <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                      </div>
                      <div class="form-group">
                        <label for="jumlah">Tarif Tagihan</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan jumlah tarif" value="{{ old('jumlah') }}" min="0">
                        @error('jumlah')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Format: tanpa titik atau koma</small>
                      </div>
                      <div class="form-group">
                          <label for="bulan">Tagihan Untuk Bulan</label>
                          <select name="bulan" id="bulan" class="form-select text-black form-control">
                            <option disabled selected>Pilih Bulan</option>
                            @php
                              $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                              ];
                            @endphp
                            @foreach ($months as $value => $label)
                              <option value="{{ $value }} {{ old('bulan') == $value ? 'selected' : ''  }}">{{ $label }}</option>
                            @endforeach
                          </select>
                          @error('bulan')
                            <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                      </div>
                      <div class="form-group">
                          <label for="tahun_ajaran">Tagihan Untuk Tahun Ajaran</label>
                          <select name="tahun_ajaran" id="tahun_ajaran" class="form-select text-black form-control">
                            <option disabled selected>Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $academicYear)
                              <option value="{{ $academicYear->id }} {{ old('tahun_ajaran') == $academicYear->academic_year ? 'selected' : ''  }}">{{ $academicYear->academic_year }}</option>
                            @endforeach
                          </select>
                          @error('bulan')
                            <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                      </div>
                      <div class="form-group">
                          <label for="jatuh_tempo">Tanggal Jatuh Tempo</label>
                          <input type="date" 
                                  name="jatuh_tempo" 
                                  id="jatuh_tempo" 
                                  class="form-control"
                                  value="{{ old('jatuh_tempo', date('Y-m-d')) }}"
                                  required>
                          @error('jatuh_tempo')
                              <span class="text-danger small">{{ $message }}</span>
                          @enderror
                      </div>

                      {{-- Preview Section --}}
                      <div class="alert alert-info" id="previewSection" style="display: none;">
                          <h5>Preview Tagihan:</h5>
                          <ul class="mb-0">
                              <li>Kelas: <span id="previewClass">-</span></li>
                              <li>Jenis: <span id="previewType">-</span></li>
                              <li>Periode: <span id="previewPeriod">-</span></li>
                              <li>Jumlah: <span id="previewAmount">-</span></li>
                              <li>Jatuh Tempo: <span id="previewDue">-</span></li>
                              <li>Jumlah Siswa: <span id="previewStudentCount">-</span></li>
                          </ul>
                      </div>
                      <a href="{{ route('admin.data_tagihan_siswa') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
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
    
</x-admin.layout>