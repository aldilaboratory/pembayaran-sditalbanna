<x-super_admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Admin</h4>
                    <a class="btn btn-primary mb-3" href="{{ route('super_admin.data_admin.create') }}">+ Tambah Admin Baru</a>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Nama
                            </th>
                            <th class="text-center">
                              Username
                            </th>
                            <th class="text-center">
                              Role
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($admins as $admin)
                          <tr>
                            <td class="text-center">
                              {{ $loop->iteration }}
                            </td>
                            <td>
                              {{ $admin->name }}
                            </td>
                            <td>
                              {{ $admin->username }}
                            </td>
                            <td>
                              {{ $admin->role }}
                            </td>
                            <td class="text-center">
                              <a href="{{ route('super_admin.data_admin.edit', $admin->id) }}" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <form id="delete-form-{{ $admin->id }}" action="{{ route('super_admin.data_admin.destroy', $admin->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $admin->id }}" data-name="Pengguna {{ $admin->name }}"><i class="mdi mdi-delete align-middle"></i> Hapus</button>
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