<x-siswa.layout title="Dashboard Siswa">
        {{-- Konten ini akan masuk ke slot layout.blade.php --}}
        {{-- <x-slot name="title">Dashboard Siswa</x-slot> --}}
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
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