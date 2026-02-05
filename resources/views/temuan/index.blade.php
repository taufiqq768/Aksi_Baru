@extends('layouts.app')

@section('title', 'Data Temuan')
@section('page-title', 'Data Temuan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Temuan</li>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">
@push('styles')
<style>
/* ===============================
   FONT FAMILY
================================ */
body,
.modal-body,
.table,
.form-control,
.form-select,
.btn,
.card-body,
.dropdown-menu,
.card,
.badge {
    font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h1, h2, h3, h4, h5, h6,
.card-title,
.modal-title,
.page-title,
.stats-label,
th {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #374151;
}

/* ===============================
   CARD
================================ */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    transition: all .3s ease;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,.12);
}

.card-header {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem;
}

/* ===============================
   BUTTON
================================ */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all .3s ease;
    font-size: .875rem;
}

.btn-sm {
    font-size: .75rem;
    padding: .25rem .5rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,.15);
}

.btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.btn-success {
    background-color: #10b981;
    border-color: #10b981;
}

.btn-success:hover {
    background-color: #059669;
    border-color: #059669;
}

/* ===============================
   TABLE
================================ */
.table {
    font-size: .85rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
}

.table thead th {
    background: #495057;
    color: #fff;
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
}

/* ===============================
   BADGE
================================ */
.badge {
    font-size: .7rem;
    font-weight: 500;
    padding: .35em .65em;
    border-radius: 20px;
}

/* ===============================
   STATS CARD
================================ */
.stats-card {
    background: linear-gradient(135deg, #0a4d68 0%, #088395 50%, #05bfdb 100%);
    border-radius: 16px;
    padding: 1.5rem;
    color: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,.1);
    transition: all .3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
}

.stats-label {
    font-size: .875rem;
    text-transform: uppercase;
}

/* ===============================
   MODAL
================================ */
.modal-content {
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,.15);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {
    .stats-number { font-size: 2rem; }
    .table { font-size: .75rem; }
}

@media (max-width: 576px) {
    .stats-number { font-size: 1.75rem; }
}
</style>
@endpush


@section('content')
    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->count() }}</div>
                <div class="stats-label">Total Pemeriksaan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->where('lha')->count() }}</div>
                <div class="stats-label">Dengan LHA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->whereNull('lha')->count() }}</div>
                <div class="stats-label">Tanpa LHA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->sum('temuan_count') }}</div>
                <div class="stats-label">Total Temuan</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Daftar Temuan Pemeriksaan</h5>
                <small class="text-muted">Kelola data temuan hasil pemeriksaan audit</small>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="temuanTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Pemeriksaan</th>
                            <th>Unit</th>
                            <th>Tanggal</th>
                            <th>Jumlah Temuan</th>
                            <th>Dokumen LHA</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemeriksaan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->pemeriksaan_judul }}</td>
                                <td>{{ $item->unit->unit_nama ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->pemeriksaan_tgl_akhir)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $item->temuan_count }}</span>
                                </td>
                                <td>
                                    @if ($item->lha)
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-success mb-1">
                                                <i class="fas fa-file-pdf"></i> {{ $item->lha->no_lha }}
                                            </span>
                                            @if ($item->lha->file_lha)
                                                <small>
                                                    <a href="{{ asset('storage/lha/' . $item->lha->file_lha) }}"
                                                        target="_blank" class="text-primary text-decoration-none">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Belum Ada
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if ($item->lha)
                                            <!-- Jika LHA ada, tombol Kelola Temuan enable, Upload LHA disable -->
                                            <a href="{{ route('temuan.kelola', $item->pemeriksaan_id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Kelola Temuan">
                                                <i class="fas fa-cogs"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="LHA sudah ada">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        @else
                                            <!-- Jika LHA tidak ada, tombol Kelola Temuan disable, Upload LHA enable -->
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                title="Upload LHA terlebih dahulu">
                                                <i class="fas fa-cogs"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                                data-bs-target="#uploadLhaModal"
                                                data-pemeriksaan-id="{{ $item->pemeriksaan_id }}"
                                                data-pemeriksaan-judul="{{ $item->pemeriksaan_judul }}" title="Upload LHA">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with pagination
            var table = $('#temuanTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                "responsive": true,
                "order": [
                    [3, "desc"]
                ], // Sort by date column (index 3) descending
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 5, 6]
                    }, // Disable sorting for No, LHA and Action columns
                    {
                        "className": "text-center",
                        "targets": [0, 4, 5, 6]
                    }, // Center align specific columns
                    {
                        "searchable": false,
                        "targets": [0]
                    } // Disable search for No column
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "drawCallback": function(settings) {
                    // Re-initialize tooltips after table redraw
                    $('[data-bs-toggle="tooltip"]').tooltip();

                    // Update row numbers based on current page
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                }
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Upload LHA Modal
            $('#uploadLhaModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var pemeriksaanId = button.data('pemeriksaan-id');
                var pemeriksaanJudul = button.data('pemeriksaan-judul');

                var modal = $(this);
                modal.find('#pemeriksaan_id').val(pemeriksaanId);
                modal.find('#pemeriksaan_judul').text(pemeriksaanJudul);

                // Reset form
                modal.find('form')[0].reset();
                modal.find('#pemeriksaan_id').val(pemeriksaanId);
                modal.find('.is-invalid').removeClass('is-invalid');
                modal.find('.invalid-feedback').remove();
            });

            // File size validation
            $('#file_lha').on('change', function() {
                var file = this.files[0];
                var maxSize = 10 * 1024 * 1024; // 10MB in bytes

                if (file && file.size > maxSize) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.invalid-feedback').length === 0) {
                        $(this).after('<div class="invalid-feedback">Ukuran file maksimal 10MB</div>');
                    }
                    $(this).val('');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Form validation
            $('form').on('submit', function(e) {
                var isValid = true;

                // Validate required fields
                $(this).find('input[required], select[required], textarea[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        if ($(this).next('.invalid-feedback').length === 0) {
                            $(this).after(
                                '<div class="invalid-feedback">Field ini wajib diisi</div>');
                        }
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.invalid-feedback').remove();
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush

<!-- Modal Upload LHA -->
<div class="modal fade" id="uploadLhaModal" tabindex="-1" aria-labelledby="uploadLhaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadLhaModalLabel">Upload Dokumen LHA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('lha.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_pemeriksaan" id="pemeriksaan_id">

                    <div class="mb-3">
                        <label class="form-label"><strong>Pemeriksaan:</strong></label>
                        <p id="pemeriksaan_judul" class="text-muted"></p>
                    </div>

                    <div class="mb-3">
                        <label for="no_lha" class="form-label">Nomor LHA <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_lha" name="no_lha" required>
                    </div>

                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="tahun" name="tahun"
                            value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="file_lha" class="form-label">File LHA (PDF)</label>
                        <input type="file" class="form-control" id="file_lha" name="file_lha" accept=".pdf"
                            onchange="validateFileSize(this)">
                        <small class="text-muted">Format: PDF, Maksimal 10MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="1">Draft</option>
                            <option value="2">Final</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateFileSize(input) {
        const file = input.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                input.value = '';
            }
        }
    }
</script>
