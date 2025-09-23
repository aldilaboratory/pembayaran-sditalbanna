<x-super_admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Tahun Ajaran</h4>
                    <a href="{{ route('super_admin.data_tahun_ajaran.create') }}" class="btn btn-primary mb-3">+ Tambah Tahun Ajaran Baru</a>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($academicYears as $academicYear)
                          <tr>
                            <td class="text-center">
                              {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                              {{ $academicYear->academic_year }}
                            </td>
                            <td class="text-center">
                              <a href="{{ route('super_admin.data_tahun_ajaran.edit', $academicYear->id) }}" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <form id="delete-form-{{ $academicYear->id }}" action="{{ route('super_admin.data_tahun_ajaran.destroy', $academicYear->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $academicYear->id }}" data-name="Tahun Angkatan {{ $academicYear->academic_year }}"><i class="mdi mdi-delete align-middle"></i> Hapus</button>
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