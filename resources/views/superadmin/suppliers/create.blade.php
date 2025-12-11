@extends('layouts.dashboard')

@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.suppliers.index') }}">Supplier</a></li>
<li class="breadcrumb-item active">Tambah Supplier</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Tambah Supplier Baru</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('superadmin.suppliers.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <input type="text"
                                   class="form-control auto-capitalize @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="PT. Supplier Indonesia"
                                   maxlength="50"
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Maksimal 50 karakter, kode supplier akan dibuat otomatis</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text"
                                   class="form-control numbers-only @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="08123456789"
                                   maxlength="20"
                                   pattern="[0-9]+"
                                   title="Hanya angka yang diperbolehkan"
                                   required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Maksimal 20 karakter, hanya angka</small>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address"
                                  name="address"
                                  rows="3"
                                  placeholder="Jl. Supplier No. 1, Jakarta"
                                  required>{{ old('address') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show validation errors with SweetAlert
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal!',
        html: `<ul class="text-start mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>`,
        confirmButtonColor: '#667eea'
    });
    @endif

    $(document).ready(function() {
        // Auto-capitalize untuk nama (huruf pertama setiap kata)
        $('.auto-capitalize').on('input', function() {
            let value = $(this).val();
            // Capitalize first letter of each word
            value = value.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
            $(this).val(value);
        });

        // Numbers only untuk telepon (hanya angka)
        $('.numbers-only').on('input', function() {
            let value = $(this).val();
            // Remove non-numeric characters
            value = value.replace(/[^0-9]/g, '');
            $(this).val(value);
        });
    });
</script>
@endpush
