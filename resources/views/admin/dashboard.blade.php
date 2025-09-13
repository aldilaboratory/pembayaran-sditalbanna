<x-admin.layout title="Dashboard Admin">
<!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <h2>Dashboard Admin,  <span class="fw-bold">{{ Auth::user()->name }}</span></h2>
              </div>
            </div>
            @php
              $fmt = fn($v) => 'Rp'.number_format((int)$v, 0, ',', '.');
            @endphp
            <div class="row">
              <div class="col-4 grid-margin stretch-card">
                <div class="card border-primary rounded">
                  <div class="card-body">
                    <p class="card-text">Jumlah Siswa</p>
                    <h3 class="card-text text-primary">{{ $students }}</h3>
                  </div>
                </div>
              </div>
              <div class="col-4 grid-margin stretch-card">
                <div class="card border-success rounded">
                  <div class="card-body">
                    <p class="card-text mb-0">Jumlah Pembayaran Lunas</p>
                    <p class="card-text">Tahun Ajaran: {{ $latestAcademicYear }}</p>
                    <h5 class="card-text text-success">{{ $fmt($penerimaanTotal) }}</h5>
                  </div>
                </div>
              </div>
              <div class="col-4 grid-margin stretch-card">
                <div class="card border-danger rounded">
                  <div class="card-body">
                    <p class="card-text mb-0">Jumlah Tunggakan Siswa</p>
                    <p class="card-text">Tahun Ajaran: {{ $latestAcademicYear }}</p>
                    <h5 class="card-text text-danger">{{ $fmt($tunggakanTotal) }}</h5>
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