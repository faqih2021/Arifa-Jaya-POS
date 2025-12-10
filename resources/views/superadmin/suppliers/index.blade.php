@extends('layouts.dashboard')

@section('title', 'Daftar Supplier')
@section('page-title', 'Daftar Supplier')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.suppliers.index') }}">Supplier</a></li>
<li class="breadcrumb-item active">Daftar Supplier</li>
@endsection

@section('content')
<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-truck me-2"></i>Daftar Supplier</h5>
        <a href="{{ route('superadmin.suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Supplier
        </a>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="supplierTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $index => $supplier)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $supplier->supplier_code }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="supplier-icon me-2">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="fw-semibold">{{ $supplier->name }}</div>
                            </div>
                        </td>
                        <td>
                            <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                <i class="fas fa-phone me-1"></i>{{ $supplier->phone }}
                            </a>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $supplier->address }}">
                                {{ $supplier->address }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.suppliers.show', $supplier->id) }}"
                                   class="btn btn-sm btn-info"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.suppliers.edit', $supplier->id) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-danger btn-delete"
                                        title="Hapus"
                                        data-id="{{ $supplier->id }}"
                                        data-name="{{ $supplier->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $supplier->id }}"
                                      action="{{ route('superadmin.suppliers.destroy', $supplier->id) }}"
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
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
        $('#supplierTable').DataTable({
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ditemukan data yang cocok",
                emptyTable: "Tidak ada data supplier",
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
                title: 'Hapus Supplier?',
                html: `Apakah Anda yakin ingin menghapus supplier <strong>${name}</strong>?<br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>`,
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
    .supplier-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #17a2b8, #138496);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
</style>
@endpush
