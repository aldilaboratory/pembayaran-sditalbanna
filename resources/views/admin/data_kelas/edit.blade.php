<x-admin.layout>
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

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Edit Data Kelas</h4>
                    <form action="{{ route('admin.data_kelas.update', $classes->id) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="form-group">
                          <label for="class">Kelas</label>
                          <input type="text" class="form-control" name="class" id="class" placeholder="Masukkan nama kelas" value="{{ old('class', $classes) }}" required>
                      </div>
                      <a href="{{ route('admin.data_kelas') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>