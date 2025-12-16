@extends('layouts.dashboard')

@section('title', 'Tambah Stok - Storage')
@section('page-title', 'Tambah Stok Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.main-stocks.index') }}">Main Stocks</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Form Tambah Stok</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.main-stocks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                    <input type="hidden" name="maximum_stock" id="maximum_stock" value="100">

                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                            @php
                                $stock = $warehouseStocks->get($product->id);
                            @endphp
                            <option value="{{ $product->id }}" 
                                data-current-stock="{{ $stock ? $stock->current_stock : 0 }}"
                                data-minimum-stock="{{ $stock ? $stock->minimum_stock : 10 }}"
                                data-maximum-stock="{{ $stock ? $stock->maximum_stock : 100 }}"
                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->product_code }} - {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_stock" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('current_stock') is-invalid @enderror"
                                id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}"
                                min="0" required>
                            @error('current_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="minimum_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 10) }}"
                                min="0" required>
                            @error('minimum_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Peringatan akan muncul jika stok di bawah nilai ini</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('storage.main-stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="data-card-body">
                <p class="text-muted mb-2">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    Pastikan produk yang dipilih belum memiliki data stok di gudang yang sama.
                </p>
                <p class="text-muted mb-0">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Stok minimum digunakan untuk peringatan stok rendah.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#product_id').on('change', function() {
        var selected = $(this).find('option:selected');
        var currentStock = selected.data('current-stock');
        var minimumStock = selected.data('minimum-stock');
        var maximumStock = selected.data('maximum-stock');
        
        if ($(this).val()) {
            $('#current_stock').val(currentStock);
            $('#minimum_stock').val(minimumStock);
            $('#maximum_stock').val(maximumStock);
        } else {
            $('#current_stock').val(0);
            $('#minimum_stock').val(10);
            $('#maximum_stock').val(100);
        }
    });
    
    // Trigger on page load if old value exists
    if ($('#product_id').val()) {
        $('#product_id').trigger('change');
    }
});
</script>
@endpush
