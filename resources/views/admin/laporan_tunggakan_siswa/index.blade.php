<x-admin.layout>
    <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-light"><i class="mdi mdi-download align-middle"></i> Download Laporan (pdf)</button>
                <button class="btn btn-light mx-3"><i class="mdi mdi-printer align-middle"></i> Cetak Laporan</button>
                <div class="card rounded mt-3">
                  <div class="card-body">
                    <h4 class="card-title">Laporan Tunggakan Siswa</h4>
                    <div class="d-flex gap-3">
                      <div class="d-flex flex-column">
                        <p class="mb-1">Tahun Ajaran:</p>
                        <select class="form-select text-reset">
                          <option disabled selected>Tahun Ajaran</option>
                          <option>2018/2019</option>
                          <option>2019/2020</option>
                          <option>2020/2021</option>
                          <option>2021/2022</option>
                        </select>
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-1">Kelas:</p>
                        <select class="form-select text-reset">
                          <option disabled selected>Kelas</option>
                          <option>1 Abu Bakar</option>
                          <option>1 Umar</option>
                          <option>1 Utsman</option>
                          <option>1 Ali</option>
                        </select>
                      </div>
                    </div>
                    <div class="table-responsive mt-3">
                      <table class="table table-bordered display" id="dataTable">
                        <thead class="text-center">
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">NIK</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Nama Siswa</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Jul</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Ags</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Sep</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Okt</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Nov</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Des</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Jan</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Feb</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Mar</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Apr</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Mei</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Jun</span>
                            </th>
                            <th class="text-center">
                              <span class="fw-bold">Total Tunggakan</span>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-center">
                              1
                            </td>
                            <td>
                              925816
                            </td>
                            <td>
                              Renaldi Siregar
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              11.400.000
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              2
                            </td>
                            <td>
                              925817
                            </td>
                            <td>
                              Annisa Suryawati
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              Lunas
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              950.000
                            </td>
                            <td class="text-end">
                              3.800.000
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              3
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end fw-bold">
                              Total Tunggakan
                            </td>
                            <td class="text-end fw-bold">
                              Rp15.200.000
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