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
                    <h4 class="card-title">Data Tahun Ajaran</h4>
                    <form action="{{ route('super_admin.data_tahun_ajaran.store') }}" method="POST">
                      @csrf
                        <div class="form-group">
                            <label for="academic_year">Tahun Ajaran</label>
                            <input type="text" class="form-control" name="academic_year" id="academic_year" placeholder="Masukkan tahun ajaran">
                        </div>
                        <a href="{{ route('super_admin.data_tahun_ajaran') }}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>