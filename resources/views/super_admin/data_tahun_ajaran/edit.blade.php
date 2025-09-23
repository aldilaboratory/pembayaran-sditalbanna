<x-super_admin.layout>
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
                    <h4 class="card-title">Edit Data Tahun Ajaran</h4>
                    <form action="{{ route('super_admin.data_tahun_ajaran.update', $academicYears->id) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="form-group">
                          <label for="academic_year">Tahun Ajaran</label>
                          <input type="text" class="form-control" name="academic_year" id="academic_year" placeholder="Masukkan nama kelas" value="{{ old('academic_year', $academicYears) }}" required>
                      </div>
                      <a href="{{ route('super_admin.data_tahun_ajaran') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>