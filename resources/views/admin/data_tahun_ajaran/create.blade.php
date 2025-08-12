<x-admin.layout>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                {{-- Tampilan error validasi --}}
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Tahun Ajaran</h4>
                    <form action="{{ route('admin.data_tahun_ajaran.store') }}" method="POST">
                      @csrf
                        <div class="form-group">
                            <label for="academic_year">Tahun Ajaran</label>
                            <input type="text" class="form-control" name="academic_year" id="academic_year" placeholder="Masukkan tahun ajaran">
                        </div>
                        <a href="{{ route('admin.data_tahun_ajaran') }}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
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