<x-admin.layout>
<div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Tahun Ajaran</h4>
                    <button class="btn btn-primary mb-3">+ Tambah Tahun Ajaran Baru</button>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
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
                              2021/2022 Semester Ganjil
                            </td>
                            <td class="text-center">
                              <a href="#" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              2
                            </td>
                            <td>
                              2021/2022 Semester Genap
                            </td>
                            <td class="text-center">
                              <a href="#" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              3
                            </td>
                            <td>
                              2022/2023 Semester Ganjil
                            </td>
                            <td class="text-center">
                              <a href="#" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <a class="btn btn-danger btn-sm"><i class="mdi mdi-delete align-middle"></i> Hapus</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              4
                            </td>
                            <td>
                              2022/2023 Semester Genap
                            </td>
                            <td class="text-center">
                              <a href="#" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
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