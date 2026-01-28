@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Manajemen User</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus"></i> Tambah User
                        </button>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Level</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->user_nik }}</td>
                                            <td>{{ $item->user_nama }}</td>
                                            <td>{{ $item->user_email }}</td>
                                            <td>{{ $item->user_tlp ?? '-' }}</td>
                                            <td>
                                                @switch($item->user_level)
                                                    @case('admin')
                                                        <span class="badge bg-danger">{{ $item->user_level }}</span>
                                                        @break
                                                    @case('spi')
                                                        <span class="badge bg-primary">{{ $item->user_level }}</span>
                                                        @break
                                                    @case('operator')
                                                        <span class="badge bg-warning">{{ $item->user_level }}</span>
                                                        @break
                                                    @case('verifikator')
                                                        <span class="badge bg-success">{{ $item->user_level }}</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $item->user_level }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $item->unit->unit_nama ?? '-' }}</td>
                                            <td>
                                                @if ($item->user_aktif === 'Y')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editData({{ $item->id_users }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteData({{ $item->id_users }})" title="Hapus">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_nik" class="form-label">NIK *</label>
                                    <input type="text" class="form-control" name="user_nik" id="user_nik" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_nama" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" name="user_nama" id="user_nama" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="user_email" id="user_email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_tlp" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="user_tlp" id="user_tlp">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_password" class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="user_password" id="user_password"
                                        required minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_level" class="form-label">Level *</label>
                                    <select class="form-control" name="user_level" id="user_level" required>
                                        <option value="">Pilih Level</option>
                                        <option value="admin">Admin</option>
                                        <option value="spi">SPI</option>
                                        <option value="operator">Operator</option>
                                        <option value="verifikator">Verifikator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Unit Kerja</label>
                            <select class="form-control" name="unit_id" id="unit_id">
                                <option value="">Pilih Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->unit_id }}">{{ $unit->unit_nama }}</option>
                                @endforeach
                            </select>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_nik" class="form-label">NIK *</label>
                                    <input type="text" class="form-control" name="user_nik" id="edit_user_nik" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_nama" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" name="user_nama" id="edit_user_nama" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="user_email" id="edit_user_email"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_tlp" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="user_tlp" id="edit_user_tlp">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_password" class="form-label">Password <small
                                            class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                    <input type="password" class="form-control" name="user_password" id="edit_user_password"
                                        minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_level" class="form-label">Level *</label>
                                    <select class="form-control" name="user_level" id="edit_user_level" required>
                                        <option value="">Pilih Level</option>
                                        <option value="admin">Admin</option>
                                        <option value="spi">SPI</option>
                                        <option value="operator">Operator</option>
                                        <option value="verifikator">Verifikator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_unit_id" class="form-label">Unit Kerja</label>
                                    <select class="form-control" name="unit_id" id="edit_unit_id">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->unit_id }}">{{ $unit->unit_nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_user_aktif" class="form-label">Status *</label>
                                    <select class="form-control" name="user_aktif" id="edit_user_aktif" required>
                                        <option value="Y">Aktif</option>
                                        <option value="N">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
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
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 25
            });
        });

        function editData(id) {
            fetch(`/master/user/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_user_nik').value = data.user_nik;
                    document.getElementById('edit_user_nama').value = data.user_nama;
                    document.getElementById('edit_user_email').value = data.user_email;
                    document.getElementById('edit_user_tlp').value = data.user_tlp || '';
                    document.getElementById('edit_user_level').value = data.user_level;
                    document.getElementById('edit_unit_id').value = data.unit_id || '';
                    document.getElementById('edit_user_aktif').value = data.user_aktif;
                    document.getElementById('edit_user_password').value = '';
                    document.getElementById('editForm').action = `/master/user/${id}`;
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        }

        function deleteData(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/master/user/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush