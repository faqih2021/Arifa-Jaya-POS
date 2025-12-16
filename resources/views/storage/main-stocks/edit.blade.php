@extends('layouts.dashboard')

@section('title', 'Edit Stok - Storage')
@section('page-title', 'Edit Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.main-stocks.index') }}">Main Stocks</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-edit me-2"></i>Form Edit Stok</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.main-stocks.update', $warehouseStock) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <input type="text" class="form-control"
                            value="{{ $warehouseStock->product->product_code }} - {{ $warehouseStock->product->name }}" disabled>
                        <input type="hidden" name="product_id" value="{{ $warehouseStock->product_id }}">
                        <input type="hidden" name="warehouse_id" value="{{ $warehouseStock->warehouse_id }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_stock" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('current_stock') is-invalid @enderror"
                                    id="current_stock" name="current_stock"
                                    value="{{ old('current_stock', $warehouseStock->current_stock) }}"
                                    min="0" required>
                                <span class="input-group-text">{{ $warehouseStock->product->unit ?? 'unit' }}</span>
                                @error('current_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="minimum_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                    id="minimum_stock" name="minimum_stock"
                                    value="{{ old('minimum_stock', $warehouseStock->minimum_stock) }}"
                                    min="0" required>
                                <span class="input-group-text">{{ $warehouseStock->product->unit ?? 'unit' }}</span>
                                @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Peringatan akan muncul jika stok di bawah nilai ini</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('storage.main-stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi Stok</h5>
            </div>
            <div class="data-card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted border-0">Dibuat:</td>
                        <td class="border-0">{{ $warehouseStock->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted border-0">Diupdate:</td>
                        <td class="border-0">{{ $warehouseStock->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
