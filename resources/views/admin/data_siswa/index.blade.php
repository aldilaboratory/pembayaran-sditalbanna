<x-admin.layout title="Admin - Data Siswa">
    <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <button class="btn btn-primary mb-3">+ Tambah Data</button>
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Data Siswa</h4>
                    <div class="table-responsive mt-3">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              NIS
                            </th>
                            <th class="text-center">
                              Nama Siswa
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
                            </th>
                            <th class="text-center">
                              Kelas
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-center">2021090310136</td>
                            <td>Annisa Suryawati</td>
                            <td class="text-center">2021/2022</td>
                            <td>4 Umar</td>
                            <td class="text-center">
                              <a href="admin-detail-data-siswa.html" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">2021090310111</td>
                            <td>Bagus Surya</td>
                            <td class="text-center">2021/2022</td>
                            <td>4 Umar</td>
                            <td class="text-center">
                              <a href="admin-detail-data-siswa.html" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">2021090310112</td>
                            <td>Jakky Ali</td>
                            <td class="text-center">2021/2022</td>
                            <td>4 Ali</td>
                            <td class="text-center">
                              <a href="admin-detail-data-siswa.html" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">2021090310113</td>
                            <td>Desta Mahendra</td>
                            <td class="text-center">2022/2023</td>
                            <td>4 Umar</td>
                            <td class="text-center">
                              <a href="admin-detail-data-siswa.html" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
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
</x-admin.layout>