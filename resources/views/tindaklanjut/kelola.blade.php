@extends('layouts.app')

@push('styles')
    <!-- Quill.js CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <style>
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

        .header-gradient-magenta {
            background: linear-gradient(135deg, #e43888 0%, #741616 100%);
        }
    </style>

     @if (auth()->user()->user_level === 'operator' || auth()->user()->user_level === 'verifikator')
    <style>
        #btnTanggapanTL { display: none; }
    </style>
    @endif

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2>Tindak Lanjut Rekomendasi</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('rekomendasi.kelola', $rekomendasi->pemeriksaan_id) }}">
                                        Kelola Rekomendasi
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Tindak Lanjut</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('rekomendasi.kelola', $rekomendasi->pemeriksaan_id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Info Pemeriksaan -->
                <div class="info-card">
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Judul</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $rekomendasi->pemeriksaan->pemeriksaan_judul ?? '-' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Auditee</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $rekomendasi->pemeriksaan->unit->unit_nama ?? 'Tidak ada unit' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <strong>Periode</strong>
                        </div>
                        <div class="col-md-11">
                            {{ $rekomendasi->pemeriksaan->pemeriksaan_tgl_mulai ? $rekomendasi->pemeriksaan->pemeriksaan_tgl_mulai->format('d/m/Y') : '-' }}
                            -
                            {{ $rekomendasi->pemeriksaan->pemeriksaan_tgl_akhir ? $rekomendasi->pemeriksaan->pemeriksaan_tgl_akhir->format('d/m/Y') : '-' }}
                        </div>
                    </div>
                </div>

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
                                        html_entity_decode($rekomendasi->temuan->temuan_judul)
                                    )
                                ) }}
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Info Rekomendasi -->
                <div class="accordion mb-3" id="rekomendasiAccordion">
                    <div class="accordion-item">
                        <!-- HEADER (selalu tampil) -->
                        <h6 class="accordion-header mb-0" id="headingRekomendasi">
                            <button class="accordion-button collapsed header-gradient-magenta text-black"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseRekomendasi"
                                    aria-expanded="false"
                                    aria-controls="collapseRekomendasi">
                                Rekomendasi
                            </button>
                        </h6>

                        <!-- YANG DI-COLLAPSE: BODY + FOOTER -->
                        <div id="collapseRekomendasi"
                            class="accordion-collapse collapse"
                            aria-labelledby="headingRekomendasi"
                            data-bs-parent="#rekomendasiAccordion">

                            <!-- BODY -->
                            <div class="accordion-body">
                                <div class="row align-items-center" style="white-space: pre-line;">
                                    {{ strip_tags(
                                        str_replace(
                                            ['<br>', '<br/>', '<br />', '&nbsp;'],
                                            ["\n", "\n", "\n", ' '],
                                            html_entity_decode($rekomendasi->rekomendasi_judul)
                                        )
                                    ) }}
                                </div>
                            </div>

                            <!-- FOOTER -->
                            <div class="accordion-footer p-3" style="background-color: #dddddfff;">
                                <div class="row mb-1">
                                    <div class="col-md-2 fw-bold">Deadline Penyelesaian</div>
                                    <div class="col-md-10">
                                        {{ $rekomendasi->rekomendasi_tgl_deadline
                                            ? $rekomendasi->rekomendasi_tgl_deadline->format('d/m/Y')
                                            : '-' }}
                                        @if ($rekomendasi->is_overdue)
                                            <span class="badge bg-danger ms-2">Terlambat</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-1">
                                    <div class="col-md-2 fw-bold">Status</div>
                                    <div class="col-md-10">
                                        @switch($rekomendasi->rekomendasi_status)
                                            @case('Belum di Tindak Lanjut')
                                                <span class="badge bg-danger">Belum di Tindak Lanjut</span>
                                                @break

                                            @case('Belum Sesuai')
                                                <span class="badge bg-warning">Belum Sesuai</span>
                                                @break

                                            @case('Sesuai')
                                                <span class="badge bg-primary">Sesuai</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



                <!-- Daftar Tindak Lanjut -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Tindak Lanjut</h5>
                        <div class="d-flex gap-2">

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addTindaklanjutModal" {{ $tindakLanjut->isNotEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i> Tambah Tindak Lanjut
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tlTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <th>Lampiran Dokumen</th>
                                        <th>Link Dokumen</th>
                                        <th>Status Kirim</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tindakLanjut as $index => $tl)
                                        <tr>
                                            {{-- <td>{{ $index + 1 }}</td> --}}
                                            <!-- <td>{{ $tl->tl_deskripsi }}</td> -->
                                            <td style="white-space: pre-line;">
                                            {{ strip_tags(
                                                str_replace(
                                                    ['<br>', '<br/>', '<br />', '&nbsp;'],
                                                    ["\n", "\n", "\n", ' '],
                                                    html_entity_decode($tl->tl_deskripsi)
                                                )
                                            ) }}
                                            </td>
                                            {{-- Di dalam tabel kolom Lampiran Dokumen --}}
                                            <td>
                                                {{-- Ambil semua upload untuk tl yang sedang dirender --}}
                                                @php $uploads = $uploadTls->where('tl_id', $tl->tl_id); @endphp
                                                @if ($uploads->isNotEmpty())
                                                    @foreach ($uploads as $upload)
                                                        <a href="{{ route('tl.lampiran', $upload->uploadtl_id) }}"
                                                            target="_blank">
                                                            <i class="fas fa-file"></i> {{ $upload->uploadtl_nama }}
                                                        </a>
                                                        @if (!$loop->last)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tl->tl_link)
                                                    <a href="{{ $tl->tl_link }}" target="_blank">
                                                        <i class="fas fa-link"></i> Buka
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tl->tl_publish_verif === 'N' && $tl->tl_publish_spi === 'N')
                                                    <span class="badge bg-danger">Belum dikirim</span>
                                                @elseif ($tl->tl_publish_verif === 'Y' && $tl->tl_publish_spi === 'N')
                                                    <span class="badge bg-warning">Terkirim ke Verifikator</span>
                                                @elseif ($tl->tl_publish_verif === 'Y' && $tl->tl_publish_spi === 'Y')
                                                    <span class="badge bg-success">Terkirim ke Auditor</span>
                                                @else
                                                    <span class="badge bg-danger">Belum dikirim</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="kirimTindakLanjut('{{ $tl->tl_id }}')" title="Kirim"
                                                    {{ $tl->tl_publish_verif == 'Y' ? 'disabled' : '' }}>
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                                <button type="button" id="btnTanggapanTL" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#tanggapanTindaklanjutModal"
                                                    data-tl-id="{{ $tl->tl_id }}" title="Tanggapan Auditor">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal" data-bs-target="#editTindaklanjutModal"
                                                    data-tl-id="{{ $tl->tl_id }}" title="Edit (Auditee)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete('{{ $tl->tl_id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Biarkan kosong, DataTables akan menampilkan emptyTable bila diaktifkan --}}
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
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
        let quillTlDeskripsi, quillEditTlDeskripsi, quillTlTanggapan;

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

        window.addEventListener('load', function() {
            // Initialize Quill editors
            quillTlDeskripsi = new Quill('#editor_tl_deskripsi', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillEditTlDeskripsi = new Quill('#editor_edit_tl_deskripsi', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillTlTanggapan = new Quill('#editor_tl_tanggapan', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });

            // Sync Quill content to hidden input on form submit (Add Modal)
            document.getElementById('addTindaklanjutForm').addEventListener('submit', function() {
                document.getElementById('tl_deskripsi').value = quillTlDeskripsi.root.innerHTML;
            });

            // Sync Quill content to hidden input on form submit (Edit Modal)
            document.getElementById('editTindaklanjutForm').addEventListener('submit', function() {
                document.getElementById('edit_tl_deskripsi').value = quillEditTlDeskripsi.root.innerHTML;
            });

            // Sync Quill content to hidden input on form submit (Tanggapan Modal)
            document.getElementById('tanggapanTindaklanjutForm').addEventListener('submit', function() {
                document.getElementById('tl_tanggapan').value = quillTlTanggapan.root.innerHTML;
            });

            // Clear Add Modal editor when modal is closed
            document.getElementById('addTindaklanjutModal').addEventListener('hidden.bs.modal', function() {
                quillTlDeskripsi.setContents([]);
            });

            // Clear Tanggapan Modal editor when modal is closed
            document.getElementById('tanggapanTindaklanjutModal').addEventListener('hidden.bs.modal', function() {
                quillTlTanggapan.setContents([]);
            });

            if (window.jQuery && $.fn && $.fn.DataTable) {
                $('#tlTable').DataTable({
                    language: {
                        emptyTable: 'Belum ada tindak lanjut untuk rekomendasi ini.',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 entri',
                        infoFiltered: '(disaring dari _MAX_ entri keseluruhan)',
                        lengthMenu: 'Tampilkan _MENU_ entri',
                        loadingRecords: 'Sedang memuat...',
                        processing: 'Sedang memproses...',
                        search: 'Cari:',
                        zeroRecords: 'Tidak ditemukan data yang sesuai',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        }
                    },
                    pageLength: 25,
                    order: [
                        [0, 'asc']
                    ] // kolom Tanggal
                });
            } else {
                console.warn('jQuery/DataTables tidak tersedia; tabel ditampilkan tanpa fitur.');
            }
        });

        // Kirim TL tanpa jQuery
        function kirimTindakLanjut(id) {
            if (!confirm('Apakah Anda yakin ingin mengirim tindak lanjut ini?')) return;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch('/tindak-lanjut/' + id + '/publish-verif', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(response => {
                    if (response.message === 'Berhasil dikirim') {
                        location.reload();
                    } else {
                        alert(response.message || 'Gagal mengirim.');
                    }
                })
                .catch(err => {
                    console.error('Kirim TL gagal:', err);
                    alert('Terjadi kesalahan saat mengirim. Coba lagi.');
                });
        }
    </script>
@endpush

<!-- Tambah Tindak Lanjut (Auditee) -->
<div class="modal fade" id="addTindaklanjutModal" tabindex="-1" aria-labelledby="addTindaklanjutModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTindaklanjutModalLabel">Tambah Tindak Lanjut (Auditee)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="addTindaklanjutForm" method="post" enctype="multipart/form-data"
                action="{{ route('tl.store') }}">
                @csrf
                <input type="hidden" name="rekomendasi_id" value="{{ $rekomendasi->rekomendasi_id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <div id="editor_tl_deskripsi" style="min-height: 150px;"></div>
                        <input type="hidden" name="tl_deskripsi" id="tl_deskripsi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran Dokumen</label>
                        <input type="file" class="form-control" name="tl_lampiran"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Dokumen</label>
                        <input type="url" class="form-control" name="tl_link" placeholder="https://...">
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

<!-- Tanggapan Tindak Lanjut (Auditor) -->
<div class="modal fade" id="tanggapanTindaklanjutModal" tabindex="-1"
    aria-labelledby="tanggapanTindaklanjutModalLabel" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tanggapanTindaklanjutModalLabel">Tanggapan Tindak Lanjut (Auditor)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="tanggapanTindaklanjutForm" method="post" action="">
                @csrf
                <input type="hidden" name="tl_id" id="tanggapan_tl_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggapan Auditor</label>
                        <div id="editor_tl_tanggapan" style="min-height: 150px;"></div>
                        <input type="hidden" name="tl_tanggapan" id="tl_tanggapan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Tindak Lanjut</label>
                        <select class="form-select" name="tl_status" required>
                            <option value="Belum di Tindak Lanjut">Belum di Tindak Lanjut</option>
                            <option value="Belum Sesuai">Belum Sesuai</option>
                            <option value="Sesuai">Sesuai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Tanggapan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tindak Lanjut (Auditee) -->
<div class="modal fade" id="editTindaklanjutModal" tabindex="-1" aria-labelledby="editTindaklanjutModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTindaklanjutModalLabel">Edit Tindak Lanjut (Auditee)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="editTindaklanjutForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tl_id" id="edit_tl_id">
                <div class="modal-body" id="editTindaklanjutContent">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <div id="editor_edit_tl_deskripsi" style="min-height: 150px;"></div>
                        <input type="hidden" name="tl_deskripsi" id="edit_tl_deskripsi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Dokumen</label>
                        <input type="url" class="form-control" name="tl_link" id="edit_tl_link"
                            placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran Dokumen</label>
                        <input type="file" class="form-control" name="tl_lampiran"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <div id="editTlFileName" class="form-text text-muted"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Daftar Lampiran</label>
                        <div id="editTlUploads" class="border rounded p-2">
                            <div class="text-muted">Memuat lampiran...</div>
                        </div>
                    </div>
                    <!-- Tambah field lain sesuai kebutuhan -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Binding tl_id dari tombol ke modal auditor/edit
        document.getElementById('tanggapanTindaklanjutModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const tlId = button?.getAttribute('data-tl-id');
            document.getElementById('tanggapan_tl_id').value = tlId || '';
            const tanggapanForm = document.getElementById('tanggapanTindaklanjutForm');
            tanggapanForm.action = tlId ? ('/tindak-lanjut/' + tlId + '/tanggapan') : '';
        });

        document.getElementById('editTindaklanjutModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const tlId = button?.getAttribute('data-tl-id') || '';
            document.getElementById('edit_tl_id').value = tlId;

            const editForm = document.getElementById('editTindaklanjutForm');
            editForm.action = tlId ? ('/tindak-lanjut/' + tlId) : '';

            const uploadsBox = document.getElementById('editTlUploads');
            uploadsBox.innerHTML = '<div class="text-muted">Memuat lampiran...</div>';

            if (!tlId) {
                console.warn('tl_id tidak tersedia untuk edit modal.');
                uploadsBox.innerHTML = '<div class="text-muted">Tidak ada lampiran.</div>';
                return;
            }

            fetch('/tindak-lanjut/' + tlId, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(tl => {
                    // Set Quill editor content for Edit Modal
                    quillEditTlDeskripsi.root.innerHTML = tl.tl_deskripsi || '';
                    document.getElementById('edit_tl_link').value = tl.tl_link || '';

                    const uploads = tl.uploads || [];
                    if (!uploads.length) {
                        uploadsBox.innerHTML = '<div class="text-muted">Tidak ada lampiran.</div>';
                    } else {
                        const html = uploads.map(u =>
                            `<a href="/lampiran/tl/${u.uploadtl_id}" target="_blank">
                            <i class="fas fa-file"></i> ${u.uploadtl_nama}
                         </a>`
                        ).join('<br>');
                        uploadsBox.innerHTML = html;
                    }
                })
                .catch(err => {
                    console.error('Gagal memuat data TL:', err);
                    uploadsBox.innerHTML = '<div class="text-danger">Gagal memuat lampiran.</div>';
                });
        });

        // Preview nama file yang dipilih (Add)
        const addFileInput = document.querySelector('#addTindaklanjutModal input[name="tl_lampiran"]');
        if (addFileInput) {
            addFileInput.addEventListener('change', function(e) {
                const name = e.target.files?.[0]?.name || '';
                document.getElementById('addTlFileName').textContent = name;
            });
        }

        // Preview nama file yang dipilih (Edit)
        const editFileInput = document.querySelector('#editTindaklanjutModal input[name="tl_lampiran"]');
        if (editFileInput) {
            editFileInput.addEventListener('change', function(e) {
                const name = e.target.files?.[0]?.name || '';
                document.getElementById('editTlFileName').textContent = name;
            });
        }

        // Isi modal Edit: deskripsi, link, dan daftar lampiran
        document.getElementById('editTindaklanjutModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const tlId = button?.getAttribute('data-tl-id') || '';
            document.getElementById('edit_tl_id').value = tlId;

            const editForm = document.getElementById('editTindaklanjutForm');
            editForm.action = tlId ? ('/tindak-lanjut/' + tlId) : '';

            const uploadsBox = document.getElementById('editTlUploads');
            uploadsBox.innerHTML = '<div class="text-muted">Memuat lampiran...</div>';

            if (!tlId) {
                console.warn('tl_id tidak tersedia untuk edit modal.');
                uploadsBox.innerHTML = '<div class="text-muted">Tidak ada lampiran.</div>';
                return;
            }

            fetch('/tindak-lanjut/' + tlId, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(tl => {
                    // Set Quill editor content for Edit Modal
                    quillEditTlDeskripsi.root.innerHTML = tl.tl_deskripsi || '';
                    document.getElementById('edit_tl_link').value = tl.tl_link || '';

                    const uploads = tl.uploads || [];
                    if (!uploads.length) {
                        uploadsBox.innerHTML = '<div class="text-muted">Tidak ada lampiran.</div>';
                    } else {
                        const html = uploads.map(u =>
                            `<a href="/lampiran/tl/${u.uploadtl_id}" target="_blank">
        <i class="fas fa-file"></i> ${u.uploadtl_nama}
    </a>`
                        ).join('<br>');
                        uploadsBox.innerHTML = html;
                    }
                })
                .catch(err => {
                    console.error('Gagal memuat data TL:', err);
                    uploadsBox.innerHTML = '<div class="text-danger">Gagal memuat lampiran.</div>';
                });
        });
    </script>
@endpush
