<x-admin.layout title="Profil Siswa">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Profil Siswa</h4>
                    <form class="form-sample" action="{{ route('admin.profil_siswa.update', $student) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <p class="card-description">Personal info</p>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                              <input type="text" name="nama" class="form-control" value="{{ old('nama', $student->nama) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9">
                              <input type="number" name="nik" class="form-control" value="{{ old('nik', $student->nik) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Tempat Lahir</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $student->tempat_lahir) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Jenis Kelamin</label
                            >
                            <div class="col-sm-9">
                              <select class="js-example-basic-single form-select text-black" name="jenis_kelamin">
                                <option disabled selected>Pilih Jenis Kelamin</option>
                                <option value="L" @selected($student->jenis_kelamin === 'L')>Laki-laki</option>
                                <option value="P" @selected($student->jenis_kelamin === 'P')>Perempuan</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Tanggal Lahir</label
                            >
                            <div class="col-sm-9">
                              <input
                                class="form-control"
                                placeholder="dd/mm/yyyy"
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $student->tanggal_lahir) }}"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Agama</label>
                            <div class="col-sm-9">
                              <select class="js-example-basic-single form-select text-black" name="agama">
                                <option disabled selected>Pilih Agama</option>
                                <option value="islam" @selected($student->agama === 'islam')>Islam</option>
                                <option value="hindu" @selected($student->agama === 'hindu')>Hindu</option>
                                <option value="kristen" @selected($student->agama === 'kristen')>Kristen</option>
                                <option value="katolik" @selected($student->agama === 'katolik')>Katolik</option>
                                <option value="buddha" @selected($student->agama === 'buddha')>Buddha</option>
                                <option value="konghucu" @selected($student->agama === 'konghucu')>Konghucu</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >NIS</label
                            >
                            <div class="col-sm-9">
                              <input type="number" name="nis" class="form-control" value="{{ old('nis', $student->nis) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Alamat</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $student->alamat) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jenis Tinggal</label>
                            <div class="col-sm-9">
                              <select class="js-example-basic-single form-select text-black" name="tinggal_dengan">
                                <option disabled selected>Pilih Jenis Tinggal</option>
                                <option value="dengan orang tua" @selected($student->tinggal_dengan === 'dengan orang tua')>Dengan Orang Tua</option>
                                <option value="dengan wali" @selected($student->tinggal_dengan === 'dengan wali')>Dengan Wali</option>
                                <optio nvalue="sendiri" @selected($student->tinggal_dengan === 'sendiri')>Sendiri</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Kewarganegaraan</label
                            >
                            <div class="col-sm-9">
                              <select class="js-example-basic-single form-select text-black" name="kewarganegaraan">
                                <option disabled selected>Pilih Kewarganegaraan</option>
                                <option value="wni" @selected($student->kewarganegaraan === 'wni')>WNI</option>
                                <option value="wna" @selected($student->kewarganegaraan === 'wna')>WNA</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Nama Ibu Kandung</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="nama_ibu_kandung" class="form-control" value="{{ old('nama_ibu_kandung', $student->nama_ibu_kandung) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Nama Ayah Kandung</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="nama_ayah_kandung" class="form-control" value="{{ old('nama_ayah_kandung', $student->nama_ayah_kandung) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Nama Wali</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $student->nama_wali) }}" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                              >Nomor Whatsapp Orang Tua/Wali</label
                            >
                            <div class="col-sm-9">
                              <input type="text" name="nomor_whatsapp_orang_tua_wali" class="form-control" value="{{ old('nomor_whatsapp_orang_tua_wali', $student->nomor_whatsapp_orang_tua_wali) }}" />
                              <small class="text-muted">Contoh pengetikkan nomor telpon: 082362846322</small>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mx-3 btn-sm"><i class="mdi mdi-content-save align-middle"></i> Simpan</button>
                            <a href="{{ route('admin.data_siswa') }}" class="btn btn-light btn-sm"><i class="mdi mdi-close align-middle"></i> Batal</a>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>