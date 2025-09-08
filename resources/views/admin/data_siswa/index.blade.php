<x-admin.layout title="Admin - Data Siswa">
    <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Data Siswa</h4>
                    <a href="{{ route('admin.data_siswa.create') }}" class="btn btn-primary mb-3">+ Tambah Data Siswa</a>
                    <div class="table-responsive mt-3">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Nama Siswa
                            </th>
                            <th class="text-center">
                              NIS
                            </th>
                            <th class="text-center">
                              Kelas
                            </th>
                            <th class="text-center">
                              Angkatan
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
                            </th>
                            <th class="text-center">
                              Status
                            </th>
                            <th class="text-center">
                              Aksi
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($students as $student)
                          <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td><a href="{{ route('admin.profil_siswa.edit', $student) }}">{{ $student->nama }}</a></td>
                            <td class="text-center">{{ $student->nis }}</td>
                            <td>{{ $student->studentClass->class }}</td>
                            <td class="text-center">{{ $student->schoolYear->school_year }}</td>
                            <td class="text-center">{{ $student->academicYear->academic_year }}</td>
                            <td class="text-center">
                              @if ($student->status === 'aktif')
                                <label class="badge badge-success">{{ $student->status }}</label>
                              @else
                                <label class="badge badge-danger">{{ $student->status }}</label>
                              @endif
                            </td>
                            <td class="text-center">
                              <a href="{{ route('admin.data_siswa.edit', $student->id) }}" class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                              <form id="delete-form-{{ $student->id }}" action="{{ route('admin.data_siswa.destroy', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $student->id }}" data-name="{{ $student->nama }}"><i class="mdi mdi-delete align-middle"></i> Hapus</button>
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
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div
              class="d-sm-flex justify-content-center justify-content-sm-between"
            >
              <span
                class="text-muted text-center text-sm-left d-block d-sm-inline-block"
                >Copyright © 2024
                <a href="https://www.bootstrapdash.com/" target="_blank"
                  >Bootstrapdash</a
                >. All rights reserved.</span
              >
              <span
                class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center"
                >Hand-crafted & made with
                <i class="mdi mdi-heart text-danger"></i
              ></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
</x-admin.layout>