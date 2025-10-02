<x-admin.layout>
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
                    <h4 class="card-title">Edit Data Siswa</h4>
                    <form action="{{ route('admin.data_siswa.update', $students->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="form-group">
                          <label for="name">Nama Siswa</label>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama siswa" value="{{ old('name', $students->nama) }}">
                      </div>
                      <div class="form-group">
                          <label for="nis">NIS</label>
                          <input
                            type="text"
                            name="nis"
                            id="nis"
                            class="form-control @error('nis') is-invalid @enderror"
                            value="{{ old('nis', $students->nis) }}"
                            placeholder="Masukkan NIS"
                            inputmode="numeric"            {{-- munculkan keypad angka di mobile --}}
                            autocomplete="off"
                            maxlength="5"                 {{-- batasi panjang di UI --}}
                            pattern="[0-9]{5}"         {{-- hanya angka, 5 digit --}}
                            oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5);"  {{-- filter non-digit & potong --}}
                            required
                          >
                          <small class="form-text text-muted">
                              Username dan password login siswa akan otomatis berubah sesuai NIS baru
                          </small>
                        </div>
                      <div class="form-group">
                          <label for="class">Kelas</label>
                          <select name="class" id="class" class="form-select text-black form-control">
                            <option disabled selected>Pilih Kelas</option>
                            @foreach ($studentClasses as $class)
                                <option value="{{ $class->class }}" {{ $students->class_id == $class->id ? 'selected' : '' }}>{{ $class->class }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="school_year">Angkatan</label>
                          <select name="school_year" id="school_year" class="form-select text-black">
                            <option disabled selected>Pilih Angkatan</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->school_year }}" {{ $students->school_year_id == $schoolYear->id ? 'selected' : '' }}>{{ $schoolYear->school_year }}</option>
                            @endforeach
                          </select>
                      </div>
                      {{-- <div class="form-group">
                          <label for="academic_year">Tahun Ajaran</label>
                          <select name="academic_year" id="academic_year" class="form-select text-black">
                            <option disabled selected>Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->academic_year }}" {{ $students->academic_year_id == $academicYear->id ? 'selected' : '' }}>{{ $academicYear->academic_year }}</option>
                            @endforeach
                          </select>
                      </div> --}}
                      <div class="form-group">
                          <label for="status">Status Siswa</label>
                          <select name="status" id="status" class="form-select text-black" {{ old('status', $students->nama) }}>
                            <option disabled selected>Pilih Status Siswa</option>
                            <option value="aktif" @selected($students->status === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($students->status === 'nonaktif')>Nonaktif</option>
                          </select>
                      </div>
                      {{-- Info Alert --}}
                        <div class="alert alert-info">
                            <strong>Informasi:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Akun login siswa akan otomatis dibuat dengan username = NIS</li>
                                <li>Password default = "siswa" + NIS</li>
                                <li>Data pribadi lainnya dapat dilengkapi kemudian</li>
                                <br>
                                <li>Contoh kredensial login</li>
                                <li>Username: 2021090310136</li>
                                <li>Password: siswa310136</li>
                            </ul>
                        </div>
                      <a href="{{ route('admin.data_siswa') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>