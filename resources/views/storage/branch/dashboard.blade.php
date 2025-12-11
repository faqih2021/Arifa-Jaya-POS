@extends('layouts.dashboard')

@section('title', 'Dashboard - Branch Storage')
@section('page-title', 'Dashboard Branch')

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
                <div class="stat-icon bg-danger me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Stok Rendah</div>
                    <div class="stat-value">{{ number_format($lowStockProducts) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Stok Tinggi</div>
                    <div class="stat-value">{{ number_format($highStockProducts) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Stok</div>
                    <div class="stat-value">{{ number_format($totalStock) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-body text-center py-4">
                <div class="welcome-icon mb-3">
                    <i class="fas fa-store fa-4x text-primary"></i>
                </div>
                <h3>Selamat Datang, {{ Auth::user()->first_name }}!</h3>
                <p class="text-muted mb-0">Anda login sebagai <strong>Admin Gudang Cabang</strong> di {{ Auth::user()->store->name ?? 'Toko Cabang' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Low Stock Items --}}
    <div class="col-lg-6 mb-4">
        <div class="data-card h-100">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Produk Stok Rendah</h5>
                <a href="{{ route('storage.branch.request.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Request Stok
                </a>
            </div>
            <div class="data-card-body p-0">
                @forelse($lowStockItems as $stock)
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <strong>{{ $stock->product->name ?? '-' }}</strong>
                        <br>
                        <small class="text-muted">{{ $stock->product->product_code ?? '' }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-danger">{{ $stock->current_stock }} {{ $stock->product->unit ?? '' }}</span>
                        <br>
                        <small class="text-muted">Min: {{ $stock->minimum_stock }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <p class="mb-0">Semua stok dalam kondisi aman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Requests --}}
    <div class="col-lg-6 mb-4">
        <div class="data-card h-100">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-history text-info me-2"></i>Request Terakhir</h5>
                <a href="{{ route('storage.branch.request.index') }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="data-card-body p-0">
                @forelse($recentRequests as $request)
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <strong>Request ke {{ $request->toWarehouse->name ?? 'Gudang Utama' }}</strong>
                        <br>
                        <small class="text-muted">{{ $request->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div>
                        @switch($request->status)
                            @case('pending')
                                <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                @break
                            @case('approved')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rejected</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $request->status }}</span>
                        @endswitch
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p class="mb-0">Belum ada request stok</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
