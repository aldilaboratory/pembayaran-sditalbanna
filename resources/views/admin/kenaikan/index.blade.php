<x-admin.layout title="Kenaikan Kelas Siswa">
  <div class="row">
    <div class="col-md-12">
      @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
      @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
      @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
      @endif

      <div class="card rounded">
        <div class="card-body">
          <h4 class="card-title">Kenaikan Kelas</h4>

          <form id="promoteForm" action="{{ route('admin.kenaikan.promote') }}" method="POST">
            @csrf

            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Kelas Awal</label>
                <select id="from_class_id" name="from_class_id" class="form-select text-black" required>
                  <option value="" disabled selected>Pilih awal</option>
                  @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedFrom==$c->id?'selected':'' }}>{{ $c->class }}</option>
                  @endforeach
                </select>
                <small id="infoCount" class="text-muted d-block mt-1"></small>
              </div>

              <div class="row g-3 mb-2">
                <div class="col-md-8">
                    <label class="form-label d-block">Mode Aksi</label>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode" id="mode_promote" value="promote" checked>
                    <label class="form-check-label ms-0" for="mode_promote">Naik Kelas</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode" id="mode_graduate" value="graduate">
                    <label class="form-check-label ms-0" for="mode_graduate">Luluskan</label>
                    </div>
                </div>
              </div>

              <div class="col-md-4" id="toClassWrap">
                <label class="form-label">Kelas Tujuan</label>
                <select id="to_class_id" name="to_class_id" class="form-select text-black">
                    <option value="" disabled selected>Pilih kelas tujuan</option>
                    @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedTo==$c->id?'selected':'' }}>{{ $c->class }}</option>
                    @endforeach
                </select>
              </div>

              {{-- <div class="col-md-4">
                <label class="form-label">Kelas Tujuan</label>
                <select id="to_class_id" name="to_class_id" class="form-select text-black" required>
                  <option value="" disabled selected>Pilih kelas tujuan</option>
                  @foreach ($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedTo==$c->id?'selected':'' }}>{{ $c->class }}</option>
                  @endforeach
                </select>
              </div> --}}

              {{-- <div class="col-md-4">
                <label class="form-label">Tahun Ajaran Baru (opsional)</label>
                <select id="academic_year_id" name="academic_year_id" class="form-select text-black">
                  <option value="">— Jangan ubah —</option>
                  @foreach ($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $selectedAy==$ay->id?'selected':'' }}>{{ $ay->academic_year }}</option>
                  @endforeach
                </select>
                <small class="text-muted">Pilih jika ingin sekaligus meng-update tahun ajaran siswa.</small>
              </div> --}}
            </div>

            <hr class="my-4">

            <div id="studentsBox" class="d-none">
              <div class="mb-2">
                <h5 class="mb-4">Pilih Siswa</h5>
                <div>
                  <label class="me-2"><input type="checkbox" id="checkAll"> Pilih Semua</label>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr>
                      <th class="text-center" style="width:40px;">#</th>
                      <th style="width:60px;">Pilih</th>
                      <th>NIS</th>
                      <th>Nama</th>
                    </tr>
                  </thead>
                  <tbody id="studentsTbody">
                    {{-- diisi via JS --}}
                  </tbody>
                </table>
              </div>

              <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                  <i class="mdi mdi-arrow-up-bold"></i> Naikkan Kelas
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const fromSelect   = document.getElementById('from_class_id');
    const infoCount    = document.getElementById('infoCount');
    const studentsBox  = document.getElementById('studentsBox');
    const tbody        = document.getElementById('studentsTbody');
    const checkAll     = document.getElementById('checkAll');

    async function loadStudents(classId){
      infoCount.textContent = 'Memuat siswa...';
      tbody.innerHTML = '';
      studentsBox.classList.add('d-none');

      if(!classId) return;

      try{
        const url = new URL("{{ route('admin.kenaikan.students') }}", window.location.origin);
        url.searchParams.set('class_id', classId);

        const resp = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
        if(!resp.ok) throw new Error('HTTP '+resp.status);
        const data = await resp.json();

        infoCount.textContent = `Ditemukan ${data.count} siswa aktif di kelas ini.`;

        if(data.count > 0){
          let rows='';
          data.students.forEach((s,idx)=>{
            rows += `
              <tr>
                <td class="text-center">${idx+1}</td>
                <td><input type="checkbox" class="row-check" name="student_ids[]" value="${s.id}"></td>
                <td>${s.nis ?? '-'}</td>
                <td>${s.nama}</td>
              </tr>`;
          });
          tbody.innerHTML = rows;
          studentsBox.classList.remove('d-none');
          checkAll.checked = false;
        } else {
          studentsBox.classList.add('d-none');
        }
      }catch(e){
        infoCount.textContent = 'Gagal memuat data siswa.';
        console.error(e);
      }
    }

    fromSelect.addEventListener('change', (e)=> loadStudents(e.target.value));
    // auto-load jika preselected
    if(fromSelect.value){ loadStudents(fromSelect.value); }

    // pilih semua
    checkAll.addEventListener('change', function(){
      document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
    });
  </script>

  <script>
  const modePromote = document.getElementById('mode_promote');
  const modeGraduate = document.getElementById('mode_graduate');
  const toClassWrap = document.getElementById('toClassWrap');
  const toSelect = document.getElementById('to_class_id');
  const submitBtn = document.querySelector('#studentsBox button[type="submit"]');

  function refreshModeUI() {
    if (modeGraduate.checked) {
      toClassWrap.classList.add('d-none');
      toSelect.value = '';
      submitBtn.innerHTML = '<i class="mdi mdi-school"></i> Luluskan Siswa';
    } else {
      toClassWrap.classList.remove('d-none');
      submitBtn.innerHTML = '<i class="mdi mdi-arrow-up-bold"></i> Naikkan Kelas';
    }
  }
  modePromote.addEventListener('change', refreshModeUI);
  modeGraduate.addEventListener('change', refreshModeUI);
  refreshModeUI();

  // Konfirmasi sebelum submit (melengkapi script yang sudah ada)
  const promoteForm = document.getElementById('promoteForm');
  promoteForm.addEventListener('submit', async function(e){
    e.preventDefault();
    const selectedRows = Array.from(document.querySelectorAll('.row-check:checked'));
    const count = selectedRows.length;
    const fromText = fromSelect.options[fromSelect.selectedIndex]?.text || '-';
    const mode = modeGraduate.checked ? 'graduate' : 'promote';

    if (!fromSelect.value) return Swal.fire({icon:'warning', title:'Pilih kelas awal terlebih dahulu'});
    if (count === 0)      return Swal.fire({icon:'info', title:'Belum ada siswa yang dipilih'});

    if (mode === 'promote') {
      if (!toSelect.value) return Swal.fire({icon:'warning', title:'Pilih kelas tujuan terlebih dahulu'});
      if (fromSelect.value === toSelect.value) return Swal.fire({icon:'error', title:'Kelas tujuan harus berbeda'});
    }

    const toText = mode === 'promote'
      ? (toSelect.options[toSelect.selectedIndex]?.text || '-')
      : '— LULUS —';

    const result = await Swal.fire({
      icon: 'question',
      title: mode === 'promote' ? 'Konfirmasi Kenaikan Kelas' : 'Konfirmasi Kelulusan',
      html: `
        <div class="text-start">
          Anda akan ${mode==='promote' ? 'menaikkan' : 'meluluskan'} <b>${count}</b> siswa.<br>
          Dari: <b>${fromText}</b><br>
          Ke: <b>${toText}</b><br>
          <hr class="my-2">
          Lanjutkan?
        </div>
      `,
      showCancelButton: true,
      confirmButtonText: 'Ya, proses',
      cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) this.submit();
  });
</script>

</x-admin.layout>
