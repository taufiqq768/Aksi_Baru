@extends('layouts.app')

@section('title', 'Manajemen PKPT')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Manajemen PKPT (Program Kerja Pemeriksaan Tahunan)</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus"></i> Tambah PKPT
                        </button>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <!-- Filter Tahun -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filterTahun" class="form-label">Filter Tahun:</label>
                                <select id="filterTahun" class="form-select" onchange="filterByYear()">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="dataTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Rutin</th>
                                        <th>Khusus</th>
                                        <th>Tematik</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pkpts as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>{{ $item->bulan }}</td>
                                            <td>{{ $item->rutin ?? 0 }}</td>
                                            <td>{{ $item->khusus ?? 0 }}</td>
                                            <td>{{ $item->tematik ?? 0 }}</td>
                                            <td><strong>{{ ($item->rutin ?? 0) + ($item->khusus ?? 0) + ($item->tematik ?? 0) }}</strong>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-warning"
                                                    onclick="editData({{ $item->pkpt_id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteData({{ $item->pkpt_id }})" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data PKPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pkpt.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun *</label>
                            <input type="number" class="form-control" name="tahun" id="tahun" min="1900" max="2100"
                                value="{{ date('Y') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan *</label>
                            <select class="form-select" name="bulan" id="bulan" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari">Januari</option>
                                <option value="Februari">Februari</option>
                                <option value="Maret">Maret</option>
                                <option value="April">April</option>
                                <option value="Mei">Mei</option>
                                <option value="Juni">Juni</option>
                                <option value="Juli">Juli</option>
                                <option value="Agustus">Agustus</option>
                                <option value="September">September</option>
                                <option value="Oktober">Oktober</option>
                                <option value="November">November</option>
                                <option value="Desember">Desember</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rutin" class="form-label">Rutin</label>
                            <input type="number" class="form-control" name="rutin" id="rutin" min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="khusus" class="form-label">Khusus</label>
                            <input type="number" class="form-control" name="khusus" id="khusus" min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="tematik" class="form-label">Tematik</label>
                            <input type="number" class="form-control" name="tematik" id="tematik" min="0" value="0">
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
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data PKPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_tahun" class="form-label">Tahun *</label>
                            <input type="number" class="form-control" name="tahun" id="edit_tahun" min="1900" max="2100"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bulan" class="form-label">Bulan *</label>
                            <select class="form-select" name="bulan" id="edit_bulan" required>
                                <option value="">Pilih Bulan</option>
                                <option value="Januari">Januari</option>
                                <option value="Februari">Februari</option>
                                <option value="Maret">Maret</option>
                                <option value="April">April</option>
                                <option value="Mei">Mei</option>
                                <option value="Juni">Juni</option>
                                <option value="Juli">Juli</option>
                                <option value="Agustus">Agustus</option>
                                <option value="September">September</option>
                                <option value="Oktober">Oktober</option>
                                <option value="November">November</option>
                                <option value="Desember">Desember</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rutin" class="form-label">Rutin</label>
                            <input type="number" class="form-control" name="rutin" id="edit_rutin" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit_khusus" class="form-label">Khusus</label>
                            <input type="number" class="form-control" name="khusus" id="edit_khusus" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit_tematik" class="form-label">Tematik</label>
                            <input type="number" class="form-control" name="tematik" id="edit_tematik" min="0">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "language": {
                    "emptyTable": "Tidak ada data yang tersedia",
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
                "order": [[1, "desc"], [2, "asc"]] // Sort by tahun desc, then bulan asc
            });
        });

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

        function editData(id) {
            fetch(`/pkpt/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_tahun').value = data.tahun;
                    document.getElementById('edit_bulan').value = data.bulan;
                    document.getElementById('edit_rutin').value = data.rutin ?? 0;
                    document.getElementById('edit_khusus').value = data.khusus ?? 0;
                    document.getElementById('edit_tematik').value = data.tematik ?? 0;
                    document.getElementById('editForm').action = `/pkpt/${id}`;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data');
                });
        }

        function deleteData(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pkpt/${id}`;
                form.innerHTML = `
                                                    @csrf
                                                    @method('DELETE')
                                                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush