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
                                <th width="10">No</th>
                                <th width="70">Kode Produk</th>
                                <th width="250">Produk</th>
                                <th width="70">Stok Saat Ini</th>
                                <th width="70">Stok Minimum</th>
                                <th width="100">Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouseStocks as $index => $stock)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $stock->product->product_code ?? '-' }}</code></td>
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
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-detail" title="Detail"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $stock->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('storage.main-stocks.edit', $stock) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('storage.main-stocks.destroy', $stock) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action btn-delete" title="Hapus">
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

        // SweetAlert delete confirmation
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Data Stok?',
                text: 'Data stok gudang akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
