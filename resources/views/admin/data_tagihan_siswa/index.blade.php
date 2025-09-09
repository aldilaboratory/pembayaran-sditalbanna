<x-admin.layout title="Admin - Data Tagihan Siswa">
      {{-- Style untuk fitur autocomplete --}}
    <style>
      .autocomplete-wrapper {
        position: relative;
    }
    
    .autocomplete-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 300px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
    }
    
    .autocomplete-results.show {
        display: block;
    }
    
    .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }
    
    .autocomplete-item:hover,
    .autocomplete-item.selected {
        background-color: #f5f5f5;
    }
    
    .autocomplete-item:last-child {
        border-bottom: none;
    }
    
    .autocomplete-item .student-nis {
        font-weight: bold;
        color: #333;
    }
    
    .autocomplete-item .student-name {
        color: #666;
        margin: 0 5px;
    }
    
    .autocomplete-item .student-class {
        color: #999;
        font-size: 0.9em;
    }
    
    .autocomplete-no-results {
        padding: 10px 15px;
        color: #999;
        text-align: center;
    }
    
    .search-spinner {
        position: absolute;
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    
    .search-spinner.show {
        display: block;
    }
    </style>

<div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12">
                <a href="{{ route('admin.data_tagihan_siswa.create') }}" class="btn btn-primary mb-3">+ Input Tagihan Siswa Sekaligus</a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card rounded">
                  <div class="card-body">
                    <div class="form-group">
                      <h4 class="card-title">CARI INFORMASI DATA TAGIHAN SISWA</h4>
                      <form id="searchForm" action="{{ route('admin.data_tagihan_siswa.search') }}" method="POST">
                        @csrf
                        {{-- Hidden input untuk student_id dari autocomplete --}}
                        <input type="hidden" name="student_id" id="student_id">
                        <div class="input-group">
                          <label class="col-form-label me-5">NIS atau Nama</label>
                          {{-- Wrapper untuk autocomplete --}}
                          <div class="autocomplete-wrapper flex-grow-1">
                              <input type="text" 
                                      id="searchInput"
                                      name="search" 
                                      class="form-control rounded-end-0 rounded-start-2 py-3 @error('search') is-invalid @enderror" 
                                      placeholder="Ketik minimal 2 karakter untuk mencari..." 
                                      value="{{ old('search', isset($student) ? $student->nis . ' - ' . $student->nama . " ({$student->studentClass->class})" : '') }}"
                                      >
                              
                              {{-- Loading spinner --}}
                              <div class="search-spinner">
                                  <div class="spinner-border spinner-border-sm text-primary" role="status">
                                      <span class="visually-hidden">Loading...</span>
                                  </div>
                              </div>
                              
                              {{-- Dropdown results --}}
                              <div id="autocompleteResults" class="autocomplete-results"></div>
                          </div>
                          <div class="input-group-append">
                            <button class="btn btn-sm btn-primary rounded-start-0 rounded-end-2" type="submit">Search</button>
                          </div>
                        </div>
                        @error('search')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            {{-- Tampilkan data jika siswa ditemukan --}}
            @if (isset($student))
              {{-- Biodata Siswa --}}
               <div class="row">
                <div class="col-md-12">
                  <div class="card rounded">
                    <div class="card-body">
                      <h4 class="card-title">Biodata Siswa</h4>
                      <table class="table table-bordered">
                        <tr>
                          <td>NIS</td>
                          <td>{{ $student->nis }}</td>
                        </tr>
                        <tr>
                          <td>Nama</td>
                          <td>{{ $student->nama }}</td>
                        </tr>
                        <tr>
                          <td>Kelas</td>
                          <td>{{ $student->studentClass->class }}</td>
                        </tr>
                        <tr>
                          <td>Tahun Ajaran</td>
                          <td>{{ $student->schoolYear->school_year }}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Data Tagihan --}}
              <div class="row mt-3">
              <div class="col-md-12">
                <div class="card rounded">
                  <div class="card-body">
                    <h4 class="card-title">Data Tagihan</h4>
                    <button class="btn btn-primary mb-3">+ Siswa Ingin Membayar Secara Offline</button>
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center">
                              #
                            </th>
                            <th class="text-center">
                              Bulan
                            </th>
                            <th class="text-center">
                              Tahun Ajaran
                            </th>
                            <th class="text-center">
                              Jenis Tagihan
                            </th>
                            <th class="text-center">
                              Jumlah
                            </th>
                            <th class="text-center">
                              Jatuh Tempo
                            </th>
                            <th class="text-center">
                              Tanggal Lunas
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
                          @if ($student->schoolFee->count() > 0)
                            @foreach ($student->schoolFee as $index => $fee)
                              <tr>
                                <td class="text-center">
                                  {{ $loop->iteration }}
                                </td>
                                <td>
                                  {{ $fee->nama_bulan }}
                                </td>
                                <th class="text-center text-light-emphasis">
                                  {{ $fee->academicYear->academic_year }}
                                </th>
                                <td>
                                  @php
                                    $jenisTagihan = [
                                      'spp' => "SPP",
                                      'daftar_ulang' => "Daftar Ulang",
                                      'biaya_pengembangan' => "Biaya Pengembangan",
                                      'biaya_operasional' => "Biaya Operasional",
                                    ]
                                  @endphp
                                  {{ $jenisTagihan[$fee->jenis_tagihan] }}
                                </td>
                                <td class="text-end">
                                  Rp{{ number_format($fee->jumlah, 0, ',', ',') }}
                                </td>
                                <td>
                                  {{ $fee->jatuh_tempo ? \Carbon\Carbon::parse($fee->jatuh_tempo)->format('d F Y') : '-' }}
                                </td>
                                <td>
                                  {{ $fee->tanggal_lunas ? \Carbon\Carbon::parse($fee->tanggal_lunas)->format('d F Y') : '-' }}
                                </td>
                                <td class="text-center">
                                  @if($fee->status == 'lunas')
                                      <label class="badge badge-success">Lunas</label>
                                  @elseif($fee->status == 'cicilan')
                                      <label class="badge badge-warning">Cicilan</label>
                                  @else
                                      <label class="badge badge-danger">Belum Lunas</label>
                                  @endif
                                </td>
                                <td class="text-center">
                                  @if($fee->status == 'lunas')
                                      <p>-</p>
                                  @else
                                      <a class="btn btn-info btn-sm"><i class="mdi mdi-square-edit-outline align-middle"></i> Edit</a>
                                  @endif
                                </td>
                              </tr>
                            @endforeach
                            
                          @else
                            
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
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

    {{-- JavaScript untuk Autocomplete --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const autocompleteResults = document.getElementById('autocompleteResults');
        const studentIdInput = document.getElementById('student_id');
        const searchForm = document.getElementById('searchForm');
        const searchSpinner = document.querySelector('.search-spinner');
        const searchButton = document.getElementById('searchButton');
        const selectedIndicator = document.getElementById('selectedIndicator');
        const selectedText = document.getElementById('selectedText');
        
        let searchTimeout;
        let selectedIndex = -1;
        let searchResults = [];
        let currentSelectedStudent = null;

        // Event listener untuk input search
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            // Reset selection jika user mengetik manual
            if (currentSelectedStudent && this.value !== currentSelectedStudent.nama) {
                clearSelection();
            }
            
            if (query.length < 2) {
                hideAutocomplete();
                return;
            }
            
            // Tampilkan loading spinner
            if (searchSpinner) searchSpinner.classList.add('show');
            
            // Delay search untuk mengurangi request
            searchTimeout = setTimeout(() => {
                fetchAutocomplete(query);
            }, 100);
        });

        // Fetch autocomplete results
        async function fetchAutocomplete(query) {
            try {
                const response = await fetch(`{{ route('admin.data_tagihan_siswa.autocomplete') }}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                searchResults = data;
                displayAutocomplete(data);
            } catch (error) {
                console.error('Error fetching autocomplete:', error);
                autocompleteResults.innerHTML = '<div class="autocomplete-no-results">Error loading results</div>';
                autocompleteResults.classList.add('show');
            } finally {
                if (searchSpinner) searchSpinner.classList.remove('show');
            }
        }

        // Display autocomplete results
        function displayAutocomplete(results) {
            if (!results || results.length === 0) {
                autocompleteResults.innerHTML = '<div class="autocomplete-no-results">Tidak ada hasil ditemukan</div>';
                autocompleteResults.classList.add('show');
                return;
            }
            
            let html = '';
            results.forEach((student, index) => {
                // Tampilkan NIS - Nama (Kelas) di dropdown
                html += `
                    <div class="autocomplete-item" data-index="${index}" data-id="${student.id}">
                        <span class="student-nis">${student.nis}</span>
                        <span class="student-name">- ${student.nama}</span>
                        <span class="student-class">(${student.kelas})</span>
                    </div>
                `;
            });
            
            autocompleteResults.innerHTML = html;
            autocompleteResults.classList.add('show');
            selectedIndex = -1;
            
            // Add click event to items
            document.querySelectorAll('.autocomplete-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectStudent(this.dataset.index);
                });
            });
        }

        // Select student from autocomplete
        function selectStudent(index) {
            const student = searchResults[index];
            if (student) {
                // Set nama saja di input display
                searchInput.value = student.inputValue || student.nama;
                
                // Set student_id untuk form submission
                studentIdInput.value = student.id;
                
                // Simpan data student yang dipilih
                currentSelectedStudent = student;
                
                // Tampilkan indicator
                showSelectedIndicator(student);
                
                // Ubah action form ke searchById
                searchForm.action = "{{ route('admin.data_tagihan_siswa.search.byId') }}";
                
                // Add visual feedback
                searchInput.classList.add('search-selected');
                
                hideAutocomplete();
                
                // Optional: Auto submit form
                searchForm.submit();
            }
        }

        // Show selected indicator
        function showSelectedIndicator(student) {
            if (selectedIndicator && selectedText) {
                selectedText.textContent = `Siswa terpilih: ${student.nis} - ${student.nama}`;
                selectedIndicator.style.display = 'block';
            }
        }

        // Clear selection
        function clearSelection() {
            studentIdInput.value = '';
            currentSelectedStudent = null;
            searchInput.classList.remove('search-selected');
            if (selectedIndicator) {
                selectedIndicator.style.display = 'none';
            }
            // Kembalikan action form ke search biasa
            searchForm.action = "{{ route('admin.data_tagihan_siswa.search') }}";
        }

        // Hide autocomplete dropdown
        function hideAutocomplete() {
            autocompleteResults.classList.remove('show');
            autocompleteResults.innerHTML = '';
            selectedIndex = -1;
            searchResults = [];
        }

        // Form submission handler
        searchForm.addEventListener('submit', function(e) {
            // Jika ada student_id, gunakan searchById
            if (studentIdInput.value) {
                this.action = "{{ route('admin.data_tagihan_siswa.search.byId') }}";
            } else {
                // Jika tidak ada, gunakan search biasa
                this.action = "{{ route('admin.data_tagihan_siswa.search') }}";
            }
        });

        // Keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const items = document.querySelectorAll('.autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
            } else if (e.key === 'Enter') {
                if (selectedIndex >= 0 && items.length > 0) {
                    e.preventDefault();
                    selectStudent(selectedIndex);
                }
                // Jika tidak ada yang dipilih dari dropdown, biarkan form submit normal
            } else if (e.key === 'Escape') {
                hideAutocomplete();
            }
        });

        // Update visual selection
        function updateSelection(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('selected');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('selected');
                }
            });
        }

        // Click outside to close
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                hideAutocomplete();
            }
        });

        // Focus event
        searchInput.addEventListener('focus', function() {
            if (this.value.length >= 2 && searchResults.length > 0 && !currentSelectedStudent) {
                autocompleteResults.classList.add('show');
            }
        });
    });
    </script>
</x-admin.layout>