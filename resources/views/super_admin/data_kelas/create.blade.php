<x-super_admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Tambah Data Kelas</h4>
                    <form action="{{ route('super_admin.data_kelas.store') }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="class">Kelas</label>
                          <input type="text" class="form-control" name="class" id="class" placeholder="Masukkan nama kelas" value="{{ old('class') }}">
                      </div>
                      <a href="{{ route('super_admin.data_kelas') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>