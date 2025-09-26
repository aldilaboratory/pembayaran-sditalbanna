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
                  <label for="username" class="col-4 col-form-label text-start mt-0">Username</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" value="{{ auth()->user()->username ?? '-' }}" readonly disabled>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row g-3 mb-3 align-items-center">
                  <label for="current_password" class="col-4 col-form-label text-start">
                    Password Saat Ini
                  </label>
                  <div class="col-8">
                    <div class="input-group">
                      <input id="current_password"
                            type="password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            autocomplete="current-password"
                            required>
                      <button type="button"
                              class="btn btn-outline-secondary toggle-password"
                              data-target="current_password"
                              aria-label="Tampilkan password">
                        <i class="mdi mdi-eye-off"></i>
                      </button>
                    </div>
                    @error('current_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="row g-3 mb-3 align-items-center">
                  <label for="new_password" class="col-4 col-form-label text-start mt-0">Password Baru</label>
                  <div class="col-8">
                    <div class="input-group">
                      <input id="new_password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password" minlength="8" required>
                      <button type="button" class="btn btn-outline-secondary toggle-password"
                              data-target="new_password"><i class="mdi mdi-eye-off"></i></button>
                    </div>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    <small class="text-muted d-block mt-1">Min 8 karakter, kombinasi huruf dan angka.</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row g-3 mb-3 align-items-center">
                  <label for="password_confirmation" class="col-4 col-form-label text-start">
                    Konfirmasi Password Baru
                  </label>
                  <div class="col-8">
                    <div class="input-group">
                      <input id="password_confirmation" type="password" name="password_confirmation"
                            class="form-control" autocomplete="new-password" required>
                      <button type="button" class="btn btn-outline-secondary toggle-password"
                              data-target="password_confirmation"><i class="mdi mdi-eye-off"></i></button>
                    </div>
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

  <script>
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const id = btn.getAttribute('data-target');
      const input = document.getElementById(id);
      const icon = btn.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('mdi-eye-off');
        icon.classList.add('mdi-eye');
        btn.setAttribute('aria-label', 'Sembunyikan password');
      } else {
        input.type = 'password';
        icon.classList.remove('mdi-eye');
        icon.classList.add('mdi-eye-off');
        btn.setAttribute('aria-label', 'Tampilkan password');
      }
    });
  });
</script>
</x-siswa.layout>