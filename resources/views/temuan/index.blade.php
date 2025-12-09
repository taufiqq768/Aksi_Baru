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
    <style>
        /* Font Family Settings */
        body,
        .modal-body,
        .table,
        .form-control,
        .form-select,
        .btn,
        .card-body,
        .dropdown-menu {
            font-family: 'Roboto', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .card-title,
        .modal-title,
        .page-title,
        .stats-label,
        th {
            font-family: 'Montserrat', sans-serif;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            font-family: 'Montserrat', sans-serif;
        }

        .required::after {
            content: " *";
            color: #ef4444;
        }

        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background-color: #6b7280;
            border-color: #6b7280;
            font-weight: 500;
            border-radius: 8px;
        }

        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            font-size: 0.8rem;
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Stats Card Styling */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-label {
            font-size: 0.875rem;
            font-weight: 500;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Data Table Font Size */
        #temuanTable {
            font-size: 0.8rem;
        }

        #temuanTable th {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.75rem 0.5rem;
        }

        #temuanTable td {
            font-size: 0.8rem;
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
        }

        /* Modal Font Size */
        .modal-body {
            font-size: 0.85rem;
        }

        .modal-body .form-label {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        .modal-body .card-title {
            font-size: 0.85rem;
        }

        .modal-body .form-text {
            font-size: 0.7rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .stats-label {
                font-size: 0.75rem;
            }

            #temuanTable {
                font-size: 0.75rem;
            }

            #temuanTable th {
                font-size: 0.7rem;
                padding: 0.5rem 0.4rem;
            }

            #temuanTable td {
                font-size: 0.75rem;
                padding: 0.5rem 0.4rem;
            }

            .btn-sm {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card {
                padding: 0.75rem;
            }

            .stats-number {
                font-size: 1.75rem;
            }

            #temuanTable {
                font-size: 0.7rem;
            }

            #temuanTable th {
                font-size: 0.65rem;
                padding: 0.4rem 0.3rem;
            }

            #temuanTable td {
                font-size: 0.7rem;
                padding: 0.4rem 0.3rem;
            }

            .btn-sm {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
@endpush

@push('styles')
    <style>
        /* Font Family */
        body,
        .card,
        .table,
        .btn,
        .form-control,
        .modal,
        .badge {
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .card-title,
        .modal-title,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', 'Roboto', sans-serif !important;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Montserrat', sans-serif;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Button Styling */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* Table Styling */
        .table {
            font-size: 0.9rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .table thead th {
            background: #495057;
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem 0.75rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            padding: 0.875rem 0.75rem;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        /* Badge Styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-bottom: 1px solid #e9ecef;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table {
                font-size: 0.8rem;
            }

            .btn-sm {
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }

            .card-header {
                padding: 1rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .stats-card {
                padding: 1rem;
            }
        }

        /* DataTable specific styling */
        #temuanTable {
            font-size: 0.875rem;
        }

        #temuanTable thead th {
            font-size: 0.8rem;
        }

        #temuanTable tbody td {
            font-size: 0.85rem;
        }

        /* Modal styling */
        .modal-content {
            font-size: 0.9rem;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            border-bottom: none;
        }

        .modal-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            font-size: 0.875rem;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* DataTable pagination styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin: 0 2px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.25rem 0.5rem;
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
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stats-number">{{ $pemeriksaan->where('lha')->count() }}</div>
                <div class="stats-label">Dengan LHA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stats-number">{{ $pemeriksaan->whereNull('lha')->count() }}</div>
                <div class="stats-label">Tanpa LHA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
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
