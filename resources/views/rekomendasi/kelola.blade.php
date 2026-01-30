@extends('layouts.app')

@section('title', 'Kelola Rekomendasi - ' . (isset($temuan) ? $temuan->temuan_judul : $pemeriksaan->pemeriksaan_judul))

@push('styles')
    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <style>
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 12px 8px;
            font-size: 0.875rem;
        }

        .table td {
            padding: 10px 8px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.2rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25em 0.6em;
        }

        #rekomendasiTable {
            font-size: 0.85rem;
        }

        .modal-body {
            font-size: 0.9rem;
        }

        .form-control {
            font-size: 0.875rem;
        }

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-gradient-success {
            background: linear-gradient(135deg, #67b853 0%, #054d23 100%);
        }
    </style>

    @if (auth()->user()->user_level === 'operator' || auth()->user()->user_level === 'verifikator')
    <style>
        .kolom-status { display: none; }
        .kolom-cb { display: none; }
        #btnKirimSelected { display: none; }
        #btnPublishSelected { display: none; }
        #btnTambahRekomendasi { display: none; }
    </style>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2>
                            Kelola Rekomendasi
                        </h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @if (isset($temuan))
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('temuan.kelola', $pemeriksaan->pemeriksaan_id) }}">Temuan</a></li>
                                    <li class="breadcrumb-item active">Rekomendasi</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{ route('rekomendasi.index') }}">Rekomendasi</a>
                                    </li>
                                    <li class="breadcrumb-item active">Kelola</li>
                                @endif
                            </ol>
                        </nav>
                    </div>

                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Info Pemeriksaan -->
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Judul</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $pemeriksaan->pemeriksaan_judul }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Auditee</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $pemeriksaan->unit ? $pemeriksaan->unit->unit_nama : 'Tidak ada unit' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Periode</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $pemeriksaan->pemeriksaan_tgl_mulai ? $pemeriksaan->pemeriksaan_tgl_mulai->format('d/m/Y') : '-' }}
                            -
                            {{ $pemeriksaan->pemeriksaan_tgl_akhir ? $pemeriksaan->pemeriksaan_tgl_akhir->format('d/m/Y') : '-' }}
                        </div>
                    </div>
                </div>
                @if (isset($temuan))
                <div class="accordion mb-3" id="accordionTemuan">
                    <div class="accordion-item border-0">

                        <!-- HEADER -->
                        <h6 class="accordion-header mb-0" id="headingTemuan">
                            <button class="accordion-button header-gradient-success text-black"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseTemuan"
                                    aria-expanded="false"
                                    aria-controls="collapseTemuan">
                                Temuan
                            </button>
                        </h6>

                        <!-- BODY (YANG PUNYA FRAME) -->
                        <div id="collapseTemuan"
                            class="accordion-collapse collapse"
                            aria-labelledby="headingTemuan"
                            data-bs-parent="#accordionTemuan">

                            <div class="accordion-body border rounded-bottom"
                                style="white-space: pre-line; border-color: #dee2e6;">
                                {{ strip_tags(
                                    str_replace(
                                        ['<br>', '<br/>', '<br />', '&nbsp;'],
                                        ["\n", "\n", "\n", ' '],
                                        html_entity_decode($temuan->temuan_judul)
                                    )
                                ) }}
                            </div>

                        </div>
                    </div>
                </div>
                @endif



                <!-- Daftar Rekomendasi -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Rekomendasi</h5>
                        <div class="d-flex gap-2">
                            <button type="button" id="btnKirimSelected" class="btn btn-success" disabled>
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                            <button type="button" id="btnPublishSelected" class="btn btn-info" disabled>
                                <i class="fas fa-share"></i> Publish
                            </button>
                            <button type="button" id="btnTambahRekomendasi" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addRekomendasiModal">
                                <i class="fas fa-plus"></i> Tambah Rekomendasi
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="rekomendasiTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="kolom-cb" data-orderable="false" data-searchable="false">
                                            <input type="checkbox" id="selectAllRekomendasi">
                                        </th>
                                        <th class="kolom-cb" data-orderable="false" data-searchable="false">
                                            <input type="checkbox" id="selectAllPublish">
                                        </th>
                                        <th>No</th>
                                        <th>Judul Rekomendasi</th>
                                        <th>Deadline</th>
                                        <th>Status TL</th>
                                        <th class="kolom-status">Status Kirim</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekomendasi as $index => $item)
                                        <tr>
                                            <td class="kolom-cb">
                                                <input type="checkbox" class="rek-check" value="{{ $item->rekomendasi_id }}"
                                                    @if ($item->rekomendasi_kirim === 'Y') disabled @endif>
                                            </td>
                                            <td class="kolom-cb">
                                                <input type="checkbox" class="publish-check" value="{{ $item->rekomendasi_id }}"
                                                    @if ($item->rekomendasi_publish_kabag === 'Y') disabled @endif>
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td style="white-space: pre-line;">
                                                                            {{ strip_tags(
                                            str_replace(
                                                ['<br>', '<br/>', '<br />', '&nbsp;'],
                                                ["\n", "\n", "\n", ' '],
                                                html_entity_decode($item->rekomendasi_judul)
                                            )
                                        ) }}

                                            </td>
                                            <td>
                                                @if ($item->rekomendasi_tgl_deadline)
                                                    {{ $item->rekomendasi_tgl_deadline->format('d/m/Y') }}
                                                    @if ($item->rekomendasi_tgl_deadline->isPast())
                                                        <br><span class="badge bg-danger">Terlambat</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @switch($item->rekomendasi_status)
                                                    @case('Belum di Tindak Lanjut')
                                                        <span class="badge bg-danger">Belum di Tindak Lanjut</span>
                                                    @break

                                                    @case('Sesuai')
                                                        <span class="badge bg-success">Sesuai</span>
                                                    @break

                                                    @case('Belum Sesuai')
                                                        <span class="badge bg-warning">Belum Sesuai</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="kolom-status">
                                                @if ($item->rekomendasi_kirim === 'N' && $item->rekomendasi_publish_kabag === 'N')
                                                    <span class="badge bg-danger">Belum dikirim</span>
                                                @elseif ($item->rekomendasi_kirim === 'Y' && $item->rekomendasi_publish_kabag === 'N')
                                                    <span class="badge bg-warning">Terkirim ke Kadiv</span>
                                                @elseif ($item->rekomendasi_kirim === 'Y' && $item->rekomendasi_publish_kabag === 'Y')
                                                    <span class="badge bg-success">Terkirim ke Auditi</span>
                                                @else
                                                    <span class="badge bg-danger">Belum dikirim</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                                    <button type="button" class="btn btn-outline-primary"
                                                        onclick="lihatTindakLanjut({{ $item->rekomendasi_id }})"
                                                        title="Lihat Tindak Lanjut">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning"
                                                        onclick="editRekomendasi({{ $item->rekomendasi_id }})"
                                                        title="Edit Rekomendasi">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="deleteRekomendasi({{ $item->rekomendasi_id }})"
                                                        title="Hapus Rekomendasi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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

    <!-- Modal Tambah Rekomendasi -->
    <div class="modal fade" id="addRekomendasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rekomendasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('rekomendasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">
                        @if (isset($temuan))
                            <input type="hidden" name="temuan_id" value="{{ $temuan->temuan_id }}">
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="rekomendasi_judul" class="form-label">Judul Rekomendasi *</label>
                                    <div id="editor_rekomendasi_judul" style="min-height: 150px;"></div>
                                    <input type="hidden" name="rekomendasi_judul" id="rekomendasi_judul" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rekomendasi_tgl" class="form-label">Tanggal Rekomendasi *</label>
                                    <input type="date" class="form-control" name="rekomendasi_tgl"
                                        id="rekomendasi_tgl"
                                        value="{{ $pemeriksaan->pemeriksaan_tgl_akhir ? $pemeriksaan->pemeriksaan_tgl_akhir->format('Y-m-d') : '' }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rekomendasi_tgl_deadline" class="form-label">Tanggal Deadline</label>
                                    <input type="date" class="form-control" name="rekomendasi_tgl_deadline"
                                        id="rekomendasi_tgl_deadline">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mention Unit</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="toggleMentionUnit"
                                            name="mention_unit">
                                        <label class="form-check-label" for="toggleMentionUnit">Mention Unit</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <select class="form-control" name="unit_id" id="unit_id" readonly>
                                        <option value="{{ $pemeriksaan->unit_id }}" selected>
                                            {{ $pemeriksaan->unit->unit_nama ?? 'Unit tidak ditemukan' }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}"
                                                {{ $pemeriksaan->unit_id == $unit->unit_id ? 'selected' : '' }}>
                                                {{ $unit->unit_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Rekomendasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Rekomendasi -->
    <div class="modal fade" id="editRekomendasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Rekomendasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRekomendasiForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">
                        @if (isset($temuan))
                            <input type="hidden" name="temuan_id" value="{{ $temuan->temuan_id }}">
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_rekomendasi_judul" class="form-label">Judul Rekomendasi *</label>
                                    <div id="editor_edit_rekomendasi_judul" style="min-height: 150px;"></div>
                                    <input type="hidden" name="rekomendasi_judul" id="edit_rekomendasi_judul" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_rekomendasi_tgl" class="form-label">Tanggal Rekomendasi *</label>
                                    <input type="date" class="form-control" name="rekomendasi_tgl"
                                        id="edit_rekomendasi_tgl" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_rekomendasi_tgl_deadline" class="form-label">Tanggal Deadline</label>
                                    <input type="date" class="form-control" name="rekomendasi_tgl_deadline"
                                        id="edit_rekomendasi_tgl_deadline">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if (!isset($temuan))
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_temuan_id" class="form-label">Terkait Temuan</label>
                                        <select class="form-control" name="temuan_id" id="edit_temuan_id">
                                            <option value="">Pilih Temuan (Opsional)</option>
                                            @foreach ($pemeriksaan->temuan as $temuanItem)
                                                <option value="{{ $temuanItem->temuan_id }}">
                                                    {{ $temuanItem->temuan_judul }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_unit_id" class="form-label">Unit</label>
                                    <select class="form-control" name="unit_id" id="edit_unit_id">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}">{{ $unit->unit_nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mention Unit</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="edit_toggleMentionUnit"
                                            name="mention_unit">
                                        <label class="form-check-label" for="toggleMentionUnit">Mention Unit</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <select class="form-control" name="unit_id" id="edit_unit_id" readonly>
                                        <option value="{{ $pemeriksaan->unit_id }}" selected>
                                            {{ $pemeriksaan->unit->unit_nama ?? 'Unit tidak ditemukan' }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}"
                                                {{ $pemeriksaan->unit_id == $unit->unit_id ? 'selected' : '' }}>
                                                {{ $unit->unit_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>




                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Rekomendasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- Quill.js JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        .ql-editor {
            min-height: 150px;
        }
    </style>
    <script>
        // Initialize Quill editors
        let quillRekomendasiJudul, quillEditRekomendasiJudul;

        // Quill toolbar configuration
        const toolbarOptions = [
            ['bold', 'italic', 'underline'],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'header': [1, 2, 3, false]
            }],
            ['clean']
        ];

        $(document).ready(function() {
            // Initialize DataTable
            $('#rekomendasiTable').DataTable({
                "language": {
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "loadingRecords": "Sedang memuat...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 25,
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [4]
                }]
            });

            // Initialize Add Modal Editor
            quillRekomendasiJudul = new Quill('#editor_rekomendasi_judul', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // Initialize Edit Modal Editor
            quillEditRekomendasiJudul = new Quill('#editor_edit_rekomendasi_judul', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // Sync Quill content to hidden input on form submit (Add Modal)
            $('#addRekomendasiModal form').on('submit', function() {
                $('#rekomendasi_judul').val(quillRekomendasiJudul.root.innerHTML);
            });

            // Sync Quill content to hidden input on form submit (Edit Modal)
            $('#editRekomendasiForm').on('submit', function() {
                $('#edit_rekomendasi_judul').val(quillEditRekomendasiJudul.root.innerHTML);
            });

            // Clear Add Modal editor when modal is closed
            $('#addRekomendasiModal').on('hidden.bs.modal', function() {
                quillRekomendasiJudul.setContents([]);
            });

            // Set default date to today
            $('#rekomendasi_tgl').val(new Date().toISOString().split('T')[0]);
        });

        function editRekomendasi(id) {
            // Helper untuk normalisasi berbagai format tanggal ke YYYY-MM-DD
            const toInputDate = (val) => {
                if (!val) return '';
                if (typeof val === 'string') {
                    const s = val.trim();
                    // Format d/m/Y
                    if (s.includes('/')) {
                        const parts = s.split('/');
                        return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    }
                    // ISO atau dengan waktu: ambil 10 karakter pertama
                    if (s.includes('T') || s.includes(' ')) {
                        return s.substring(0, 10);
                    }
                    // Sudah YYYY-MM-DD
                    if (/^\d{4}-\d{2}-\d{2}$/.test(s)) {
                        return s;
                    }
                }
                try {
                    const d = new Date(val);
                    const yyyy = d.getFullYear();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}`;
                } catch {
                    return '';
                }
            };

            fetch(`/rekomendasi/${id}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async (response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    const ct = response.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        const preview = await response.text();
                        console.error('Response bukan JSON:', preview.slice(0, 300));
                        throw new Error('Response bukan JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    // Set Quill editor content for Edit Modal
                    quillEditRekomendasiJudul.root.innerHTML = data.rekomendasi_judul || '';
                    // Tanggal
                    $('#edit_rekomendasi_tgl').val(toInputDate(data.rekomendasi_tgl));
                    $('#edit_rekomendasi_tgl_deadline').val(toInputDate(data.rekomendasi_tgl_deadline));
                    // Temuan (conditional)
                    if ($('#edit_temuan_id').length) {
                        $('#edit_temuan_id').val(data.temuan_id || '');
                    }
                    // Unit fallback
                    const fallbackUnitId = {{ $pemeriksaan->unit_id }};
                    $('#edit_unit_id').val((data.unit_id ?? fallbackUnitId) || '');
                    // Status
                    $('#edit_rekomendasi_status').val(data.rekomendasi_status || 'aktif');
                    // Form action
                    $('#editRekomendasiForm').attr('action', `/rekomendasi/${id}`);
                    // Show modal
                    $('#editRekomendasiModal').modal('show');
                })
                .catch(error => {
                    console.error('Error ambil rekomendasi:', error);
                    alert('Terjadi kesalahan saat mengambil data rekomendasi');
                });
        }

        function lihatTindakLanjut(rekomendasiId) {
            // Redirect ke halaman tindak lanjut untuk rekomendasi tertentu
            window.location.href = `/tindak-lanjut/rekomendasi/${rekomendasiId}`;
        }

        function deleteRekomendasi(id) {
            if (confirm('Apakah Anda yakin ingin menghapus rekomendasi ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/rekomendasi/${id}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Toggle functionality for mention unit
        document.getElementById('toggleMentionUnit').addEventListener('change', function() {
            const unitDropdown = document.getElementById('unit_id');

            if (this.checked) {
                // Enable dropdown and show all options
                unitDropdown.disabled = false;
                unitDropdown.innerHTML = `
                <option value="">Pilih Unit</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->unit_id }}"
                        {{ $pemeriksaan->unit_id == $unit->unit_id ? 'selected' : '' }}>
                        {{ $unit->unit_nama }}
                    </option>
                @endforeach
            `;
            } else {
                // Disable dropdown and set to default unit from pemeriksaan
                unitDropdown.disabled = true;
                unitDropdown.innerHTML = `
                <option value="{{ $pemeriksaan->unit_id }}" selected>{{ $pemeriksaan->unit->unit_nama ?? 'Unit tidak ditemukan' }}</option>
            `;
            }
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(function() {
            // Ambil table
            const $table = $('#rekomendasiTable');

            // Inisialisasi atau ambil instance yang sudah ada, simpan global untuk dipakai ulang
            window.dtRekomendasi = $.fn.DataTable && $.fn.DataTable.isDataTable($table) ?
                $table.DataTable() :
                $table.DataTable({
                    "language": {
                        "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                        "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                        "lengthMenu": "Tampilkan _MENU_ entri",
                        "loadingRecords": "Sedang memuat...",
                        "processing": "Sedang memproses...",
                        "search": "Cari:",
                        "zeroRecords": "Tidak ditemukan data yang sesuai",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    },
                    "pageLength": 25,
                    "order": [
                        [1, "asc"]
                    ],
                    "columnDefs": [{
                        "orderable": false,
                        "targets": [4]
                    }]
                });

            const dtRekomendasi = window.dtRekomendasi;

            function $scopeRows() {
                return $(dtRekomendasi.rows({
                    search: 'applied'
                }).nodes());
            }

            function updateKirimButtonState() {
                const checkedCount = $scopeRows().find('.rek-check:checked').length;
                $('#btnKirimSelected').prop('disabled', checkedCount === 0);
            }

            function updatePublishButtonState() {
                const checkedCount = $scopeRows().find('.publish-check:checked').length;
                $('#btnPublishSelected').prop('disabled', checkedCount === 0);
            }

            function syncHeaderCheckbox() {
                const $rows = $scopeRows();
                const totalEnabled = $rows.find('.rek-check:not(:disabled)').length;
                const checkedEnabled = $rows.find('.rek-check:checked:not(:disabled)').length;
                $('#selectAllRekomendasi').prop('checked', totalEnabled > 0 && checkedEnabled === totalEnabled);
            }

            function syncPublishHeaderCheckbox() {
                const $rows = $scopeRows();
                const totalEnabled = $rows.find('.publish-check:not(:disabled)').length;
                const checkedEnabled = $rows.find('.publish-check:checked:not(:disabled)').length;
                $('#selectAllPublish').prop('checked', totalEnabled > 0 && checkedEnabled === totalEnabled);
            }

            function setAllCheckboxes(checked) {
                $scopeRows().find('.rek-check:not(:disabled)').prop('checked', checked);
                updateKirimButtonState();
                syncHeaderCheckbox();
            }

            function setAllPublishCheckboxes(checked) {
                $scopeRows().find('.publish-check:not(:disabled)').prop('checked', checked);
                updatePublishButtonState();
                syncPublishHeaderCheckbox();
            }

            // Cegah klik header checkbox memicu sort
            $(document).on('click', '#rekomendasiTable thead input#selectAllRekomendasi', function(e) {
                e.stopPropagation();
            });

            $(document).on('click', '#rekomendasiTable thead input#selectAllPublish', function(e) {
                e.stopPropagation();
            });

            // Inisialisasi state awal
            updateKirimButtonState();
            updatePublishButtonState();
            syncHeaderCheckbox();
            syncPublishHeaderCheckbox();

            // Select All (delegation agar aman saat header di-redraw)
            $(document).on('change', '#selectAllRekomendasi', function() {
                setAllCheckboxes(this.checked);
            });

            $(document).on('change', '#selectAllPublish', function() {
                setAllPublishCheckboxes(this.checked);
            });

            // Perubahan checkbox baris
            $(document).on('change', '.rek-check', function() {
                updateKirimButtonState();
                syncHeaderCheckbox();
            });

            $(document).on('change', '.publish-check', function() {
                updatePublishButtonState();
                syncPublishHeaderCheckbox();
            });

            // Sinkron saat tabel redraw (paging/filter)
            $table.on('draw.dt', function() {
                syncHeaderCheckbox();
                syncPublishHeaderCheckbox();
                updateKirimButtonState();
                updatePublishButtonState();
            });

            // Kirim batch: set rekomendasi_kirim = 'Y' untuk terpilih
            $('#btnKirimSelected').on('click', function() {
                const ids = $scopeRows().find('.rek-check:checked').map(function() {
                    return this.value;
                }).get();
                if (ids.length === 0) return;

                fetch('/rekomendasi/kirim-batch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids
                        })
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        alert(data.message || 'Berhasil mengirim rekomendasi terpilih');
                        window.location.reload();
                    })
                    .catch(async (err) => {
                        let msg = 'Terjadi kesalahan saat mengirim rekomendasi';
                        try {
                            const j = await err.json();
                            if (j && j.message) msg = j.message;
                        } catch (_) {}
                        alert(msg);
                    });
            });

            // Publish batch: set rekomendasi_publish_kabag = 'Y' untuk terpilih
            $('#btnPublishSelected').on('click', function() {
                const ids = $scopeRows().find('.publish-check:checked').map(function() {
                    return this.value;
                }).get();
                if (ids.length === 0) return;

                fetch('/rekomendasi/publish-batch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids
                        })
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        alert(data.message || 'Berhasil mempublish rekomendasi terpilih');
                        window.location.reload();
                    })
                    .catch(async (err) => {
                        let msg = 'Terjadi kesalahan saat mempublish rekomendasi';
                        try {
                            const j = await err.json();
                            if (j && j.message) msg = j.message;
                        } catch (_) {}
                        alert(msg);
                    });
            });
        });
    </script>
@endpush
