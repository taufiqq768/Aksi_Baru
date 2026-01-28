@extends('layouts.app')

@section('title', 'Kelola Temuan - ' . $pemeriksaan->pemeriksaan_judul)

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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
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


                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Temuan</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                data-bs-target="#addTemuanModal">
                                <i class="fas fa-plus"></i> Tambah Temuan
                            </button>
                            <a href="{{ route('temuan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="temuanDetailTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Temuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($temuan as $index => $item)
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td style="white-space: pre-line;">
                                                                            {{ strip_tags(
                                            str_replace(
                                                ['<br>', '<br/>', '<br />', '&nbsp;'],
                                                ["\n", "\n", "\n", ' '],
                                                html_entity_decode($item->temuan_judul)
                                            )
                                        ) }}
                                                                            <!-- {{ strip_tags($item->temuan_judul) }} -->
                                                                        </td>
                                                                        <td>
                                                                            @if ($item->temuan_kirim == 'Y')
                                                                                <span class="badge bg-success">Terkirim</span>
                                                                            @else
                                                                                <span class="badge bg-warning">Draft</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <!-- Kolom aksi pada setiap baris temuan -->
                                                                            <div class="btn-group" role="group">
                                                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                                                    onclick="detailTemuan({{ $item->temuan_id }})" title="Detail">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </button>
                                                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                                                    onclick="editTemuan({{ $item->temuan_id }})" title="Edit">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                                                    onclick="kelolaRekomendasi({{ $item->temuan_id }})"
                                                                                    title="Kelola Rekomendasi">
                                                                                    <i class="fas fa-tasks"></i>
                                                                                </button>
                                                                                <!-- Tombol Hapus Temuan -->
                                                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                                                    onclick="hapusTemuan({{ $item->temuan_id }})" title="Hapus">
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
    </div>

    <!-- Add Temuan Modal -->
    <div class="modal fade" id="addTemuanModal" tabindex="-1" aria-labelledby="addTemuanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTemuanModalLabel">Tambah Temuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('temuan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->pemeriksaan_id }}">
                    <div class="modal-body">
                        <!-- Baris 1: Tanggal Temuan dan Obyek Temuan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temuan_tgl" class="form-label">Tanggal Temuan</label>
                                    <input type="date" class="form-control" id="temuan_tgl" name="temuan_tgl"
                                        value="{{ $pemeriksaan->pemeriksaan_tgl_akhir ? \Carbon\Carbon::parse($pemeriksaan->pemeriksaan_tgl_akhir)->format('Y-m-d') : '' }}"
                                        readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temuan_obyek" class="form-label">Obyek Temuan</label>
                                    <input type="text" class="form-control" id="temuan_obyek" name="temuan_obyek"
                                        value="{{ $pemeriksaan->pemeriksaan_obyek }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2: Judul Temuan -->
                        <div class="mb-3">
                            <label for="temuan_judul" class="form-label">Judul Temuan</label>
                            <div id="editor_temuan_judul" style="min-height: 150px;"></div>
                            <input type="hidden" name="temuan_judul" id="temuan_judul" required>
                        </div>

                        <!-- Baris 3: Bidang Temuan dan Master Temuan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bidangtemuan_id" class="form-label">Bidang Temuan</label>
                                    <select class="form-control" id="bidangtemuan_id" name="bidangtemuan_id">
                                        <option value="">Pilih Bidang Temuan</option>
                                        @foreach ($bidangTemuan as $bidang)
                                            <option value="{{ $bidang->bidangtemuan_id }}">
                                                {{ $bidang->bidangtemuan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="temu_id" class="form-label">Master Temuan</label>
                                    <select class="form-control" id="temu_id" name="temu_id">
                                        <option value="">Pilih Master Temuan</option>
                                        @foreach ($masterTemuan as $temu)
                                            <option value="{{ $temu->temu_id }}">{{ $temu->kode_temuan }} -
                                                {{ $temu->klasifikasi_temuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4: Master COSO dan Master AB -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="coso_id" class="form-label">Master COSO</label>
                                    <select class="form-control" id="coso_id" name="coso_id">
                                        <option value="">Pilih Master COSO</option>
                                        @foreach ($masterCoso as $coso)
                                            <option value="{{ $coso->coso_id }}">{{ $coso->kode_coso }} -
                                                {{ $coso->klasifikasi_coso }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_klasifikasi_ab" class="form-label">Master AB</label>
                                    <select class="form-control" id="id_klasifikasi_ab" name="id_klasifikasi_ab">
                                        <option value="">Pilih Master AB</option>
                                        @foreach ($masterAb as $ab)
                                            <option value="{{ $ab->id_ab }}">{{ $ab->kode_ab }} -
                                                {{ $ab->judul_ab }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 5: Penyebab -->
                        <div class="mb-3">
                            <label for="penyebab" class="form-label">Penyebab</label>
                            <div id="editor_penyebab" style="min-height: 150px;"></div>
                            <input type="hidden" name="penyebab" id="penyebab">
                        </div>

                        <!-- Baris 6: Master Penyebab -->
                        <div class="mb-3">
                            <label for="sebab_id" class="form-label">Master Penyebab</label>
                            <select class="form-control" id="sebab_id" name="sebab_id">
                                <option value="">Pilih Master Penyebab</option>
                                @foreach ($masterSebab as $sebab)
                                    <option value="{{ $sebab->sebab_id }}">{{ $sebab->sebab_kode }} -
                                        {{ $sebab->klasifikasi_sebab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Baris 7: Kriteria Temuan -->
                        <div class="mb-3">
                            <label for="temuan_kriteria" class="form-label">Kriteria Temuan</label>
                            <div id="editor_temuan_kriteria" style="min-height: 150px;"></div>
                            <input type="hidden" name="temuan_kriteria" id="temuan_kriteria">
                        </div>

                        <!-- Nominal -->
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal">
                        </div>

                        <!-- PMR Sebelumnya (Hidden field with default value) -->
                        <input type="hidden" id="temuan_pmr_sebelumnya" name="temuan_pmr_sebelumnya" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Temuan Modal -->
    <div class="modal fade" id="editTemuanModal" tabindex="-1" aria-labelledby="editTemuanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTemuanModalLabel">Edit Temuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTemuanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Baris 1: Tanggal Temuan dan Obyek Temuan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_temuan_tgl" class="form-label">Tanggal Temuan</label>
                                    <input type="date" class="form-control" id="edit_temuan_tgl" name="temuan_tgl" readonly
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_temuan_obyek" class="form-label">Obyek Temuan</label>
                                    <input type="text" class="form-control" id="edit_temuan_obyek" name="temuan_obyek">
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2: Judul Temuan -->
                        <div class="mb-3">
                            <label for="edit_temuan_judul" class="form-label">Judul Temuan</label>
                            <div id="editor_edit_temuan_judul" style="min-height: 150px;"></div>
                            <input type="hidden" name="temuan_judul" id="edit_temuan_judul" required>
                        </div>

                        <!-- Baris 3: Bidang Temuan dan Master Temuan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_bidangtemuan_id" class="form-label">Bidang Temuan</label>
                                    <select class="form-control" id="edit_bidangtemuan_id" name="bidangtemuan_id">
                                        <option value="">Pilih Bidang Temuan</option>
                                        @foreach ($bidangTemuan as $bidang)
                                            <option value="{{ $bidang->bidangtemuan_id }}">
                                                {{ $bidang->bidangtemuan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_temu_id" class="form-label">Master Temuan</label>
                                    <select class="form-control" id="edit_temu_id" name="temu_id">
                                        <option value="">Pilih Master Temuan</option>
                                        @foreach ($masterTemuan as $temu)
                                            <option value="{{ $temu->temu_id }}">{{ $temu->kode_temuan }} -
                                                {{ $temu->klasifikasi_temuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4: Master COSO dan Master AB -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_coso_id" class="form-label">Master COSO</label>
                                    <select class="form-control" id="edit_coso_id" name="coso_id">
                                        <option value="">Pilih Master COSO</option>
                                        @foreach ($masterCoso as $coso)
                                            <option value="{{ $coso->coso_id }}">{{ $coso->kode_coso }} -
                                                {{ $coso->klasifikasi_coso }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_id_klasifikasi_ab" class="form-label">Master AB</label>
                                    <select class="form-control" id="edit_id_klasifikasi_ab" name="id_klasifikasi_ab">
                                        <option value="">Pilih Master AB</option>
                                        @foreach ($masterAb as $ab)
                                            <option value="{{ $ab->id_ab }}">{{ $ab->kode_ab }} -
                                                {{ $ab->judul_ab }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 5: Penyebab -->
                        <div class="mb-3">
                            <label for="edit_penyebab" class="form-label">Penyebab</label>
                            <div id="editor_edit_penyebab" style="min-height: 150px;"></div>
                            <input type="hidden" name="penyebab" id="edit_penyebab">
                        </div>

                        <!-- Baris 6: Master Penyebab -->
                        <div class="mb-3">
                            <label for="edit_sebab_id" class="form-label">Master Penyebab</label>
                            <select class="form-control" id="edit_sebab_id" name="sebab_id">
                                <option value="">Pilih Master Penyebab</option>
                                @foreach ($masterSebab as $sebab)
                                    <option value="{{ $sebab->sebab_id }}">{{ $sebab->sebab_kode }} -
                                        {{ $sebab->klasifikasi_sebab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Baris 7: Kriteria Temuan -->
                        <div class="mb-3">
                            <label for="edit_temuan_kriteria" class="form-label">Kriteria Temuan</label>
                            <div id="editor_edit_temuan_kriteria" style="min-height: 150px;"></div>
                            <input type="hidden" name="temuan_kriteria" id="edit_temuan_kriteria">
                        </div>

                        <!-- Nominal -->
                        <div class="mb-3">
                            <label for="edit_nominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="edit_nominal" name="nominal">
                        </div>

                        <!-- PMR Sebelumnya (Hidden field) -->
                        <input type="hidden" id="edit_temuan_pmr_sebelumnya" name="temuan_pmr_sebelumnya" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Temuan Modal -->
    <div class="modal fade" id="detailTemuanModal" tabindex="-1" aria-labelledby="detailTemuanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailTemuanModalLabel">Detail Temuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailTemuanContent">
                    <!-- Detail content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Quill.js JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        #detailTemuanModal .modal-dialog {
            max-width: 95vw;
        }

        .ql-editor {
            min-height: 150px;
        }
    </style>
    <script>
        // Initialize Quill editors
        let quillTemuanJudul, quillPenyebab, quillTemuanKriteria;
        let quillEditTemuanJudul, quillEditPenyebab, quillEditTemuanKriteria;

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

        $(document).ready(function () {

            // Initialize DataTable
            $('#temuanDetailTable').DataTable({
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
                    [1, "desc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [3]
                }]
            });

            // Initialize Add Modal Editors
            quillTemuanJudul = new Quill('#editor_temuan_judul', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillPenyebab = new Quill('#editor_penyebab', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillTemuanKriteria = new Quill('#editor_temuan_kriteria', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // Initialize Edit Modal Editors
            quillEditTemuanJudul = new Quill('#editor_edit_temuan_judul', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillEditPenyebab = new Quill('#editor_edit_penyebab', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillEditTemuanKriteria = new Quill('#editor_edit_temuan_kriteria', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // Sync Quill content to hidden inputs on form submit (Add Modal)
            $('#addTemuanModal form').on('submit', function () {
                $('#temuan_judul').val(quillTemuanJudul.root.innerHTML);
                $('#penyebab').val(quillPenyebab.root.innerHTML);
                $('#temuan_kriteria').val(quillTemuanKriteria.root.innerHTML);
            });

            // Sync Quill content to hidden inputs on form submit (Edit Modal)
            $('#editTemuanForm').on('submit', function () {
                $('#edit_temuan_judul').val(quillEditTemuanJudul.root.innerHTML);
                $('#edit_penyebab').val(quillEditPenyebab.root.innerHTML);
                $('#edit_temuan_kriteria').val(quillEditTemuanKriteria.root.innerHTML);
            });

            // Clear Add Modal editors when modal is closed
            $('#addTemuanModal').on('hidden.bs.modal', function () {
                quillTemuanJudul.setContents([]);
                quillPenyebab.setContents([]);
                quillTemuanKriteria.setContents([]);
            });
        });

        // Define functions in global scope
        function detailTemuan(id) {
            $.getJSON(`/temuan/${id}`, function (data) {
                console.log('Detail temuan:', data);

                const tanggal = data.temuan_tgl ? new Date(data.temuan_tgl) : null;

                // Tulis ke ID yang benar di modal-body
                $('#detailTemuanContent').html(`
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-2 pb-2 border-bottom">
                                                <strong>Judul Temuan:</strong><br>
                                                <div>${data.temuan_judul || 'N/A'}</div>
                                            </div>
                                            <div class="mb-2 pb-2 border-bottom">
                                                <strong>Nominal:</strong><br>
                                                ${data.nominal ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.nominal) : 'N/A'}
                                            </div>
                                            <div class="mb-2 pb-2 border-bottom">
                                                <strong>Penyebab:</strong><br>
                                                <div>${data.penyebab || 'N/A'}</div>
                                            </div>
                                            <div class="mb-2 pb-2 border-bottom">
                                                <strong>Kriteria:</strong><br>
                                                <div>${data.temuan_kriteria || 'N/A'}</div>
                                            </div>
                                        </div>
                                    </div>
                                `);

                // Tampilkan modal
                const modalEl = document.getElementById('detailTemuanModal');
                if (window.bootstrap && modalEl) {
                    new bootstrap.Modal(modalEl).show();
                } else {
                    $('#detailTemuanModal').modal('show');
                }
            }).fail(function () {
                alert('Gagal memuat detail temuan');
            });
        }

        function editTemuan(id) {
            $.get(`/temuan/${id}/edit`, function (data) {
                console.log('Data received:', data); // Debug log

                // Populate edit form with data
                $('#edit_temuan_obyek').val(data.temuan_obyek);

                // Format date for HTML input (YYYY-MM-DD)
                if (data.temuan_tgl) {
                    let date = new Date(data.temuan_tgl);
                    let formattedDate = date.toISOString().split('T')[0];
                    $('#edit_temuan_tgl').val(formattedDate);
                }

                $('#edit_nominal').val(data.nominal);

                // Set Quill editor content for Edit Modal
                quillEditTemuanJudul.root.innerHTML = data.temuan_judul || '';
                quillEditPenyebab.root.innerHTML = data.penyebab || '';
                quillEditTemuanKriteria.root.innerHTML = data.temuan_kriteria || '';

                // Set dropdown values
                $('#edit_bidangtemuan_id').val(data.bidangtemuan_id);
                $('#edit_temu_id').val(data.temu_id);
                $('#edit_coso_id').val(data.coso_id);
                $('#edit_id_klasifikasi_ab').val(data.id_klasifikasi_ab);
                $('#edit_sebab_id').val(data.sebab_id);
                $('#edit_temuan_pmr_sebelumnya').val(data.temuan_pmr_sebelumnya || 0);

                $('#editTemuanForm').attr('action', `/temuan/${id}`);
                $('#editTemuanModal').modal('show');
            }).fail(function () {
                alert('Gagal memuat data temuan');
            });
        }

        function kelolaRekomendasi(id) {
            // Redirect ke halaman kelola rekomendasi untuk temuan tertentu
            window.location.href = "{{ url('rekomendasi/kelola-rekomendasi') }}/" + id;
        }

        // function kelolaTindakLanjut(id) {
        //     // Implementasi kelola tindak lanjut
        //     alert('Kelola tindak lanjut untuk temuan ID: ' + id);
        // }

        function hapusTemuan(id) {
            if (!confirm('Yakin ingin menghapus temuan ini? Tindakan tidak dapat dibatalkan.')) {
                return;
            }

            $.ajax({
                url: `/temuan/${id}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    // Setelah hapus, refresh halaman agar daftar temuan terupdate
                    location.reload();
                },
                error: function (xhr) {
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : xhr
                        .statusText;
                    alert('Gagal menghapus temuan: ' + msg);
                }
            });
        }

        // // Initialize DataTable when document is ready
        // $(document).ready(function () {
        //     $('#temuanDetailTable').DataTable({
        //         responsive: true,
        //         language: {
        //             url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
        //         }
        //     });
        // });
    </script>
@endpush