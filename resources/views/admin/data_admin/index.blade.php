<x-admin.layout>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Data Admin</h4>
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
                              -
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
</x-admin.layout>