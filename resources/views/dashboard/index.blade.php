@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title mb-1">Dashboard</h1>
                    <p class="text-muted mb-0">Selamat datang di Aplikasi AKSI - Audit dan Kontrol Sistem Informasi</p>
                </div>
                <div>
                    <label for="filterTahun" class="form-label mb-1 small text-muted">Filter Tahun:</label>
                    <select id="filterTahun" name="tahun" class="form-select" onchange="filterByYear()"
                        style="min-width: 120px;">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Cards Pemeriksaan -->
        <div class="row g-4 mb-4">
            <!-- Total Pemeriksaan -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-medium">Total Pemeriksaan</p>
                                <h2 class="mb-0 fw-bold text-primary">{{ number_format($totalPemeriksaan) }}</h2>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-clipboard-list fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-chart-line me-1"></i> Semua Jenis
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemeriksaan Rutin -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-medium">Pemeriksaan Rutin</p>
                                <h2 class="mb-0 fw-bold text-success">{{ number_format($pemeriksaanRutin) }}</h2>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-sync me-1"></i> Rutin
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemeriksaan Khusus -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-medium">Pemeriksaan Khusus</p>
                                <h2 class="mb-0 fw-bold text-warning">{{ number_format($pemeriksaanKhusus) }}</h2>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-exclamation-circle me-1"></i> Khusus
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pemeriksaan Tematik -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-2 fw-medium">Pemeriksaan Tematik</p>
                                <h2 class="mb-0 fw-bold text-info">{{ number_format($pemeriksaanTematik) }}</h2>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-layer-group fa-2x"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-project-diagram me-1"></i> Tematik
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4">
            <!-- Pie Chart - Status TL -->
            <div class="col-xl-4 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Status Tindak Lanjut
                        </h5>
                        <p class="text-muted small mb-0">Distribusi status TL</p>
                    </div>
                    <div class="card-body">
                        <canvas id="tlStatusChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bar Chart - Perbandingan LHA, Temuan, Rekomendasi -->
            <div class="col-xl-8 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Perbandingan LHA, Temuan & Rekomendasi
                        </h5>
                        <p class="text-muted small mb-0">Bulan berjalan vs Year to Date ({{ $selectedYear }})</p>
                    </div>
                    <div class="card-body">
                        <canvas id="comparisonChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Ringkasan Statistik
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-file-alt fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-muted">LHA Bulan Ini</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($lhaBulanIni) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-search fa-2x text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-muted">Temuan Bulan Ini</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($temuanBulanIni) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-lightbulb fa-2x text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 text-muted">Rekomendasi Bulan Ini</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($rekomendasiBulanIni) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .card {
            border-radius: 12px;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Pie Chart - Status TL
        const tlStatusCtx = document.getElementById('tlStatusChart').getContext('2d');
        const tlStatusChart = new Chart(tlStatusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($tlStatusLabels),
                datasets: [{
                    data: @json($tlStatusCounts),
                    backgroundColor: [
                        '#088395',
                        '#05bfdb',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                family: 'Inter'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            family: 'Inter'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Inter'
                        },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function (context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart - Perbandingan
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        const comparisonChart = new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['LHA', 'Temuan', 'Rekomendasi'],
                datasets: [
                    {
                        label: 'Bulan Berjalan',
                        data: [{{ $lhaBulanIni }}, {{ $temuanBulanIni }}, {{ $rekomendasiBulanIni }}],
                        backgroundColor: '#088395',
                        borderRadius: 8,
                        barThickness: 40
                    },
                    {
                        label: 'Year to Date',
                        data: [{{ $lhaYTD }}, {{ $temuanYTD }}, {{ $rekomendasiYTD }}],
                        backgroundColor: '#05bfdb',
                        borderRadius: 8,
                        barThickness: 40
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                family: 'Inter'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            family: 'Inter'
                        },
                        bodyFont: {
                            size: 13,
                            family: 'Inter'
                        },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                family: 'Inter'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Inter',
                                weight: '500'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function filterByYear() {
            const tahun = document.getElementById('filterTahun').value;
            const url = new URL(window.location.href);

            if (tahun) {
                url.searchParams.set('tahun', tahun);
            } else {
                url.searchParams.delete('tahun');
            }

            window.location.href = url.toString();
        }
    </script>
@endpush