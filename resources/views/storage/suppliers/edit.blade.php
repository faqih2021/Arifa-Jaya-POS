@extends('layouts.dashboard')

@section('title', 'Edit Supplier - Storage')
@section('page-title', 'Edit Supplier')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.suppliers.index') }}">Suppliers</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-edit me-2"></i>Form Edit Supplier</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="supplier_code" class="form-label">Kode Supplier</label>
                            <input type="text" class="form-control"
                                id="supplier_code" value="{{ $supplier->supplier_code }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $supplier->name) }}"
                                placeholder="Masukkan nama supplier" maxlength="50" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                            placeholder="Contoh: 08123456789" maxlength="20" required>
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                            id="address" name="address" rows="3"
                            placeholder="Masukkan alamat lengkap supplier" required>{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('storage.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi Supplier</h5>
            </div>
            <div class="data-card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted border-0">Dibuat:</td>
                        <td class="border-0">{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted border-0">Diupdate:</td>
                        <td class="border-0">{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
