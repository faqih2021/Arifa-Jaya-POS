@extends('layouts.dashboard')

@section('title', 'Dashboard Gudang Cabang - Arifa Jaya POS')
@section('page-title', 'Dashboard Gudang Cabang')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="welcome-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">Selamat Datang, {{ Auth::user()->first_name }}! 👋</h3>
                        <p class="mb-0 opacity-85">
                            Anda login sebagai <strong>Admin Gudang Cabang</strong> di <strong>{{ Auth::user()->store->name ?? 'Toko Cabang' }}</strong>
                        </p>
                        <p class="mb-0 opacity-75 small mt-2">
                            <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="fas fa-store fa-5x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($totalProducts) }}</h3>
                    <p>Total Produk</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-danger">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($lowStockProducts) }}</h3>
                    <p>Stok Rendah</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($highStockProducts) }}</h3>
                    <p>Stok Tinggi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($totalStock) }}</h3>
                    <p>Total Stok</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Low Stock Items --}}
    <div class="col-lg-6 mb-4">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Produk Stok Rendah</h5>
                <a href="{{ route('storage.branch.request.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Request Stok
                </a>
            </div>
            <div class="data-card-body p-0">
                @forelse($lowStockItems as $stock)
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">{{ $stock->product->name ?? '-' }}</div>
                        <div class="product-code">{{ $stock->product->product_code ?? '' }}</div>
                    </div>
                    <div class="product-stock low">
                        {{ $stock->current_stock }} {{ $stock->product->unit ?? 'unit' }}
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
            <div class="data-card-header">
                <h5><i class="fas fa-clipboard-list me-2"></i>Request Stok Terbaru</h5>
                <a href="{{ route('storage.branch.request.index') }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRequests as $request)
                            <tr>
                                <td>{{ $request->toWarehouse->name ?? 'Gudang Utama' }}</td>
                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($request->status == 'approved')
                                        <span class="request-badge request-approved"><i class="fas fa-check me-1"></i>Disetujui</span>
                                    @elseif($request->status == 'pending')
                                        <span class="request-badge request-pending"><i class="fas fa-clock me-1"></i>Pending</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="request-badge request-rejected"><i class="fas fa-times me-1"></i>Ditolak</span>
                                    @else
                                        <span class="status-badge status-inactive">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
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
