@extends('layouts.app')

@section('title', 'Monitoring Tindak Lanjut')

@section('content')
    <style>
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 12px 8px;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .table td {
            padding: 10px 8px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            font-weight: 500;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-item {
            text-align: center;
        }

        .stats-item h3 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stats-item p {
            font-size: 0.9rem;
            margin: 0;
            opacity: 0.9;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2>Monitoring Tindak Lanjut</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Laporan</li>
                                <li class="breadcrumb-item active">Monitoring Tindak Lanjut</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="stats-card">
                    <div class="row">
                        <div class="col-md-2 stats-item">
                            <h3>{{ count($monitoringData) }}</h3>
                            <p>Total Unit</p>
                        </div>
                        <div class="col-md-2 stats-item">
                            <h3>{{ array_sum(array_column($monitoringData, 'jumlah_temuan')) }}</h3>
                            <p>Total Temuan</p>
                        </div>
                        <div class="col-md-2 stats-item">
                            <h3>{{ array_sum(array_column($monitoringData, 'jumlah_rekomendasi')) }}</h3>
                            <p>Total Rekomendasi</p>
                        </div>
                        <div class="col-md-2 stats-item">
                            <h3>{{ array_sum(array_column($monitoringData, 'jumlah_tindak_lanjut')) }}</h3>
                            <p>Total Tindak Lanjut</p>
                        </div>
                        <div class="col-md-2 stats-item">
                            <h3>{{ array_sum(array_column($monitoringData, 'status_belum_ditindaklanjuti')) }}</h3>
                            <p>Belum Ditindaklanjuti</p>
                        </div>
                        <div class="col-md-2 stats-item">
                            <h3>{{ array_sum(array_column($monitoringData, 'status_sesuai')) }}</h3>
                            <p>Sesuai</p>
                        </div>
                    </div>
                </div>

                <!-- Monitoring Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Monitoring Tindak Lanjut per Unit</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="monitoringTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Unit Kerja</th>
                                        <th class="text-center">Jumlah Temuan</th>
                                        <th class="text-center">Jumlah Rekomendasi</th>
                                        <th class="text-center">Jumlah Tindak Lanjut</th>
                                        <th class="text-center">Belum Ditindaklanjuti</th>
                                        <th class="text-center">Belum Sesuai</th>
                                        <th class="text-center">Sesuai</th>
                                        <th class="text-center">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monitoringData as $index => $data)
                                        @php
                                            $totalRekomendasi = $data['jumlah_rekomendasi'];
                                            $progress = $totalRekomendasi > 0
                                                ? round(($data['status_sesuai'] / $totalRekomendasi) * 100, 1)
                                                : 0;

                                            // Determine progress color
                                            if ($progress >= 80) {
                                                $progressColor = 'success';
                                            } elseif ($progress >= 50) {
                                                $progressColor = 'warning';
                                            } else {
                                                $progressColor = 'danger';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $data['unit_nama'] }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $data['jumlah_temuan'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $data['jumlah_rekomendasi'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $data['jumlah_tindak_lanjut'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($data['status_belum_ditindaklanjuti'] > 0)
                                                    <span class="badge bg-danger">{{ $data['status_belum_ditindaklanjuti'] }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($data['status_belum_sesuai'] > 0)
                                                    <span class="badge bg-warning">{{ $data['status_belum_sesuai'] }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($data['status_sesuai'] > 0)
                                                    <span class="badge bg-success">{{ $data['status_sesuai'] }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="progress" style="width: 100px; height: 20px;">
                                                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar"
                                                            style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ $progress }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="2" class="text-end"><strong>TOTAL</strong></th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-primary">{{ array_sum(array_column($monitoringData, 'jumlah_temuan')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-info">{{ array_sum(array_column($monitoringData, 'jumlah_rekomendasi')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-secondary">{{ array_sum(array_column($monitoringData, 'jumlah_tindak_lanjut')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-danger">{{ array_sum(array_column($monitoringData, 'status_belum_ditindaklanjuti')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-warning">{{ array_sum(array_column($monitoringData, 'status_belum_sesuai')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            <span
                                                class="badge bg-success">{{ array_sum(array_column($monitoringData, 'status_sesuai')) }}</span>
                                        </th>
                                        <th class="text-center">
                                            @php
                                                $totalRek = array_sum(array_column($monitoringData, 'jumlah_rekomendasi'));
                                                $totalSesuai = array_sum(array_column($monitoringData, 'status_sesuai'));
                                                $totalProgress = $totalRek > 0 ? round(($totalSesuai / $totalRek) * 100, 1) : 0;
                                            @endphp
                                            <strong>{{ $totalProgress }}%</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">Keterangan Status:</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <span class="badge bg-danger">Belum Ditindaklanjuti</span> - Rekomendasi yang belum ada
                                tindak lanjut
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-warning">Belum Sesuai</span> - Tindak lanjut sudah ada tetapi belum
                                sesuai
                            </div>
                            <div class="col-md-4">
                                <span class="badge bg-success">Sesuai</span> - Tindak lanjut sudah sesuai dengan rekomendasi
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">Keterangan Progress:</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-danger" style="width: 100%">0-49%</div>
                                </div>
                                <small>Progress rendah</small>
                            </div>
                            <div class="col-md-4">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" style="width: 100%">50-79%</div>
                                </div>
                                <small>Progress sedang</small>
                            </div>
                            <div class="col-md-4">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 100%">80-100%</div>
                                </div>
                                <small>Progress tinggi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#monitoringTable').DataTable({
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
                "order": [[1, "asc"]],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [8] // Progress column
                }],
                "footerCallback": function (row, data, start, end, display) {
                    // This ensures footer is always visible
                }
            });
        });
    </script>
@endpush