<x-admin.layout>
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
                    <h4 class="card-title">Data Angkatan</h4>
                    <form action="{{ route('admin.data_angkatan.store') }}" method="POST">
                      @csrf
                        <div class="form-group">
                            <label for="school_year">Angkatan</label>
                            <input type="text" class="form-control" name="school_year" id="school_year" placeholder="Masukkan angkatan">
                        </div>
                        <a href="{{ route('admin.data_angkatan') }}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-admin.layout>