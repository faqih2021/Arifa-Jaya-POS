@extends('layouts.dashboard')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.employees.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Edit Karyawan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-user-edit me-2"></i>Edit Karyawan: {{ $employee->first_name }} {{ $employee->last_name }}</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('superadmin.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Nama Depan <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   value="{{ old('first_name', $employee->first_name) }}"
                                   required>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Nama Belakang <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name', $employee->last_name) }}"
                                   required>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('username', $employee->username) }}"
                                   required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation">
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
                                <option value="superadmin" {{ old('roles', $employee->roles) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="cashier" {{ old('roles', $employee->roles) == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                <option value="storage" {{ old('roles', $employee->roles) == 'storage' ? 'selected' : '' }}>Gudang</option>
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
                                <option value="" class="no-store-option" {{ $employee->store_id === null ? 'selected' : '' }}>
                                    -- Tidak Terikat Toko --
                                </option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id', $employee->store_id) == $store->id ? 'selected' : '' }}>
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
                            <i class="fas fa-save me-1"></i> Update
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
                if (storeSelect.val() === '' || storeSelect.find('option:selected').hasClass('no-store-option')) {
                    storeSelect.val('');
                }
            }
        }

        // Run on page load
        updateStoreRequirement();

        // Run on role change
        rolesSelect.on('change', updateStoreRequirement);
    });
</script>
@endpush
