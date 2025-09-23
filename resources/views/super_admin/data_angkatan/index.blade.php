<x-super_admin.layout>
            <div class="row">
              <div class="col-md-12">
                {{-- @if (session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif --}}
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Angkatan</h4>
                    <a href="{{ route('super_admin.data_angkatan.create') }}" class="btn btn-primary mb-3">+ Tambah Angkatan Baru</a>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Angkatan
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($schoolYears as $schoolYear)
                          <tr>
                            <td class="text-center">
                              {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                              {{ $schoolYear->school_year }}
                            </td>
                            <td class="text-center">
                              <a href="{{ route('super_admin.data_angkatan.edit', $schoolYear->id) }}" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <form id="delete-form-{{ $schoolYear->id }}" action="{{ route('super_admin.data_angkatan.destroy', $schoolYear->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <!-- PENTING: type="button" agar tidak auto submit -->
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $schoolYear->id }}" data-name="Tahun Angkatan {{ $schoolYear->school_year }}"><i class="mdi mdi-delete align-middle"></i> Hapus</button>
                              </form>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</x-super_admin.layout>