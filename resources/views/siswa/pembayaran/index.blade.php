<x-siswa.layout title="Riwayat Pembayaran">
<!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Riwayat Pembayaran</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Tanggal Pembayaran
                            </th>
                            <th class="text-center">
                              Kode Pembayaran
                            </th>
                            <th class="text-center">
                              Total
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
                              22 Januari 2022
                            </td>
                            <td>
                              ABN-325
                            </td>
                            <td class="text-end">
                              Rp3.000.000
                            </td>
                            <td class="text-center">
                              <label class="badge badge-success">Pembayaran Berhasil</label>
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
                              22 Februari 2022
                            </td>
                            <td>
                              ABN-324
                            </td>
                            <td class="text-end">
                              Rp3.000.000
                            </td>
                            <td class="text-center">
                              <label class="badge badge-danger">Pembayaran Gagal</label>
                            </td>
                            <td class="text-center">
                              -
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              3
                            </td>
                            <td>
                              22 Maret 2022
                            </td>
                            <td>
                              ABN-323
                            </td>
                            <td class="text-end">
                              Rp3.000.000
                            </td>
                            <td class="text-center">
                              <label class="badge badge-danger">Pembayaran Gagal</label>
                            </td>
                            <td class="text-center">
                              -
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