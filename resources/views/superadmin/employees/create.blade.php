@extends('layouts.dashboard')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.employees.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Tambah Karyawan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-user-plus me-2"></i>Tambah Karyawan Baru</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('superadmin.employees.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control auto-capitalize letters-only @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   maxlength="20"
                                   pattern="[A-Za-z\s]+"
                                   title="Hanya huruf dan spasi yang diperbolehkan"
                                   required>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 20 karakter, hanya huruf</small>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nama Belakang <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control auto-capitalize letters-only @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   maxlength="20"
                                   pattern="[A-Za-z\s]+"
                                   title="Hanya huruf dan spasi yang diperbolehkan"
                                   required>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 20 karakter, hanya huruf</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   id="username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   maxlength="20"
                                   required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Maksimal 20 karakter, harus unik dan akan digunakan untuk login</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('roles') is-invalid @enderror"
                                    id="roles"
                                    name="roles"
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="superadmin" {{ old('roles') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="cashier" {{ old('roles') == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                <option value="storage" {{ old('roles') == 'storage' ? 'selected' : '' }}>Gudang</option>
                            </select>
                            @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="store_id" class="form-label">Toko <span class="text-danger store-required">*</span></label>
                            <select class="form-select @error('store_id') is-invalid @enderror"
                                    id="store_id"
                                    name="store_id">
                                <option value="">Pilih Toko</option>
                                <option value="" class="no-store-option" style="display: none;" {{ old('store_id') === '' && old('roles') == 'superadmin' ? 'selected' : '' }}>
                                    -- Tidak Terikat Toko --
                                </option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('store_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted store-hint" style="display: none;">Superadmin tidak wajib terikat toko</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.employees.index') }}" class="btn btn-secondary">
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

    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Handle role change for store requirement
    $(document).ready(function() {
        const rolesSelect = $('#roles');
        const storeSelect = $('#store_id');
        const storeRequired = $('.store-required');
        const storeHint = $('.store-hint');
        const noStoreOption = $('.no-store-option');

        function updateStoreRequirement() {
            const selectedRole = rolesSelect.val();

            if (selectedRole === 'superadmin') {
                // Superadmin: toko tidak wajib, tampilkan opsi "Tidak Terikat Toko"
                storeRequired.hide();
                storeHint.show();
                noStoreOption.show();
                storeSelect.prop('required', false);
            } else {
                // Cashier/Storage: toko wajib
                storeRequired.show();
                storeHint.hide();
                noStoreOption.hide();
                storeSelect.prop('required', true);

                // Jika sedang memilih "Tidak Terikat Toko", reset ke "Pilih Toko"
                if (storeSelect.val() === '' && storeSelect.find('option:selected').hasClass('no-store-option')) {
                    storeSelect.val('');
                }
            }
        }

        // Run on page load
        updateStoreRequirement();

        // Run on role change
        rolesSelect.on('change', updateStoreRequirement);

        // Auto-capitalize untuk nama (huruf pertama setiap kata)
        $('.auto-capitalize').on('input', function() {
            let value = $(this).val();
            // Capitalize first letter of each word
            value = value.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
            $(this).val(value);
        });

        // Letters only untuk nama (hanya huruf dan spasi)
        $('.letters-only').on('input', function() {
            let value = $(this).val();
            // Remove non-letter and non-space characters
            value = value.replace(/[^A-Za-z\s]/g, '');
            $(this).val(value);
        });
    });
</script>
@endpush
