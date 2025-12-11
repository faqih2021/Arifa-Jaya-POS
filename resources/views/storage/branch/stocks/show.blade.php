@extends('layouts.dashboard')

@section('title', 'Detail Stok - Storage')
@section('page-title', 'Detail Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.branch.stocks.index') }}">Branch Stocks</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-box me-2"></i>Informasi Stok</h5>
            </div>
            <div class="data-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Gudang</td>
                                <td><strong>{{ $warehouseStock->warehouse->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kode Produk</td>
                                <td><code class="fs-5">{{ $warehouseStock->product->product_code ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Produk</td>
                                <td><strong>{{ $warehouseStock->product->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Satuan</td>
                                <td><span class="badge bg-secondary">{{ $warehouseStock->product->unit ?? '-' }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Stok Saat Ini</td>
                                <td>
                                    <span class="fs-4 fw-bold text-{{ $warehouseStock->current_stock <= $warehouseStock->minimum_stock ? 'danger' : 'success' }}">
                                        {{ number_format($warehouseStock->current_stock) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Stok Minimum</td>
                                <td>{{ number_format($warehouseStock->minimum_stock) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    @if($warehouseStock->current_stock <= $warehouseStock->minimum_stock)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Stok Rendah
                                    </span>
                                    @elseif($warehouseStock->current_stock > $warehouseStock->minimum_stock * 3)
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>Stok Tinggi
                                    </span>
                                    @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-check me-1"></i>Normal
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-tags me-2"></i>Informasi Harga</h5>
            </div>
            <div class="data-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Harga Modal:</span>
                    <span>Rp {{ number_format($warehouseStock->product->actual_price ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Harga Jual:</span>
                    <span class="text-success">Rp {{ number_format($warehouseStock->product->selling_price ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Harga Member:</span>
                    <span class="text-primary">Rp {{ number_format($warehouseStock->product->membership_price ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($warehouseStock->current_stock <= $warehouseStock->minimum_stock)
        <div class="data-card mt-3">
            <div class="data-card-body text-center">
                <div class="text-warning mb-3">
                    <i class="fas fa-exclamation-circle fa-3x"></i>
                </div>
                <h6>Stok Hampir Habis!</h6>
                <p class="text-muted small mb-3">Segera lakukan request stok ke gudang utama.</p>
                <a href="{{ route('storage.branch.request.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-paper-plane me-1"></i> Request Stok
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('storage.branch.stocks.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection
