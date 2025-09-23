<x-super_admin.layout>
            <div class="row">
              <div class="col-md-12">
                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Tambah Data Siswa</h4>
                    <form action="{{ route('super_admin.data_siswa.store') }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="name">Nama Siswa</label>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama siswa" value="{{ old('name') }}">
                      </div>
                      <div class="form-group">
                          <label for="nis">NIS</label>
                          <input type="number" class="form-control" name="nis" id="nis" placeholder="Masukkan NIS" value="{{ old('nis') }}">
                      </div>
                      <div class="form-group">
                          <label for="class">Kelas</label>
                          <select name="class" id="class" class="form-select text-black form-control">
                            <option disabled selected>Pilih Kelas</option>
                            @foreach ($studentClasses as $class)
                                <option value="{{ $class->class }}">{{ $class->class }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="school_year">Angkatan</label>
                          <select name="school_year" id="school_year" class="form-select text-black">
                            <option disabled selected>Pilih Angkatan</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->school_year }}">{{ $schoolYear->school_year }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="academic_year">Tahun Ajaran</label>
                          <select name="academic_year" id="academic_year" class="form-select text-black">
                            <option disabled selected>Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->academic_year }}">{{ $academicYear->academic_year }}</option>
                            @endforeach
                          </select>
                      </div>
                      {{-- Info Alert --}}
                        <div class="alert alert-info">
                            <strong>Informasi:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Akun login siswa akan otomatis dibuat dengan username = NIS</li>
                                <li>Password default = "siswa" + 6 digit terakhir NIS</li>
                                <li>Data pribadi lainnya dapat dilengkapi kemudian</li>
                                <br>
                                <li>Contoh kredensial login</li>
                                <li>Username: 2021090310136</li>
                                <li>Password: siswa310136</li>
                            </ul>
                        </div>
                      <a href="{{ route('super_admin.data_siswa') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>