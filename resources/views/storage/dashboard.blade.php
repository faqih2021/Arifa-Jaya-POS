@extends('layouts.dashboard')

@section('title', 'Storage')
@section('page-title', 'Dashboard Gudang')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Stok</div>
                    <div class="stat-value">{{ number_format($warehouseStock) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Produk</div>
                    <div class="stat-value">{{ number_format($totalProducts) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Request Pending</div>
                    <div class="stat-value">{{ number_format($pendingRequests) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Request</div>
                    <div class="stat-value">{{ number_format($totalRequests) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-body text-center py-5">
                <div class="welcome-icon mb-3">
                    <i class="fas fa-warehouse fa-4x text-primary"></i>
                </div>
                <h3>Selamat Datang, {{ Auth::user()->first_name }}!</h3>
                <p class="text-muted mb-0">Anda login sebagai <strong>Admin Gudang</strong> di {{ Auth::user()->store->name ?? 'Toko' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Low Stock Products --}}
    <div class="col-lg-6 mb-4">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Produk Stok Rendah</h5>
            </div>
            <div class="data-card-body p-0">
                @forelse($lowStockProducts as $product)
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-code">{{ $product->product_code }}</div>
                    </div>
                    <div class="product-stock low">
                        {{ $product->current_stock ?? 0 }} unit
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <p class="mb-0">Semua produk memiliki stok cukup</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Stock Requests --}}
    <div class="col-lg-6 mb-4">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-clipboard-list me-2"></i>Request Stok Terbaru</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($request->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($request->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Belum ada request stok
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .welcome-icon {
        opacity: 0.8;
    }
</style>
@endpush
