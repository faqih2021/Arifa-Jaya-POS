@extends('layouts.dashboard')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.suppliers.index') }}">Supplier</a></li>
<li class="breadcrumb-item active">Detail Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-building me-2"></i>Informasi Supplier</h5>
            </div>
            <div class="data-card-body text-center">
                <div class="supplier-avatar mb-3">
                    <i class="fas fa-truck"></i>
                </div>
                <h4 class="mb-1">{{ $supplier->name }}</h4>
                <code class="d-block mb-3">{{ $supplier->supplier_code }}</code>

                <div class="text-start">
                    <div class="info-item mb-3">
                        <div class="info-label"><i class="fas fa-phone me-2"></i>Telepon</div>
                        <div class="info-value">
                            <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                        </div>
                    </div>

                    <div class="info-item mb-3">
                        <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Alamat</div>
                        <div class="info-value">{{ $supplier->address }}</div>
                    </div>

                    <div class="info-item mb-3">
                        <div class="info-label"><i class="fas fa-calendar me-2"></i>Terdaftar</div>
                        <div class="info-value">{{ $supplier->created_at->format('d M Y H:i') }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-clock me-2"></i>Terakhir Update</div>
                        <div class="info-value">{{ $supplier->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('superadmin.suppliers.edit', $supplier->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-delete-supplier"
                            data-id="{{ $supplier->id }}"
                            data-name="{{ $supplier->name }}">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                    <form id="delete-supplier-form"
                          action="{{ route('superadmin.suppliers.destroy', $supplier->id) }}"
                          method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-history me-2"></i>Riwayat Pemesanan dari Supplier</h5>
            </div>
            <div class="data-card-body">
                {{-- This section can be expanded to show order history from this supplier --}}
                <div class="text-center text-muted py-5">
                    <i class="fas fa-box-open fa-4x mb-3"></i>
                    <p class="mb-0">Riwayat pemesanan akan ditampilkan di sini</p>
                    <small>Fitur ini akan tersedia dalam pembaruan selanjutnya</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="{{ route('superadmin.suppliers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Supplier
    </a>
</div>
@endsection

@push('styles')
<style>
    .supplier-avatar {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #17a2b8, #138496);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 40px;
        margin: 0 auto;
    }

    .info-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .info-value {
        font-weight: 500;
        color: #333;
    }

    .info-value a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .info-value a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
    $('.btn-delete-supplier').on('click', function() {
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
                document.getElementById('delete-supplier-form').submit();
            }
        });
    });
</script>
@endpush
