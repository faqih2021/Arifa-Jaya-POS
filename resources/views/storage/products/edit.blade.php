@extends('layouts.dashboard')

@section('title', 'Edit Produk - Storage')
@section('page-title', 'Edit Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.products.index') }}">Products</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-edit me-2"></i>Form Edit Produk</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_code" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('product_code') is-invalid @enderror"
                                id="product_code" name="product_code" value="{{ old('product_code', $product->product_code) }}"
                                placeholder="Contoh: PRD001" required>
                            @error('product_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $product->name) }}"
                                placeholder="Masukkan nama produk" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3"
                            placeholder="Masukkan deskripsi produk (opsional)">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                id="unit" name="unit" value="{{ old('unit', $product->unit) }}"
                                placeholder="Contoh: pcs, kg, zak, m3" required>
                            @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="fas fa-tags me-2"></i>Informasi Harga</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="actual_price" class="form-label">Harga Modal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('actual_price') is-invalid @enderror"
                                    id="actual_price" name="actual_price" value="{{ old('actual_price', $product->actual_price) }}"
                                    placeholder="0" min="0" required>
                                @error('actual_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="selling_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                                    id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
                                    placeholder="0" min="0" required>
                                @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('storage.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi Produk</h5>
            </div>
            <div class="data-card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Dibuat:</td>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diupdate:</td>
                        <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
