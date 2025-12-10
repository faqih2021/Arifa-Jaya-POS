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
        <a href="{{ route('superadmin.employees.create') }}" class="btn btn-primary">
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
                                <span class="badge bg-danger">Superadmin</span>
                            @elseif($employee->roles == 'cashier')
                                <span class="badge bg-success">Kasir</span>
                            @elseif($employee->roles == 'storage')
                                <span class="badge bg-info">Gudang</span>
                            @else
                                <span class="badge bg-secondary">{{ $employee->roles }}</span>
                            @endif
                        </td>
                        <td>{{ $employee->store->name ?? '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.employees.edit', $employee->id) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($employee->id != auth()->id())
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
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
        $('.btn-delete').on('click', function() {
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

@push('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush
