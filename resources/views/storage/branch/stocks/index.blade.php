@extends('layouts.dashboard')

@section('title', 'Branch Stocks - Storage')
@section('page-title', 'Branch Stocks')

@section('breadcrumb')
<li class="breadcrumb-item active">Branch Stocks</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-boxes me-2"></i>Daftar Stok Cabang</h5>
                <a href="{{ route('storage.branch.request.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-paper-plane me-1"></i> Request Stok
                </a>
            </div>
            <div class="data-card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="branchStocksTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="80">Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouseStocks as $index => $stock)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $stock->product->product_code ?? '-' }}</code></td>
                                <td>{{ $stock->product->name ?? '-' }}</td>
                                <td><strong>{{ number_format($stock->current_stock) }} {{ $stock->product->unit ?? '' }}</strong></td>
                                <td>{{ number_format($stock->minimum_stock) }}</td>
                                <td>
                                    @if($stock->current_stock <= $stock->minimum_stock)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Stok Rendah
                                    </span>
                                    @elseif($stock->current_stock > $stock->minimum_stock * 3)
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>Stok Tinggi
                                    </span>
                                    @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-check me-1"></i>Normal
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('storage.branch.stocks.show', $stock) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-boxes fa-3x mb-3 d-block"></i>
                                    Belum ada data stok
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#branchStocksTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });
    });
</script>
@endpush
