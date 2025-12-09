@extends('layouts.app')

@section('title', 'Daftar Rekomendasi Pemeriksaan')

@section('content')
<style>
    .table-responsive {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card h3 {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }
    
    .stats-card p {
        margin: 5px 0 0 0;
        opacity: 0.9;
    }
    
    #rekomendasiTable {
        font-size: 0.85rem;
    }
    
    .modal-body {
        font-size: 0.9rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin-left: 2px;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: white !important;
        border-color: #007bff;
    }
    
    .form-control {
        font-size: 0.875rem;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Daftar Rekomendasi Pemeriksaan</h2>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <h3>{{ $pemeriksaan->count() }}</h3>
                        <p>Total Pemeriksaan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h3>{{ $pemeriksaan->sum(function($p) { return $p->rekomendasi->count(); }) }}</h3>
                        <p>Total Rekomendasi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <h3>{{ $pemeriksaan->filter(function($p) { return $p->rekomendasi->count() > 0; })->count() }}</h3>
                        <p>Pemeriksaan dengan Rekomendasi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <h3>{{ $pemeriksaan->filter(function($p) { return $p->lha; })->count() }}</h3>
                        <p>LHA Tersedia</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Rekomendasi Pemeriksaan -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Rekomendasi Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="rekomendasiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Pemeriksaan</th>
                                    <th>Unit</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Rekomendasi</th>
                                    <th>Status LHA</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pemeriksaan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->pemeriksaan_judul }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->pemeriksaan_objek }}</small>
                                    </td>
                                    <td>
                                        @if($item->unit)
                                            <span class="badge bg-info">{{ $item->unit->unit_nama }}</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada unit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            <strong>Mulai:</strong> {{ $item->pemeriksaan_tgl_mulai ? $item->pemeriksaan_tgl_mulai->format('d/m/Y') : '-' }}<br>
                                            <strong>Akhir:</strong> {{ $item->pemeriksaan_tgl_akhir ? $item->pemeriksaan_tgl_akhir->format('d/m/Y') : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($item->rekomendasi->count() > 0)
                                            <span class="badge bg-success">{{ $item->rekomendasi->count() }} Rekomendasi</span>
                                        @else
                                            <span class="badge bg-warning">Belum ada rekomendasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->lha)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> LHA Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> LHA Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->rekomendasi->count() > 0)
                                            <a href="{{ route('rekomendasi.kelola', $item->pemeriksaan_id) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-cogs"></i> Kelola Rekomendasi
                                            </a>
                                        @else
                                            <a href="{{ route('rekomendasi.create', ['pemeriksaan_id' => $item->pemeriksaan_id]) }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i> Tambah Rekomendasi
                                            </a>
                                        @endif
                                        
                                        @if(!$item->lha)
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#uploadLhaModal" 
                                                    data-pemeriksaan-id="{{ $item->pemeriksaan_id }}"
                                                    data-pemeriksaan-judul="{{ $item->pemeriksaan_judul }}">
                                                <i class="fas fa-upload"></i> Upload LHA
                                            </button>
                                        @endif
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

<!-- Modal Upload LHA -->
<div class="modal fade" id="uploadLhaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload LHA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('lha.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_pemeriksaan" id="lha_pemeriksaan_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Pemeriksaan</label>
                        <input type="text" class="form-control" id="lha_pemeriksaan_judul" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lha_nomor" class="form-label">Nomor LHA</label>
                        <input type="text" class="form-control" name="lha_nomor" id="lha_nomor" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lha_tanggal" class="form-label">Tanggal LHA</label>
                        <input type="date" class="form-control" name="lha_tanggal" id="lha_tanggal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lha_file" class="form-label">File LHA (PDF)</label>
                        <input type="file" class="form-control" name="lha_file" id="lha_file" accept=".pdf" required>
                        <small class="text-muted">Format: PDF, Maksimal 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload LHA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#rekomendasiTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 25,
        "order": [[ 3, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ]
    });
    
    // Handle Upload LHA Modal
    $('#uploadLhaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var pemeriksaanId = button.data('pemeriksaan-id');
        var pemeriksaanJudul = button.data('pemeriksaan-judul');
        
        var modal = $(this);
        modal.find('#lha_pemeriksaan_id').val(pemeriksaanId);
        modal.find('#lha_pemeriksaan_judul').val(pemeriksaanJudul);
    });
});
</script>
@endpush