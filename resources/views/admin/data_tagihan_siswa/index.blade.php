<x-admin.layout title="Admin - Data Tagihan Siswa">
<div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-primary mb-3">+ Input Tagihan Siswa Sekaligus</button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card rounded">
                  <div class="card-body">
                    <div class="form-group">
                      <h4 class="card-title">CARI INFORMASI DATA TAGIHAN SISWA</h4>
                      <div class="input-group">
                        <label class="col-form-label me-5">NIS atau Nama</label>
                        <input type="text" class="form-control rounded-start-2" placeholder="Masukkan NIS atau Nama siswa" value="">
                        <div class="input-group-append">
                          <button class="btn btn-sm btn-primary rounded-end-2" type="button">Search</button>
                        </div>
                      </div>
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
                        <td>2021090310136</td>
                      </tr>
                      <tr>
                        <td>Nama</td>
                        <td>Annisa Suryawati</td>
                      </tr>
                      <tr>
                        <td>Kelas</td>
                        <td>4 Umar</td>
                      </tr>
                      <tr>
                        <td>Tahun Ajaran</td>
                        <td>2021/2022</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Data Tagihan</h4>
                    <button class="btn btn-primary mb-3">+ Siswa Ingin Membayar Secara Offline</button>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Bulan
                            </th>
                            <th class="text-center">
                              Jenis Tagihan
                            </th>
                            <th class="text-center">
                              Jumlah
                            </th>
                            <th class="text-center">
                              Sisa
                            </th>
                            <th class="text-center">
                              Jatuh Tempo
                            </th>
                            <th class="text-center">
                              Tanggal Lunas
                            </th>
                            <th class="text-center">
                              Status
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-center">
                              1
                            </td>
                            <td>
                              Desember 2021
                            </td>
                            <td>
                              PPDB
                            </td>
                            <td class="text-end">
                              Rp15.000.000
                            </td>
                            <td class="text-end">
                              Rp10.000.000
                            </td>
                            <td>
                              19 Oktober 2021
                            </td>
                            <td>
                              -
                            </td>
                            <td class="text-center">
                              <label class="badge badge-danger">Belum Lunas</label>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              2
                            </td>
                            <td>
                              Januari 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp950.000
                            </td>
                            <td class="text-end">
                              0
                            </td>
                            <td>
                              1 Januari 2022
                            </td>
                            <td>
                              22 Januari 2022
                            </td>
                            <td class="text-center">
                              <label class="badge badge-success">Lunas</label>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              3
                            </td>
                            <td>
                              Februari 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp950.000
                            </td>
                            <td class="text-end">
                              Rp950.000
                            </td>
                            <td>
                              1 Februari 2022
                            </td>
                            <td>
                              -
                            </td>
                            <td class="text-center">
                              <label class="badge badge-danger">Belum Lunas</label>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              4
                            </td>
                            <td>
                              Maret 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp950.000
                            </td>
                            <td class="text-end">
                              Rp950.000
                            </td>
                            <td>
                              1 Maret 2022
                            </td>
                            <td>
                              -
                            </td>
                            <td class="text-center">
                              <label class="badge badge-danger">Belum Lunas</label>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Bukti Pembayaran -->
           <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Bukti Pembayaran</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <img src="assets/images/bukti_pembayaran/bukti.jpg" class="img-fluid object-fit-cover" alt="Bukti Pembayaran">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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