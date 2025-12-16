@extends('layouts.dashboard')

@section('title', 'Tambah Produk - Storage')
@section('page-title', 'Tambah Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.products.index') }}">Products</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Form Tambah Produk</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.products.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama produk" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                id="unit" name="unit" value="{{ old('unit') }}"
                                placeholder="Contoh: pcs, kg, zak, m3" required>
                            @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3"
                            placeholder="Masukkan deskripsi produk (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="fas fa-tags me-2"></i>Informasi Harga</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="actual_price" class="form-label">Harga Modal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('actual_price') is-invalid @enderror"
                                    id="actual_price" name="actual_price" value="{{ old('actual_price') }}"
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
                                    id="selling_price" name="selling_price" value="{{ old('selling_price') }}"
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
                            <i class="fas fa-save me-1"></i> Simpan Produk
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
                <div class="alert alert-info mb-0">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Kode produk harus unik dan tidak boleh sama dengan produk lain</li>
                        <li>Gunakan satuan yang konsisten (pcs, kg, zak, m3, dll)</li>
                        <li>Pastikan harga jual lebih tinggi dari harga modal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
