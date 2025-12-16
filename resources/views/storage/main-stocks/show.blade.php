@extends('layouts.dashboard')

@section('title', 'Detail Stok - Storage')
@section('page-title', 'Detail Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.main-stocks.index') }}">Main Stocks</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-warehouse me-2"></i>Informasi Stok</h5>
                <div>
                    <a href="{{ route('storage.main-stocks.edit', $warehouseStock) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('storage.main-stocks.destroy', $warehouseStock) }}" method="POST" class="d-inline" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" id="btnDelete">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="data-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Kode Produk</td>
                                <td class="border-0"><code class="fs-5">{{ $warehouseStock->product->product_code ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Nama Produk</td>
                                <td class="border-0"><strong>{{ $warehouseStock->product->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Satuan</td>
                                <td class="border-0">{{ $warehouseStock->product->unit ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Stok Saat Ini</td>
                                <td class="border-0"><strong class="fs-5">{{ number_format($warehouseStock->current_stock) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Stok Minimum</td>
                                <td class="border-0">{{ number_format($warehouseStock->minimum_stock) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Status</td>
                                <td class="border-0">
                                    @if($warehouseStock->current_stock <= $warehouseStock->minimum_stock)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Stok Rendah
                                    </span>
                                    @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Aman
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Dibuat</td>
                                <td class="border-0">{{ $warehouseStock->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Terakhir Update</td>
                                <td class="border-0">{{ $warehouseStock->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('storage.main-stocks.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Stok
    </a>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnDelete').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Stok',
            text: 'Apakah Anda yakin ingin menghapus data stok ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    });
</script>
@endpush
