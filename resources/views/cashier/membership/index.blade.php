@extends('layouts.dashboard')

@section('title', 'Membership - Arifa Jaya POS')
@section('page-title', 'Membership')

@section('breadcrumb')
<li class="breadcrumb-item active">Membership</li>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $memberships->count() }}</h3>
                    <p>Total Member</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $memberships->where('is_active', true)->count() }}</h3>
                    <p>Member Aktif</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $memberships->where('registered_at_store_id', Auth::user()->store_id)->count() }}</h3>
                    <p>Member Toko Ini</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $memberships->where('joined_at', '>=', now()->startOfMonth())->count() }}</h3>
                    <p>Member Baru (Bulan Ini)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-id-card me-2"></i>Daftar Member</h5>
        <a href="{{ route('cashier.membership.create') }}" class="btn btn-primary btn-add">
            <i class="fas fa-plus me-1"></i> Tambah Member
        </a>
    </div>
    <div class="data-card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="memberTable">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="10%">Kode</th>
                        <th width="12%">Toko</th>
                        <th width="15%">Nama</th>
                        <th width="12%">Telepon</th>
                        <th width="15%">Alamat</th>
                        <th width="8%">Status</th>
                        <th width="12%">Tgl Daftar</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberships as $index => $member)
                    @php
                        $isOwnStore = $member->registered_at_store_id == Auth::user()->store_id;
                    @endphp
                    <tr class="{{ !$member->is_active ? 'row-inactive' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <code class="member-code">{{ $member->membership_code }}</code>
                        </td>
                        <td>
                            @if($isOwnStore)
                                <span class="store-badge store-own">
                                    <i class="fas fa-store me-1"></i>{{ $member->registeredAtStore->name ?? '-' }}
                                </span>
                            @else
                                <span class="store-badge store-other">
                                    {{ $member->registeredAtStore->name ?? '-' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="member-name">
                                <i class="fas fa-user-circle me-1 text-muted"></i>
                                <span title="{{ $member->name }}">{{ Str::limit($member->name, 18) }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">
                                <i class="fas fa-phone me-1"></i>{{ $member->phone }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" title="{{ $member->address }}">
                                {{ Str::limit($member->address, 25) }}
                            </span>
                        </td>
                        <td>
                            @if($member->is_active)
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-times-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($member->joined_at)->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('cashier.membership.toggle', $member->id) }}" method="POST" class="d-inline toggle-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                               {{ $member->is_active ? 'checked' : '' }}
                                               data-status="{{ $member->is_active ? 'nonaktifkan' : 'aktifkan' }}"
                                               data-own-store="{{ $isOwnStore ? 'true' : 'false' }}">
                                    </div>
                                </form>
                                <button type="button" class="btn-action btn-edit" title="Edit"
                                        data-own-store="{{ $isOwnStore ? 'true' : 'false' }}"
                                        data-url="{{ route('cashier.membership.edit', $member->id) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('cashier.membership.destroy', $member->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete" title="Hapus"
                                            data-has-orders="{{ $member->orders()->exists() ? 'true' : 'false' }}"
                                            data-own-store="{{ $isOwnStore ? 'true' : 'false' }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada data member</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Page-specific: Member name styling */
    .member-name {
        font-weight: 500;
    }

    /* Table ID specific styling */
    #memberTable thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px 10px;
        font-size: 0.85rem;
    }
    #memberTable thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    #memberTable thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    #memberTable tbody tr {
        transition: all 0.2s ease;
    }
    #memberTable tbody tr:hover {
        background: #f8f9ff;
    }
    #memberTable tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.9rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#memberTable').DataTable({
            pageLength: 10,
            language: {
                search: "<i class='fas fa-search'></i>",
                searchPlaceholder: "Cari member...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ditemukan data yang cocok",
                emptyTable: "Tidak ada data member",
                paginate: {
                    first: "<i class='fas fa-angle-double-left'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    previous: "<i class='fas fa-angle-left'></i>"
                }
            },
            dom: '<"row align-items-center mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row align-items-center mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });

        // Show success/error alerts
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#667eea'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#667eea'
        });
        @endif
    });

    // Delete confirmation with SweetAlert - using event delegation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const hasOrders = $(this).data('has-orders') === true || $(this).data('has-orders') === 'true';
        const isOwnStore = $(this).data('own-store') === true || $(this).data('own-store') === 'true';

        // Check if member belongs to different store
        if (!isOwnStore) {
            Swal.fire({
                title: 'Tidak Diizinkan!',
                text: 'Anda hanya dapat menghapus member yang terdaftar di toko Anda.',
                icon: 'error',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        if (hasOrders) {
            // Member has orders, cannot be deleted
            Swal.fire({
                title: 'Tidak Dapat Dihapus!',
                text: 'Member ini memiliki riwayat transaksi dan tidak dapat dihapus. Gunakan fitur nonaktifkan sebagai alternatif.',
                icon: 'error',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Mengerti'
            });
        } else {
            // Member has no orders, can be deleted
            Swal.fire({
                title: 'Hapus Member?',
                text: 'Data member akan dihapus permanen. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // Edit button click handler
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const isOwnStore = $(this).data('own-store') === true || $(this).data('own-store') === 'true';
        const url = $(this).data('url');

        if (!isOwnStore) {
            Swal.fire({
                title: 'Tidak Diizinkan!',
                text: 'Anda hanya dapat mengedit member yang terdaftar di toko Anda.',
                icon: 'error',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        // Redirect to edit page
        window.location.href = url;
    });

    // Toggle status confirmation with SweetAlert - using switch
    $(document).on('change', '.toggle-switch', function(e) {
        e.preventDefault();
        const checkbox = $(this);
        const form = checkbox.closest('form');
        const isActivating = !checkbox.is(':checked'); // karena sudah berubah, kita ambil kebalikannya
        const isOwnStore = checkbox.data('own-store') === true || checkbox.data('own-store') === 'true';

        // Kembalikan ke posisi semula dulu
        checkbox.prop('checked', !checkbox.is(':checked'));

        // Check if member belongs to different store
        if (!isOwnStore) {
            Swal.fire({
                title: 'Tidak Diizinkan!',
                text: 'Anda hanya dapat mengubah status member yang terdaftar di toko Anda.',
                icon: 'error',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: isActivating ? 'Nonaktifkan Member?' : 'Aktifkan Member?',
            text: isActivating
                ? 'Member tidak akan dapat digunakan untuk transaksi.'
                : 'Member akan dapat digunakan kembali untuk transaksi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isActivating ? '#6c757d' : '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: isActivating ? 'Ya, Nonaktifkan!' : 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
