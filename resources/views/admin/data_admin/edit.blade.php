<x-admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Edit Data Admin</h4>
                    <form action="{{ route('admin.data_admin.update', ['id' => $user->id]) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="form-group">
                          <label for="name">Nama</label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Masukkan nama pengguna" value="{{ old('name', $user->name) }}">
                          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="username" id="username" placeholder="Masukkan username" value="{{ old('username', $user->username) }}">
                          @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" class="form-control @error('name') is-invalid @enderror" name="password" id="password" placeholder="Masukkan password" value="{{ old('password') }}">
                          <small>*Kosongkan kolom ini apabila tidak ingin mengubah password</small>
                          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="role">Role</label>
                          <select class="form-select text-black @error('name') is-invalid @enderror" name="role" id="role">
                            <option disabled selected>Pilih Role</option>
                            <option value="super_admin" @selected($user->role === 'super_admin')>Super Admin</option>
                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            <option value="kepala_sekolah" @selected($user->role === 'kepala_sekolah')>Kepala Sekolah</option>
                          </select>
                          @error('role') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                      </div>
                      <a href="{{ route('admin.data_admin') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>