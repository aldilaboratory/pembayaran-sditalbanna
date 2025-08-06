<x-siswa.layout title="Tagihan Siswa">
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Daftar Tagihan</h4>
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
                              Jatuh Tempo
                            </th>
                            <th class="text-center">
                              Tanggal Lunas
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
                          <tr>
                            <td class="text-center">
                              1
                            </td>
                            <td>
                              Januari 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp3.000.000
                            </td>
                            <td>
                              19 Oktober 2021
                            </td>
                            <td>
                              22 Januari 2022
                            </td>
                            <td class="text-center">
                              <label class="badge badge-success">Lunas</label>
                            </td>
                            <td class="text-center">
                              <a href="detail-transaksi.html" class="btn btn-light text-center btn-sm"><i class="mdi mdi-history align-middle"></i> Lihat Detail Transaksi</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              2
                            </td>
                            <td>
                              Februari 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp3.000.000
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
                            <td class="text-center">
                              <a href="tagihan-checkout.html" class="btn btn-info btn-sm"><i class="mdi mdi-cash align-middle"></i> Bayar</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              3
                            </td>
                            <td>
                              Maret 2022
                            </td>
                            <td>
                              SPP
                            </td>
                            <td class="text-end">
                              Rp3.000.000
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
                            <td class="text-center">
                              <a href="tagihan-checkout.html" class="btn btn-info btn-sm"><i class="mdi mdi-cash align-middle"></i> Bayar</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">4</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end">
                              <p class="fs-5">Total Tagihan Belum Lunas: <span class="fw-bold fs-4">Rp6.000.000</span></p>
                            </td>
                            <td class="text-center">
                              <a href="tagihan-checkout.html" class="btn btn-info btn-sm"><i class="mdi mdi-cash align-middle"></i> Bayar Sekaligus</a>
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