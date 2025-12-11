@extends('layouts.dashboard')

@section('title', 'Main Stocks - Storage')
@section('page-title', 'Main Stocks')

@section('breadcrumb')
<li class="breadcrumb-item active">Main Stocks</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-warehouse me-2"></i>Stok Gudang Utama</h5>
                <a href="{{ route('storage.main-stocks.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Stok
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
                    <table class="table table-hover" id="mainStocksTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gudang</th>
                                <th>Produk</th>
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
                                <td>{{ $stock->warehouse->name ?? '-' }}</td>
                                <td>{{ $stock->product->name ?? '-' }}</td>
                                <td><strong>{{ number_format($stock->current_stock) }}</strong></td>
                                <td>{{ number_format($stock->minimum_stock) }}</td>
                                <td>
                                    @if($stock->current_stock <= $stock->minimum_stock)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Stok Rendah
                                    </span>
                                    @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Aman
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('storage.main-stocks.show', $stock) }}" class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('storage.main-stocks.edit', $stock) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('storage.main-stocks.destroy', $stock) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data stok ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-warehouse fa-3x mb-3 d-block"></i>
                                    Belum ada data stok gudang
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
        $('#mainStocksTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });
    });
</script>
@endpush
