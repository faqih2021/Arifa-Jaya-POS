@extends('layouts.dashboard')

@section('title', 'Dashboard Stock')
@section('page-title', 'Dashboard Stock')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard Stock</li>
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
                    <div class="stat-value">{{ $lowStockProducts->count() }}</div>
                    <div class="stat-change negative">
                        <i class="fas fa-arrow-down"></i> < 10 unit
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Stok Tinggi</div>
                    <div class="stat-value">{{ $highStockProducts->count() }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> > 100 unit
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Gudang</div>
                    <div class="stat-value">{{ $warehouseStocks->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Low Stock Products --}}
    <div class="col-lg-6 mb-3">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-exclamation-triangle text-danger me-2"></i>Produk Stok Rendah</h5>
            </div>
            <div class="data-card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @forelse($lowStockProducts as $product)
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-code">{{ $product->product_code }}</div>
                    </div>
                    <div class="product-stock low">
                        {{ $product->total_stock ?? 0 }} unit
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <p>Semua produk memiliki stok cukup</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- High Stock Products --}}
    <div class="col-lg-6 mb-3">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-check-circle text-success me-2"></i>Produk Stok Tinggi</h5>
            </div>
            <div class="data-card-body p-0" style="max-height: 400px; overflow-y: auto;">
                @forelse($highStockProducts as $product)
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-code">{{ $product->product_code }}</div>
                    </div>
                    <div class="product-stock high">
                        {{ $product->total_stock ?? 0 }} unit
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Tidak ada produk dengan stok tinggi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Stock per Warehouse --}}
<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-chart-bar me-2"></i>Stok per Toko</h5>
            </div>
            <div class="data-card-body">
                <div class="chart-container">
                    <canvas id="warehouseStockChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-list me-2"></i>Detail Stok per Toko</h5>
            </div>
            <div class="data-card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Toko</th>
                            <th class="text-end">Total Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouseStocks as $stock)
                        <tr>
                            <td>{{ $stock->store_name }}</td>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ number_format($stock->total_stock) }} unit</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum ada data stok</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Product List --}}
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-boxes me-2"></i>Daftar Produk</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productTable">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Unit</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-end">Total Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td><code>{{ $product->product_code }}</code></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->unit }}</td>
                                <td class="text-end">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="text-end">{{ $product->total_stock ?? 0 }}</td>
                                <td>
                                    @if(($product->total_stock ?? 0) < 10)
                                        <span class="badge bg-danger">Stok Rendah</span>
                                    @elseif(($product->total_stock ?? 0) > 100)
                                        <span class="badge bg-success">Stok Tinggi</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#productTable').DataTable({
            pageLength: 10,
            order: [[4, 'asc']]
        });
    });

    // Warehouse Stock Chart
    const warehouseCtx = document.getElementById('warehouseStockChart').getContext('2d');
    new Chart(warehouseCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($warehouseStocks->pluck('store_name')) !!},
            datasets: [{
                label: 'Total Stok',
                data: {!! json_encode($warehouseStocks->pluck('total_stock')) !!},
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(17, 153, 142, 0.8)',
                    'rgba(56, 239, 125, 0.8)',
                    'rgba(240, 147, 251, 0.8)',
                    'rgba(245, 87, 108, 0.8)'
                ],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
