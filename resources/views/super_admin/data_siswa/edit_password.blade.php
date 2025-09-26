<x-super_admin.layout>
  <div class="row">
    <div class="col-md-12">
      @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
      @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif

      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Password Login Siswa</h4>

          <form action="{{ route('super_admin.data_siswa.updatePassword', $students->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label>Nama Siswa</label>
              <input type="text" class="form-control" value="{{ $students->nama }}" readonly disabled>
            </div>

            <div class="mb-3">
              <label>NIS</label>
              <input type="text" class="form-control" value="{{ $students->nis }}" readonly disabled>
            </div>

            <div class="mb-3">
              <label for="new_password">Password Baru</label>
              <div class="col-12">
                <div class="input-group">
                  <input id="new_password" type="password" name="password"
                         class="form-control @error('password') is-invalid @enderror"
                         minlength="8" autocomplete="new-password" required>
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                    <i class="mdi mdi-eye-off"></i>
                  </button>
                </div>
                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="text-muted d-block mt-1 mb-3">Min 8 karakter, disarankan kombinasi huruf & angka.</small>
            </div>

            <div class="mb-3">
              <label for="password_confirmation">Konfirmasi Password</label>
              <div class="col-12">
                <div class="input-group">
                  <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                  <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                    <i class="mdi mdi-eye-off"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="row g-3 mb-4 align-items-center">
              
            </div>

            <a href="{{ route('super_admin.data_siswa') }}" class="btn btn-light">Kembali</a>
            <button type="submit" class="btn btn-primary mx-2">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Toggle show/hide password --}}
  <script>
    document.addEventListener('click', function(e){
      const btn = e.target.closest('.toggle-password');
      if(!btn) return;
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      const icon = btn.querySelector('i');
      if(!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('mdi-eye-off'); icon.classList.add('mdi-eye');
      } else {
        input.type = 'password';
        icon.classList.remove('mdi-eye'); icon.classList.add('mdi-eye-off');
      }
    });
  </script>
</x-super_admin.layout>
