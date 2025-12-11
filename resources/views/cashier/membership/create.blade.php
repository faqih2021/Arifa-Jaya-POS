@extends('layouts.dashboard')

@section('title', 'Tambah Member - Arifa Jaya POS')
@section('page-title', 'Tambah Member')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cashier.membership.index') }}">Membership</a></li>
<li class="breadcrumb-item active">Tambah Member</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Tambah Member Baru</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('cashier.membership.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror auto-capitalize"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap"
                                   maxlength="20"
                                   pattern="[A-Za-z\s]+"
                                   title="Nama hanya boleh berisi huruf"
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Kode member akan dibuat otomatis (Maks. 20 karakter, hanya huruf)</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror numbers-only"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="08123456789"
                                   maxlength="20"
                                   pattern="[0-9]+"
                                   title="Nomor telepon hanya boleh berisi angka"
                                   required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Maks. 20 karakter, hanya angka</small>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Alamat</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cashier.membership.index') }}" class="btn btn-secondary">
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
    // Auto capitalize name
    $(document).on('input', '.auto-capitalize', function() {
        let value = $(this).val();
        // Remove non-letter characters except spaces
        value = value.replace(/[^A-Za-z\s]/g, '');
        // Capitalize each word
        value = value.replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
        $(this).val(value);
    });

    // Numbers only for phone
    $(document).on('input', '.numbers-only', function() {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        $(this).val(value);
    });

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
</script>
@endpush
