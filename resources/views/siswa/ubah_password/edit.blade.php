<x-siswa.layout title="Ubah Password Siswa">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card rounded">
        <div class="card-body">
          <h4 class="card-title">Ubah Password Login</h4>

          @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('siswa.password.update') }}" method="POST" class="form-sample">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Username</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ auth()->user()->username ?? '-' }}" readonly disabled>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Password Saat Ini</label>
                  <div class="col-sm-8">
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Password Baru</label>
                  <div class="col-sm-8">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted d-block mt-1">Min 8 karakter, kombinasi huruf besar, kecil, dan angka.</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Konfirmasi Password Baru</label>
                  <div class="col-sm-8">
                    <input type="password" name="password_confirmation" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <a href="{{ route('siswa.dashboard') }}" class="btn btn-light btn-sm align-items-center">
                <i class="mdi mdi-arrow-left align-middle"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary btn-sm ms-2">
                <i class="mdi mdi-content-save align-middle"></i> Simpan
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-siswa.layout>