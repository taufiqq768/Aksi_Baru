@extends('layouts.app')

@section('title', 'Data Pemeriksaan')
@section('page-title', 'Data Pemeriksaan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Pemeriksaan</li>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        }

        .btn-secondary {
            background-color: #6b7280;
            border-color: #6b7280;
        }

        .table th {
            background-color: #495057 !important;
            color: white !important;
            font-weight: 600;
            border: none !important;
            padding: 1rem 0.75rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-buttons {
            white-space: nowrap;
        }

        /* Stats Card Styling */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
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
        #pemeriksaanTable {
            font-size: 0.8rem;
        }

        #pemeriksaanTable th {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.6rem 0.5rem;
            background-color: #495057 !important;
            color: white !important;
            border: none !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #pemeriksaanTable td {
            font-size: 0.8rem;
            padding: 0.6rem 0.5rem;
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

        .modal-body .dropdown-item-text {
            font-size: 0.8rem;
        }

        .modal-body .form-check-label {
            font-size: 0.8rem;
        }

        /* Badge and Button adjustments */
        .badge {
            font-size: 0.7rem;
        }

        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
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

            #pemeriksaanTable {
                font-size: 0.75rem;
            }

            #pemeriksaanTable th {
                font-size: 0.7rem;
                padding: 0.5rem 0.4rem;
            }

            #pemeriksaanTable td {
                font-size: 0.75rem;
                padding: 0.5rem 0.4rem;
            }

            .modal-body {
                font-size: 0.8rem;
            }

            .modal-body .form-label {
                font-size: 0.75rem;
            }

            .modal-body .form-control,
            .modal-body .form-select {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card {
                padding: 0.75rem;
            }

            .stats-number {
                font-size: 1.75rem;
            }

            #pemeriksaanTable {
                font-size: 0.7rem;
            }

            #pemeriksaanTable th {
                font-size: 0.65rem;
                padding: 0.4rem 0.3rem;
            }

            #pemeriksaanTable td {
                font-size: 0.7rem;
                padding: 0.4rem 0.3rem;
            }

            .modal-body {
                font-size: 0.75rem;
            }

            .modal-body .form-label {
                font-size: 0.7rem;
            }

            .modal-body .form-control,
            .modal-body .form-select {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->count() }}</div>
                <div class="stats-label">Total Pemeriksaan</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->where('pemeriksaan_aktif', 'Y')->count() }}</div>
                <div class="stats-label">Pemeriksaan Aktif</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $pemeriksaan->where('pemeriksaan_aktif', 'N')->count() }}</div>
                <div class="stats-label">Pemeriksaan Selesai</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">
                    {{ $pemeriksaan->filter(function ($item) {
        return \Carbon\Carbon::parse($item->created_at)->month == now()->month; })->count() }}
                </div>
                <div class="stats-label">Bulan Ini</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Daftar Pemeriksaan</h5>
                <small class="text-muted">Kelola data pemeriksaan audit</small>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Tambah Data
            </button>
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
                <table id="pemeriksaanTable" class="table table-striped table-hover"
                    style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background-color: #495057 !important;">
                        <tr>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                No</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Jenis</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Jenis Pemeriksaan</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Judul</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Objek Audit</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Ojek Audit (Auditee)</th>
                            {{-- <th>Kepala Audit</th>
                            <th>Ketua Tim</th> --}}
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Tanggal Pemeriksaan</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Status</th>
                            <th
                                style="background-color: #495057 !important; color: white !important; border: none !important;">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemeriksaan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->pemeriksaan_pkpt }}</td>
                                <td>{{ $item->pemeriksaan_jenis }}</td>
                                <td>{{ $item->pemeriksaan_judul }}</td>
                                <td>{{ $item->pemeriksaan_objek }}</td>
                                <td>{{ $item->unit->unit_nama ?? 'N/A' }}</td>
                                {{-- <td>{{ $item->pemeriksaan_pengawas }}</td>
                                <td>{{ $item->pemeriksaan_ketua }}</td> --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($item->pemeriksaan_tgl_mulai)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($item->pemeriksaan_tgl_akhir)->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if ($item->pemeriksaan_aktif == 'Y')
                                        <span class="badge bg-danger">Aktif</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                            onclick="detailPemeriksaan({{ $item->pemeriksaan_id }})" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="editPemeriksaan({{ $item->pemeriksaan_id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deletePemeriksaan({{ $item->pemeriksaan_id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @if ($item->pemeriksaan_dokumen_surat_tugas)
                                            <a href="{{ asset('storage/documents/' . $item->pemeriksaan_dokumen_surat_tugas) }}"
                                                class="btn btn-sm btn-outline-success" target="_blank" title="Lihat Dokumen">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
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
    </div>
    </div>
    </div>


    @push('styles')
        <style>
            .card-header.bg-gradient {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
                color: white;
                border-radius: 16px 16px 0 0 !important;
            }

            .btn-primary {
                background: var(--primary-color);
                border: 1px solid var(--primary-color);
                color: white;
                font-weight: 500;
                border-radius: 10px;
                padding: 0.625rem 1.25rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .btn-primary:hover {
                background: var(--primary-dark);
                border-color: var(--primary-dark);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            }

            .btn-success {
                background: var(--success-color);
                border: 1px solid var(--success-color);
                color: white;
                font-weight: 500;
                border-radius: 10px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .btn-success:hover {
                background: #059669;
                border-color: #059669;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            }

            .btn-danger {
                background: var(--danger-color);
                border: 1px solid var(--danger-color);
                color: white;
                font-weight: 500;
                border-radius: 10px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .btn-danger:hover {
                background: #dc2626;
                border-color: #dc2626;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            }

            .table th {
                background-color: var(--bs-secondary-bg);
                border-top: none;
                font-weight: 600;
                color: var(--bs-body-color);
                padding: 1rem 0.75rem;
            }

            .table td {
                padding: 0.875rem 0.75rem;
                vertical-align: middle;
            }

            .modal-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
                color: white;
                border-radius: 16px 16px 0 0;
                border-bottom: none;
                padding: 1.5rem;
            }

            .modal-content {
                border: none;
                border-radius: 16px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            [data-bs-theme="dark"] .modal-content {
                background: var(--surface-dark);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.12);
            }

            .form-control:focus {
                border-color: var(--primary-light);
                box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
            }

            .form-control {
                border-radius: 10px;
                border: 1px solid var(--bs-border-color);
                padding: 0.75rem 1rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .form-select {
                border-radius: 10px;
                border: 1px solid var(--bs-border-color);
                padding: 0.75rem 1rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .form-select:focus {
                border-color: var(--primary-light);
                box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
            }
        </style>
    @endpush



    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pemeriksaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select class="form-select" name="pemeriksaan_jenis" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="Rutin">Rutin</option>
                                    <option value="Khusus">Khusus</option>
                                    <option value="Tematik">Tematik</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PKPT <span class="text-danger">*</span></label>
                                <select class="form-select" name="pemeriksaan_pkpt" required>
                                    <option value="">Pilih PKPT</option>
                                    <option value="pkpt">PKPT</option>
                                    <option value="non pkpt">Non PKPT</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="pemeriksaan_judul" rows="3"
                                placeholder="Masukkan judul pemeriksaan..." required></textarea>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Pemilihan Objek Audit</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Object</label>
                                    <select class="form-select" id="pemeriksaan_objek" onchange="filterUnitsByJenis()">
                                        <option value="">Semua Jenis</option>
                                        <option value="regional">Regional</option>
                                        <option value="divisi">Divisi</option>
                                        <option value="anper">Anper</option>
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Objek Audit <span class="text-danger">*</span></label>
                                    <select class="form-select" name="auditee" id="auditee" required>
                                        <option value="">Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_nama }}" data-jenis="{{ $unit->jenis }}"
                                                data-unit-id="{{ $unit->unit_id }}">
                                                {{ $unit->unit_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="unit_id" id="unit_id_hidden">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Informasi Surat Tugas</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Dokumen Surat Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pemeriksaan_nomor_st" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Dokumen Surat Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="pemeriksaan_tanggal_st" required>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Upload File Surat Tugas</label>
                                    <input type="file" class="form-control" name="file_surat_tugas" accept=".pdf"
                                        onchange="displayFileName(this)">
                                    <small class="form-text text-muted">Format file yang disarankan: PDF (maksimal
                                        5MB)</small>
                                    <div id="current_file" class="mt-2" style="display: none;">
                                        <small class="text-info">
                                            <i class="fas fa-file-pdf"></i>
                                            <span id="file_name"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Tim Audit</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kepala Audit <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pemeriksaan_pengawas" required>
                                            <option value="">Pilih Kepala Audit</option>
                                            @foreach ($anggotas as $anggota)
                                                <option value="{{ $anggota->user_nik }}">{{ $anggota->user_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ketua <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pemeriksaan_ketua" required>
                                            <option value="">Pilih Ketua</option>
                                            @foreach ($anggotas as $anggota)
                                                <option value="{{ $anggota->user_nik }}">{{ $anggota->user_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Petugas</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                            type="button" id="anggotaDspiDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="selectedAnggotaText">Pilih Petugas</span>
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="anggotaDspiDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($anggotas as $anggota)
                                                <li>
                                                    <div class="form-check dropdown-item-text py-2"
                                                        style="padding-left: 2rem; padding-right: 1rem;">
                                                        <input class="form-check-input anggota-checkbox me-3" type="checkbox"
                                                            name="pemeriksaan_petugas[]" value="{{ $anggota->user_nik }}"
                                                            id="anggota_{{ $anggota->user_nik }}"
                                                            style="transform: scale(1.2);">
                                                        <label class="form-check-label ms-1"
                                                            for="anggota_{{ $anggota->user_nik }}">
                                                            {{ $anggota->user_nama }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <small class="form-text text-muted">Klik untuk memilih/membatalkan pilihan
                                        anggota</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dateRange" name="pemeriksaan_tgl"
                                placeholder="Pilih rentang tanggal" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select class="form-select" name="pemeriksaan_jenis" id="edit_jenis" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="Rutin">Rutin</option>
                                    <option value="Khusus">Khusus</option>
                                    <option value="Tematik">Tematik</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PKPT <span class="text-danger">*</span></label>
                                <select class="form-select" name="pemeriksaan_pkpt" id="edit_pkpt" required>
                                    <option value="">Pilih PKPT</option>
                                    <option value="pkpt">PKPT</option>
                                    <option value="non pkpt">Non PKPT</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pemeriksaan_judul" id="edit_judul" required>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Pemilihan Objek Audit</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Objek Audit <span class="text-danger">*</span></label>
                                    <select class="form-select" name="pemeriksaan_objek" id="edit_objek"
                                        onchange="filterEditUnitsByJenis()">
                                        <option value="">Semua Jenis</option>
                                        <option value="regional">Regional</option>
                                        <option value="divisi">Divisi</option>
                                        <option value="anper">Anper</option>
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Ojek Audit (Auditee)<span class="text-danger">*</span></label>
                                    <select class="form-select" name="edit_auditee" id="edit_auditee" required>
                                        <option value="">Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}">
                                                {{ $unit->unit_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Informasi Surat Tugas</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Dokumen Surat Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pemeriksaan_nomor_st" id="edit_nomor_st"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Dokumen Surat Tugas <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="pemeriksaan_tanggal_st" id="edit_tgl_st"
                                        required>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Upload File Surat Tugas</label>
                                    <input type="file" class="form-control" name="pemeriksaan_doc"
                                        id="edit_file_surat_tugas" accept=".pdf" onchange="displayEditFileName(this)">
                                    <small class="form-text text-muted">Format file yang disarankan: PDF (maksimal
                                        5MB)</small>
                                    <div id="edit_current_file" class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-file-pdf"></i>
                                            <span id="edit_file_name"></span>
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Tim Audit</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kepala Audit <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pemeriksaan_pengawas" id="edit_kepala_audit"
                                            required>
                                            <option value="">Pilih Kepala Audit</option>
                                            @foreach ($anggotas as $anggota)
                                                <option value="{{ $anggota->user_nik }}">{{ $anggota->user_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ketua Tim <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pemeriksaan_ketua" id="edit_ketua" required>
                                            <option value="">Pilih Ketua Tim</option>
                                            @foreach ($anggotas as $anggota)
                                                <option value="{{ $anggota->user_nik }}">{{ $anggota->user_nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Petugas</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                            type="button" id="editAnggotaDspiDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="editSelectedAnggotaText">Pilih Petugas</span>
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="editAnggotaDspiDropdown"
                                            style="max-height: 200px; overflow-y: auto;">
                                            @foreach ($anggotas as $anggota)
                                                <li>
                                                    <div class="form-check dropdown-item-text py-2"
                                                        style="padding-left: 2rem; padding-right: 1rem;">
                                                        <input class="form-check-input edit-anggota-checkbox me-3"
                                                            type="checkbox" name="pemeriksaan_petugas[]"
                                                            value="{{ $anggota->user_nik }}"
                                                            id="edit_anggota_{{ $anggota->user_nik }}"
                                                            style="transform: scale(1.2);">
                                                        <label class="form-check-label ms-1"
                                                            for="edit_anggota_{{ $anggota->user_nik }}">
                                                            {{ $anggota->user_nama }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <small class="form-text text-muted">Klik untuk memilih/membatalkan pilihan
                                        anggota</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editDateRange" name="pemeriksaan_tgl"
                                placeholder="Pilih rentang tanggal" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="pemeriksaan_aktif" id="edit_aktif">
                                <option value="Y">Aktif</option>
                                <option value="N">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data pemeriksaan ini?</p>
                    <p class="text-danger"><strong>Data yang dihapus tidak dapat dikembalikan!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pemeriksaan -->
    <div class="modal fade" id="detailPemeriksaanModal" tabindex="-1" aria-labelledby="detailPemeriksaanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailPemeriksaanModalLabel">
                        <i class="fas fa-eye me-2"></i>Detail Pemeriksaan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">PKPT:</label>
                                <p class="form-control-plaintext" id="detail_pkpt">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Pemeriksaan:</label>
                                <p class="form-control-plaintext" id="detail_jenis">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Pemeriksaan:</label>
                                <p class="form-control-plaintext" id="detail_judul">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Objek Pemeriksaan:</label>
                                <p class="form-control-plaintext" id="detail_objek">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Unit:</label>
                                <p class="form-control-plaintext" id="detail_unit">-</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pengawas:</label>
                                <p class="form-control-plaintext" id="detail_pengawas">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ketua Tim:</label>
                                <p class="form-control-plaintext" id="detail_ketua">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Petugas:</label>
                                <p class="form-control-plaintext" id="detail_petugas">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Pemeriksaan:</label>
                                <p class="form-control-plaintext" id="detail_tanggal">-</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p class="form-control-plaintext" id="detail_status">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="detail_dokumen_section" style="display: none;">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dokumen Surat Tugas:</label>
                                <div>
                                    <a href="#" id="detail_dokumen_link" class="btn btn-outline-success btn-sm"
                                        target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i>Lihat Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#pemeriksaanTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                "order": [
                    [6, "desc"]
                ], // Sort by date column (index 6) descending
                "columnDefs": [{
                    "orderable": false,
                    "targets": [8]
                }, // Disable sorting for Action column
                {
                    "className": "text-center",
                    "targets": [0, 7, 8]
                } // Center align specific columns
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                "drawCallback": function (settings) {
                    // Re-initialize tooltips after table redraw
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Initialize date range pickers
            flatpickr("#dateRange", {
                mode: "range",
                dateFormat: "m/d/Y",
                showMonths: 2,
                defaultDate: [new Date(), new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1)],
                locale: {
                    rangeSeparator: ' - '
                }
            });

            flatpickr("#editDateRange", {
                mode: "range",
                dateFormat: "m/d/Y",
                showMonths: 2,
                defaultDate: [new Date(), new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1)],
                locale: {
                    rangeSeparator: ' - '
                }
            });
        });

        // Edit function - KOMPREHENSIF DAN ROBUST
        function editPemeriksaan(id) {
            console.log('Edit function called for ID:', id);

            // Fetch data terlebih dahulu
            fetch(`/pemeriksaan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received:', data);
                    console.log('pemeriksaan_jenis value:', data.pemeriksaan_jenis);
                    console.log('pemeriksaan_pkpt value:', data.pemeriksaan_pkpt);

                    // Set form action
                    document.getElementById('editForm').action = `/pemeriksaan/${id}`;

                    // 1. FIELD JENIS - Pastikan ada
                    const editJenis = document.getElementById('edit_jenis');
                    console.log('editJenis element found:', editJenis);
                    console.log('editJenis options:', editJenis ? editJenis.innerHTML : 'Element not found');
                    if (editJenis) {
                        console.log('Before setting - editJenis.value:', editJenis.value);
                        editJenis.value = data.pemeriksaan_jenis || '';
                        console.log('After setting - editJenis.value:', editJenis.value);
                        console.log('✓ Jenis set to:', editJenis.value);
                    } else {
                        console.error('✗ Field edit_jenis tidak ditemukan!');
                    }

                    // 2. FIELD PKPT - Pastikan ada
                    const editPkpt = document.getElementById('edit_pkpt');
                    if (editPkpt) {
                        editPkpt.value = data.pemeriksaan_pkpt || '';
                        console.log('✓ PKPT set to:', editPkpt.value);
                    } else {
                        console.error('✗ Field edit_pkpt tidak ditemukan!');
                    }

                    // 3. FIELD JUDUL - Sudah diperbaiki
                    const editJudul = document.getElementById('edit_judul');
                    if (editJudul) {
                        editJudul.value = data.pemeriksaan_judul || '';
                    }

                    // 4. FIELD OBJEK AUDIT - Pastikan ada
                    const editObjek = document.getElementById('edit_objek');
                    if (editObjek) {
                        editObjek.value = data.pemeriksaan_objek || '';

                    }

                    // 4. FIELD OBJEK AUDIT - Pastikan ada
                    const editAuditee = document.getElementById('edit_auditee');
                    if (editAuditee) {
                        editAuditee.value = data.unit_id || '';

                    }


                    // 5. FIELD NOMOR ST - Pastikan ada
                    const editNomorSt = document.getElementById('edit_nomor_st');
                    if (editNomorSt) {
                        editNomorSt.value = data.pemeriksaan_nomor_st || '';

                    }

                    // 6. FIELD TANGGAL DOKUMEN - Format dengan benar
                    const editTglSt = document.getElementById('edit_tgl_st');
                    if (editTglSt) {
                        if (data.pemeriksaan_tanggal_st) {
                            const date = new Date(data.pemeriksaan_tanggal_st);
                            const formattedDate = date.toISOString().split('T')[0];
                            editTglSt.value = formattedDate;
                            console.log('✓ Tanggal ST set to:', formattedDate);
                        }
                    }

                    // 7. FIELD KEPALA AUDIT - Set value and add fallback lookup for display name
                    const editKepalaAudit = document.getElementById('edit_kepala_audit');
                    if (editKepalaAudit) {
                        const pengawasNik = data.pemeriksaan_pengawas || '';
                        editKepalaAudit.value = pengawasNik;

                        // Add visual feedback - show name next to dropdown if available
                        if (pengawasNik && !data.pengawas) {
                            fetch(`/api/user/${pengawasNik}`)
                                .then(response => response.json())
                                .then(user => {
                                    // Create or update name display
                                    let nameDisplay = document.getElementById('edit_kepala_audit_name');
                                    if (!nameDisplay) {
                                        nameDisplay = document.createElement('small');
                                        nameDisplay.id = 'edit_kepala_audit_name';
                                        nameDisplay.className = 'text-muted ms-2';
                                        editKepalaAudit.parentNode.appendChild(nameDisplay);
                                    }
                                    nameDisplay.textContent = `(${user.user_nama})`;
                                })
                                .catch(() => {
                                    console.log('Could not fetch pengawas name');
                                });
                        }
                    }

                    // 8. FIELD KETUA - Set value and add fallback lookup for display name
                    const editKetua = document.getElementById('edit_ketua');
                    if (editKetua) {
                        const ketuaNik = data.pemeriksaan_ketua || '';
                        editKetua.value = ketuaNik;

                        // Add visual feedback - show name next to dropdown if available
                        if (ketuaNik && !data.ketua) {
                            fetch(`/api/user/${ketuaNik}`)
                                .then(response => response.json())
                                .then(user => {
                                    // Create or update name display
                                    let nameDisplay = document.getElementById('edit_ketua_name');
                                    if (!nameDisplay) {
                                        nameDisplay = document.createElement('small');
                                        nameDisplay.id = 'edit_ketua_name';
                                        nameDisplay.className = 'text-muted ms-2';
                                        editKetua.parentNode.appendChild(nameDisplay);
                                    }
                                    nameDisplay.textContent = `(${user.user_nama})`;
                                })
                                .catch(() => {
                                    console.log('Could not fetch ketua name');
                                });
                        }
                    }

                    // 9. FIELD STATUS - Pastikan ada
                    const editAktif = document.getElementById('edit_aktif');
                    if (editAktif) {
                        editAktif.value = (data.pemeriksaan_aktif === 'Y') ? '1' : '0';
                    }

                    // 10. FIELD TANGGAL RANGE - Pastikan ada
                    const editDateRange = document.getElementById('editDateRange');
                    if (editDateRange) {
                        if (data.pemeriksaan_tgl_mulai && data.pemeriksaan_tgl_akhir) {
                            const startDate = new Date(data.pemeriksaan_tgl_mulai).toLocaleDateString('en-US');
                            const endDate = new Date(data.pemeriksaan_tgl_akhir).toLocaleDateString('en-US');
                            editDateRange.value = `${startDate} - ${endDate}`;
                            console.log('✓ Date Range set to:', editDateRange.value);
                        }
                    }

                    // 11. PETUGAS CHECKBOXES - Robust handling
                    const petugasData = data.pemeriksaan_petugas || '';
                    const selectedPetugas = petugasData.split('/').map(id => id.trim()).filter(id => id);

                    // Clear all checkboxes first
                    document.querySelectorAll('.edit-anggota-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Check the selected petugas
                    let checkedCount = 0;
                    selectedPetugas.forEach(petugasId => {
                        const checkbox = document.querySelector(`.edit-anggota-checkbox[value="${petugasId}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkedCount++;
                        }
                    });
                    console.log(`✓ ${checkedCount} Petugas selected`);

                    // Update the display text
                    updateEditSelectedAnggotaText();

                    // 12. FILE DISPLAY - Show current file info
                    const currentFileDiv = document.getElementById('edit_current_file');
                    const fileNameSpan = document.getElementById('edit_file_name');

                    if (currentFileDiv && fileNameSpan) {
                        if (data.pemeriksaan_doc) {
                            fileNameSpan.innerHTML =
                                `File saat ini: <a href="/storage/documents/${data.pemeriksaan_doc}" target="_blank">Lihat file</a>`;
                            currentFileDiv.style.display = 'block';
                            console.log('✓ File info displayed');
                        } else {
                            currentFileDiv.style.display = 'none';
                            console.log('✓ No file to display');
                        }
                    }

                    console.log('✓ Semua field berhasil diproses');

                    // Buka modal setelah semua data terisi
                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Terjadi kesalahan saat mengambil data');
                });
        }

        // Delete function
        function deletePemeriksaan(id) {
            document.getElementById('deleteForm').action = `/pemeriksaan/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Test function untuk memastikan modal bisa dibuka
        function testModal() {
            console.log('Testing modal...');
            const modal = new bootstrap.Modal(document.getElementById('detailPemeriksaanModal'));
            modal.show();
        }

        // Detail function
        function detailPemeriksaan(id) {
            console.log('Detail button clicked for ID:', id);

            fetch(`/pemeriksaan/${id}/edit`)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Detail data received:', data);

                    // Populate detail fields
                    document.getElementById('detail_pkpt').textContent = data.pemeriksaan_pkpt || '-';
                    document.getElementById('detail_jenis').textContent = data.pemeriksaan_jenis || '-';
                    document.getElementById('detail_judul').textContent = data.pemeriksaan_judul || '-';
                    document.getElementById('detail_objek').textContent = data.pemeriksaan_objek || '-';

                    // Get unit name
                    if (data.unit && data.unit.unit_nama) {
                        document.getElementById('detail_unit').textContent = data.unit.unit_nama;
                    } else {
                        document.getElementById('detail_unit').textContent = '-';
                    }

                    // Get pengawas name - fallback to manual lookup if relationship is null
                    if (data.pengawas && data.pengawas.user_nama) {
                        document.getElementById('detail_pengawas').textContent = data.pengawas.user_nama;
                    } else if (data.pemeriksaan_pengawas) {
                        // Manual lookup for pengawas
                        fetch(`/api/user/${data.pemeriksaan_pengawas}`)
                            .then(response => response.json())
                            .then(user => {
                                document.getElementById('detail_pengawas').textContent = user.user_nama || '-';
                            })
                            .catch(() => {
                                document.getElementById('detail_pengawas').textContent = data.pemeriksaan_pengawas;
                            });
                    } else {
                        document.getElementById('detail_pengawas').textContent = '-';
                    }

                    // Get ketua name - fallback to manual lookup if relationship is null
                    if (data.ketua && data.ketua.user_nama) {
                        document.getElementById('detail_ketua').textContent = data.ketua.user_nama;
                    } else if (data.pemeriksaan_ketua) {
                        // Manual lookup for ketua
                        fetch(`/api/user/${data.pemeriksaan_ketua}`)
                            .then(response => response.json())
                            .then(user => {
                                document.getElementById('detail_ketua').textContent = user.user_nama || '-';
                            })
                            .catch(() => {
                                document.getElementById('detail_ketua').textContent = data.pemeriksaan_ketua;
                            });
                    } else {
                        document.getElementById('detail_ketua').textContent = '-';
                    }

                    // Get petugas names - fallback to manual lookup if accessor returns empty
                    if (data.petugas && data.petugas.length > 0) {
                        const petugasNames = data.petugas.map(p => p.user_nama).join(', ');
                        document.getElementById('detail_petugas').textContent = petugasNames;
                    } else if (data.pemeriksaan_petugas) {
                        // Manual lookup for petugas
                        const petugasIds = data.pemeriksaan_petugas.split('/');
                        const petugasPromises = petugasIds.map(id =>
                            fetch(`/api/user/${id.trim()}`)
                                .then(response => response.json())
                                .then(user => user.user_nama)
                                .catch(() => id.trim())
                        );

                        Promise.all(petugasPromises)
                            .then(names => {
                                document.getElementById('detail_petugas').textContent = names.join(', ');
                            })
                            .catch(() => {
                                document.getElementById('detail_petugas').textContent = data.pemeriksaan_petugas;
                            });
                    } else {
                        document.getElementById('detail_petugas').textContent = '-';
                    }

                    // Format tanggal
                    if (data.pemeriksaan_tgl_mulai && data.pemeriksaan_tgl_akhir) {
                        const startDate = new Date(data.pemeriksaan_tgl_mulai).toLocaleDateString('id-ID');
                        const endDate = new Date(data.pemeriksaan_tgl_akhir).toLocaleDateString('id-ID');
                        document.getElementById('detail_tanggal').textContent = `${startDate} - ${endDate}`;
                    } else {
                        document.getElementById('detail_tanggal').textContent = '-';
                    }

                    // Status
                    const statusElement = document.getElementById('detail_status');
                    if (data.pemeriksaan_aktif == 'Y') {
                        statusElement.innerHTML = '<span class="badge bg-danger">Aktif</span>';
                    } else {
                        statusElement.innerHTML = '<span class="badge bg-success">Selesai</span>';
                    }

                    // Dokumen
                    const dokumenSection = document.getElementById('detail_dokumen_section');
                    const dokumenLink = document.getElementById('detail_dokumen_link');
                    if (data.pemeriksaan_dokumen_surat_tugas) {
                        dokumenLink.href = `/storage/documents/${data.pemeriksaan_dokumen_surat_tugas}`;
                        dokumenSection.style.display = 'block';
                    } else {
                        dokumenSection.style.display = 'none';
                    }

                    console.log('About to show modal...');
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('detailPemeriksaanModal'));
                    modal.show();
                    console.log('Modal should be visible now');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data pemeriksaan: ' + error.message);
                });
        }

        // Filter units by jenis for Add Modal
        function filterUnitsByJenis() {
            const filterSelect = document.getElementById('pemeriksaan_objek');
            const unitSelect = document.getElementById('auditee');
            const selectedJenis = filterSelect.value;

            // Get all options
            const allOptions = unitSelect.querySelectorAll('option');

            // Show/hide options based on selected jenis
            allOptions.forEach(option => {
                if (option.value === '') {
                    // Always show the default "Pilih Unit" option
                    option.style.display = 'block';
                } else {
                    const optionJenis = option.getAttribute('data-jenis');
                    if (selectedJenis === '' || optionJenis === selectedJenis) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });

            // Reset unit selection if current selection is now hidden
            const currentSelection = unitSelect.value;
            if (currentSelection !== '') {
                const currentOption = unitSelect.querySelector(`option[value="${currentSelection}"]`);
                if (currentOption && currentOption.style.display === 'none') {
                    unitSelect.value = '';
                }
            }
        }

        // Filter units by jenis for Edit Modal
        function filterEditUnitsByJenis() {
            const filterSelect = document.getElementById('edit_objek');
            const unitSelect = document.getElementById('edit_auditee');
            const selectedJenis = filterSelect.value;

            // Get all options
            const allOptions = unitSelect.querySelectorAll('option');

            // Show/hide options based on selected jenis
            allOptions.forEach(option => {
                if (option.value === '') {
                    // Always show the default "Pilih Unit" option
                    option.style.display = 'block';
                } else {
                    const optionJenis = option.getAttribute('data-jenis');
                    if (selectedJenis === '' || optionJenis === selectedJenis) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });

            // Reset unit selection if current selection is now hidden
            const currentSelection = unitSelect.value;
            if (currentSelection !== '') {
                const currentOption = unitSelect.querySelector(`option[value="${currentSelection}"]`);
                if (currentOption && currentOption.style.display === 'none') {
                    unitSelect.value = '';
                }
            }
        }

        // Display selected file name for Add Modal
        function displayFileName(input) {
            const fileDiv = document.getElementById('current_file');
            const fileNameSpan = document.getElementById('file_name');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileNameSpan.textContent = fileName;
                fileDiv.style.display = 'block';
            } else {
                fileDiv.style.display = 'none';
            }
        }

        // Display selected file name for Edit Modal
        function displayEditFileName(input) {
            const fileDiv = document.getElementById('edit_current_file');
            const fileNameSpan = document.getElementById('edit_file_name');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                fileNameSpan.textContent = fileName;
                fileDiv.style.display = 'block';
            } else {
                fileDiv.style.display = 'none';
            }
        }

        // Update selected anggota text for Add Modal
        function updateSelectedAnggotaText() {
            const checkboxes = document.querySelectorAll('.anggota-checkbox:checked');
            const selectedText = document.getElementById('selectedAnggotaText');

            if (checkboxes.length === 0) {
                selectedText.textContent = 'Pilih Petugas';
            } else if (checkboxes.length === 1) {
                selectedText.textContent = checkboxes[0].nextElementSibling.textContent.trim();
            } else {
                selectedText.textContent = `${checkboxes.length} petugas dipilih`;
            }
        }

        // Update selected anggota text for Edit Modal
        function updateEditSelectedAnggotaText() {
            const checkboxes = document.querySelectorAll('.edit-anggota-checkbox:checked');
            const selectedText = document.getElementById('editSelectedAnggotaText');

            if (checkboxes.length === 0) {
                selectedText.textContent = 'Pilih Petugas';
            } else if (checkboxes.length === 1) {
                selectedText.textContent = checkboxes[0].nextElementSibling.textContent.trim();
            } else {
                selectedText.textContent = `${checkboxes.length} petugas dipilih`;
            }
        }

        // Add event listeners for anggota checkboxes
        document.addEventListener('DOMContentLoaded', function () {
            // Add event listener for unit selection in add form
            const objekAuditSelect = document.getElementById('objek_audit_select');
            const unitIdHidden = document.getElementById('unit_id_hidden');

            if (objekAuditSelect && unitIdHidden) {
                objekAuditSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const unitId = selectedOption.getAttribute('data-unit-id');
                    unitIdHidden.value = unitId || '';
                });
            }

            // Add Modal checkboxes
            const anggotaCheckboxes = document.querySelectorAll('.anggota-checkbox');
            anggotaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedAnggotaText);
            });

            // Edit Modal checkboxes
            const editAnggotaCheckboxes = document.querySelectorAll('.edit-anggota-checkbox');
            editAnggotaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateEditSelectedAnggotaText);
            });

            // Prevent dropdown from closing when clicking on checkbox items
            document.querySelectorAll('.dropdown-item-text').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endpush