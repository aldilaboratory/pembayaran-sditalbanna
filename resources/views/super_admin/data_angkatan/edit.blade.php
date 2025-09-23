<x-super_admin.layout>
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
                    <h4 class="card-title">Edit Data Angkatan</h4>
                    <form action="{{ route('super_admin.data_angkatan.update', $schoolYears->id) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="form-group">
                          <label for="school_year">Angkatan</label>
                          <input type="text" class="form-control" name="school_year" id="school_year" placeholder="Masukkan angkatan" value="{{ old('school_year', $schoolYears) }}" required>
                      </div>
                      <a href="{{ route('super_admin.data_angkatan') }}" class="btn btn-light">Kembali</a>
                      <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>