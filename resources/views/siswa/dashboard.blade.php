<x-siswa.layout title="Dashboard Siswa">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                  <div class="d-flex align-items-end flex-wrap">
                    <div class="me-md-3 me-xl-5">
                      <h2>Selamat Datang, Ayah/Bunda <span class="fw-bold">{{ Auth::user()->name }}</span></h2>
                      <p class="mb-md-0">Informasi Umum Peserta Didik</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Biodata Siswa</h4>
                    <table class="table table-bordered">
                      <tr>
                        <td>NIS</td>
                        <td>{{ $user->student->nis }}</td>
                      </tr>
                      <tr>
                        <td>Nama</td>
                        <td>{{ $user->name }}</td>
                      </tr>
                      <tr>
                        <td>Kelas</td>
                        <td>{{ $user->student->studentClass->class }}</td>
                      </tr>
                      <tr>
                        <td>Tahun Ajaran</td>
                        <td>{{ $user->student->academicYear->academic_year }}</td>
                      </tr>
                      <tr>
                        <td>Angkatan</td>
                        <td>{{ $user->student->schoolYear->school_year }}</td>
                      </tr>
                      <tr>
                        <td>Status</td>
                        <td>Aktif</td>
                      </tr>
                      <tr>
                        <td>Tagihan</td>
                        <td><span class="fw-bold">Rp6.000.000</span></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
</x-siswa.layout>