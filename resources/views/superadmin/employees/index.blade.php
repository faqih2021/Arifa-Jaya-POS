@extends('layouts.dashboard')

@section('title', 'Daftar Karyawan')
@section('page-title', 'Daftar Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.employees.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Daftar Karyawan</li>
@endsection

@section('content')
<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-users me-2"></i>Daftar Karyawan</h5>
        <a href="{{ route('superadmin.employees.create') }}" class="btn btn-add">
            <i class="fas fa-plus me-1"></i> Tambah Karyawan
        </a>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="employeeTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Toko</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $employee->username }}</code></td>
                        <td>
                            @if($employee->roles == 'superadmin')
                                <span class="status-badge role-badge-superadmin"><i class="fas fa-crown me-1"></i>Superadmin</span>
                            @elseif($employee->roles == 'cashier')
                                <span class="status-badge role-badge-cashier"><i class="fas fa-cash-register me-1"></i>Kasir</span>
                            @elseif($employee->roles == 'storage')
                                <span class="status-badge role-badge-storage"><i class="fas fa-warehouse me-1"></i>Gudang</span>
                            @else
                                <span class="status-badge status-inactive">{{ $employee->roles }}</span>
                            @endif
                        </td>
                        <td>{{ $employee->store->name ?? '-' }}</td>
                        <td class="text-center">
                            <div class="action-buttons justify-content-center">
                                <a href="{{ route('superadmin.employees.edit', $employee->id) }}"
                                   class="btn-action btn-edit"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($employee->id != auth()->id())
                                <button type="button"
                                        class="btn-action btn-delete btn-delete-employee"
                                        title="Hapus"
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $employee->id }}"
                                      action="{{ route('superadmin.employees.destroy', $employee->id) }}"
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
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
        $('#employeeTable').DataTable({
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ditemukan data yang cocok",
                emptyTable: "Tidak ada data karyawan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // SweetAlert Delete Confirmation
        $('.btn-delete-employee').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Karyawan?',
                html: `Apakah Anda yakin ingin menghapus karyawan <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });
</script>
@endpush
