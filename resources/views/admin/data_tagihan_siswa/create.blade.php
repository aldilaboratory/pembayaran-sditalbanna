<x-admin.layout>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Tambah Data Tagihan Siswa</h4>

          {{-- Alert untuk menampilkan error --}}
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Berhasil!</strong> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          {{-- FORM simpan tagihan --}}
          <form action="{{ route('admin.data_tagihan_siswa.store') }}" method="POST" id="feeForm">
            @csrf

            {{-- pilih kelas (tanpa submit) --}}
            <div class="mb-3">
              <label for="class_id_select" class="form-label">Kelas</label>
              <select id="class_id_select" class="form-select text-dark @error('class_id') is-invalid @enderror">
                <option value="" selected disabled>Pilih Kelas</option>
                @foreach ($studentClasses as $class)
                  <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->class }}</option>
                @endforeach
              </select>
              <input type="hidden" name="class_id" id="class_id_hidden" value="{{ old('class_id') }}">
              <small class="text-muted" id="studentCountText"></small>
              @error('class_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <label for="jenis_tagihan" class="form-label">Jenis Tagihan</label>
                <select name="jenis_tagihan" id="jenis_tagihan" class="form-select text-dark @error('jenis_tagihan') is-invalid @enderror">
                  <option value="" disabled {{ old('jenis_tagihan') ? '' : 'selected' }}>Pilih Jenis Tagihan</option>
                  <option value="spp"                @selected(old('jenis_tagihan')==='spp')>SPP</option>
                  <option value="daftar_ulang"       @selected(old('jenis_tagihan')==='daftar_ulang')>Daftar Ulang</option>
                  <option value="biaya_pengembangan" @selected(old('jenis_tagihan')==='biaya_pengembangan')>Biaya Pengembangan</option>
                  <option value="biaya_operasional"  @selected(old('jenis_tagihan')==='biaya_operasional')>Biaya Operasional</option>
                </select>
                @error('jenis_tagihan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-4">
                <label for="jumlah" class="form-label">Tarif Tagihan</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control text-dark @error('jumlah') is-invalid @enderror"
                       value="{{ old('jumlah') }}" min="0" placeholder="Tanpa titik/koma">
                @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-4">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select text-dark @error('bulan') is-invalid @enderror">
                  <option value="" disabled {{ old('bulan') ? '' : 'selected' }}>Pilih Bulan</option>
                  @php
                    $months=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                  @endphp
                  @foreach($months as $v=>$label)
                    <option value="{{ $v }}" @selected(old('bulan')==$v)>{{ $label }}</option>
                  @endforeach
                </select>
                @error('bulan') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-4">
                <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-select text-dark @error('tahun_ajaran') is-invalid @enderror">
                  <option value="" disabled {{ old('tahun_ajaran') ? '' : 'selected' }}>Pilih Tahun Ajaran</option>
                  @foreach ($academicYears as $ay)
                    <option value="{{ $ay->id }}" @selected(old('tahun_ajaran')==$ay->id)>{{ $ay->academic_year }}</option>
                  @endforeach
                </select>
                @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-4">
                <label for="jatuh_tempo" class="form-label">Jatuh Tempo</label>
                <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control @error('jatuh_tempo') is-invalid @enderror"
                       value="{{ old('jatuh_tempo', date('Y-m-d')) }}">
                @error('jatuh_tempo') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- DAFTAR SISWA (dari AJAX) --}}
            <hr>
            <div id="studentsBox" style="display:none;">
              <div class="mb-2">
                <label class="form-check">
                  <input type="checkbox" class="form-check-input text-dark" id="check_all">
                  <span class="form-check-label">Pilih Semua</span>
                </label>
              </div>

              <div class="table-responsive" style="max-height: 380px; overflow:auto;">
                <table class="table table-sm table-bordered align-middle">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center" style="width:40px;">#</th>
                      <th style="width:60px;">Pilih</th>
                      <th>NIS</th>
                      <th>Nama</th>
                    </tr>
                  </thead>
                  <tbody id="studentsTbody"></tbody>
                </table>
              </div>
            </div>

            <div class="mt-3">
              <a href="{{ route('admin.data_tagihan_siswa') }}" class="btn btn-light">Kembali</a>
              <button type="submit" class="btn btn-primary mx-2" id="submitBtn" disabled>Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const classSelect   = document.getElementById('class_id_select');
    const classHidden   = document.getElementById('class_id_hidden');
    const studentsBox   = document.getElementById('studentsBox');
    const tbody         = document.getElementById('studentsTbody');
    const checkAll      = document.getElementById('check_all');
    const submitBtn     = document.getElementById('submitBtn');
    const countText     = document.getElementById('studentCountText');

    // Function untuk menampilkan alert error
    function showFieldError(fieldName, message) {
      alert(`Error pada field ${fieldName}: ${message}`);
    }

    // Cek dan tampilkan error untuk setiap field saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      @if($errors->any())
        @foreach($errors->all() as $error)
          alert('{{ $error }}');
        @endforeach
      @endif

      // Restore kelas yang dipilih jika ada error
       const selectedClassId = '{{ old('class_id') }}';
       if (selectedClassId) {
         classSelect.value = selectedClassId;
         classHidden.value = selectedClassId;
         // Trigger change event untuk memuat siswa
         classSelect.dispatchEvent(new Event('change'));
       }

       // Event listener untuk form submission
        document.getElementById('feeForm').addEventListener('submit', function(e) {
          if (!validateForm()) {
            e.preventDefault();
            return false;
          }
        });

        // Event listener untuk pengecekan duplikasi real-time
        document.querySelector('select[name="jenis_tagihan"]')?.addEventListener('change', checkDuplicateTagihan);
        document.querySelector('select[name="bulan"]')?.addEventListener('change', checkDuplicateTagihan);
        document.querySelector('select[name="tahun_ajaran"]')?.addEventListener('change', checkDuplicateTagihan);
        
        // Event listener untuk checkbox siswa
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('student-check')) {
                checkDuplicateTagihan();
            }
        });
      });

      // Fungsi untuk mengecek duplikasi tagihan
       async function checkDuplicateTagihan() {
         const jenisTagihan = document.querySelector('select[name="jenis_tagihan"]').value;
         const bulan = document.querySelector('select[name="bulan"]').value;
         const tahunAjaran = document.querySelector('select[name="tahun_ajaran"]').value;
         const checkedStudents = document.querySelectorAll('.student-check:checked');

         if (!jenisTagihan || !bulan || !tahunAjaran || checkedStudents.length === 0) {
           return; // Skip jika data tidak lengkap
         }

         const studentIds = Array.from(checkedStudents).map(cb => cb.value);

         try {
           const response = await fetch('{{ route("admin.data_tagihan_siswa.check_duplicate") }}', {
             method: 'POST',
             headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
             },
             body: JSON.stringify({
               jenis_tagihan: jenisTagihan,
               bulan: bulan,
               academic_year_id: tahunAjaran,
               student_ids: studentIds
             })
           });

           const data = await response.json();
           
           if (data.duplicate) {
             alert(data.message);
           }
         } catch (error) {
           console.error('Error checking duplicate:', error);
         }
       }

       // Fungsi validasi form sebelum submit
       function validateForm() {
         // Validasi kelas
         if (!classHidden.value) {
           alert('Error: Silakan pilih kelas terlebih dahulu!');
           return false;
         }

         // Validasi jenis tagihan
         const jenisTagihan = document.querySelector('select[name="jenis_tagihan"]').value;
         if (!jenisTagihan) {
           alert('Error: Silakan pilih jenis tagihan!');
           return false;
         }

         // Validasi jumlah tagihan
         const jumlah = document.querySelector('input[name="jumlah"]').value;
         if (!jumlah || jumlah <= 0) {
           alert('Error: Jumlah tagihan harus lebih dari 0!');
           return false;
         }

         // Validasi bulan
         const bulan = document.querySelector('select[name="bulan"]').value;
         if (!bulan) {
           alert('Error: Silakan pilih bulan!');
           return false;
         }

         // Validasi tahun ajaran
         const tahunAjaran = document.querySelector('select[name="tahun_ajaran"]').value;
         if (!tahunAjaran) {
           alert('Error: Silakan pilih tahun ajaran!');
           return false;
         }

         // Validasi jatuh tempo
         const jatuhTempo = document.querySelector('input[name="jatuh_tempo"]').value;
         if (!jatuhTempo) {
           alert('Error: Silakan isi tanggal jatuh tempo!');
           return false;
         }

         // Validasi siswa yang dipilih
         const checkedStudents = document.querySelectorAll('.student-check:checked');
         if (checkedStudents.length === 0) {
           alert('Error: Silakan pilih minimal satu siswa!');
           return false;
         }

         return true;
       }

      function updateSubmitState() {
        const anyChecked = !!document.querySelector('.student-check:checked');
        submitBtn.disabled = !anyChecked;
      }

    checkAll?.addEventListener('change', () => {
      document.querySelectorAll('.student-check').forEach(cb => cb.checked = checkAll.checked);
      updateSubmitState();
    });

    document.addEventListener('change', (e) => {
      if (e.target.classList?.contains('student-check')) {
        // sinkronkan checkAll jika ada yang dicentang/di-uncheck
        const all = document.querySelectorAll('.student-check');
        const checked = document.querySelectorAll('.student-check:checked');
        checkAll.checked = (all.length && checked.length === all.length);
        updateSubmitState();
      }
    });

    classSelect.addEventListener('change', async function(){
      const classId = this.value;
      classHidden.value = classId;
      submitBtn.disabled = true; // lock sementara

      tbody.innerHTML = '<tr><td colspan="4" class="text-center">Memuat data...</td></tr>';
      studentsBox.style.display = 'block';
      countText.textContent = '';

      try {
        const url = "{{ route('admin.data_tagihan_siswa.students') }}" + '?class_id=' + encodeURIComponent(classId);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) throw new Error('Gagal memuat siswa.');
        const data = await res.json();

        // render rows
        if (!data.students || !data.students.length) {
          tbody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada siswa aktif pada kelas ini.</td></tr>';
          checkAll.checked = false;
          submitBtn.disabled = true;
        } else {
          let html = '';
          data.students.forEach((s, idx) => {
            html += `
              <tr>
                <td class="text-center">${idx+1}</td>
                <td><input type="checkbox" name="student_ids[]" class="student-check" value="${s.id}"></td>
                <td>${s.nis ?? ''}</td>
                <td>${s.nama}</td>
              </tr>
            `;
          });
          tbody.innerHTML = html;
          checkAll.checked = false;
          countText.textContent = `Jumlah siswa aktif: ${data.count}`;
          updateSubmitState();
        }
      } catch (err) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error memuat data siswa.</td></tr>';
        submitBtn.disabled = true;
      }
    });
  </script>
</x-admin.layout>
