<x-admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Tambah Data Admin</h4>
                    <form action="{{ route('admin.data_admin.store') }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="name">Nama</label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Masukkan nama pengguna" value="{{ old('name') }}">
                          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="username" id="username" placeholder="Masukkan username" value="{{ old('username') }}">
                          @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" class="form-control @error('name') is-invalid @enderror" name="password" id="password" placeholder="Masukkan password" value="{{ old('password') }}">
                          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>
                      <div class="form-group">
                          <label for="role">Role</label>
                          <select class="form-select text-black @error('name') is-invalid @enderror" name="role" id="role">
                            <option disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
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