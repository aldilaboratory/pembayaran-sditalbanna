<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SD Albanna' }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/materialdesignicons-webfont.ttf') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.base.css') }}" />
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ 'images/albanna-icon.png' }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.css') }}">
  </head>
  <body>
    <div class="container-scroller">
      @include('sweetalert::alert')
      <!-- partial:partials/_navbar.html -->
      @include('components.siswa.header')
      <!-- partial -->  

      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('components.siswa.sidebar')

        <div class="main-panel">
          <div class="content-wrapper">
            {{ $slot }}
          </div>
          @include('components.siswa.footer')
        </div>

      </div>
      <!-- page-body-wrapper ends -->

    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('assets/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="{{ asset('assets/js/chart.umd.js') }}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/proBanner.js') }}"></script>

    <!-- End custom js for this page-->
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- DataTables -->
    <script src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}"></script>
    <script>
      new DataTable('#dataTable');
    </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    text: '{{ session('success') }}',
                    timer: 5000,
                    timerProgressBar: false,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top',
                    background: '#E6FFE6',
                });
            @endif
            
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    text: '{{ session('error') }}',
                    timer: 5000,
                    timerProgressBar: false,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top',
                    background: '#ffefea',
                });
            @endif
        });
        </script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
          button.addEventListener('click', function (e) {
            e.preventDefault(); // aman karena type="button" (tidak submit)
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
              title: 'Apakah Anda yakin?',
              text: `Data "${name}" akan dihapus!`,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Ya, hapus!',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
              }
            });
          });
        });
      });
    </script>
  </body>
</html>
