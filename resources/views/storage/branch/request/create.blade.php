@extends('layouts.dashboard')

@section('title', 'Buat Request Stok - Storage')
@section('page-title', 'Buat Request Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.branch.request.index') }}">Stock Request</a></li>
<li class="breadcrumb-item active">Buat Request</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-paper-plane me-2"></i>Form Permintaan Stok</h5>
            </div>
            <div class="data-card-body">
                <form action="{{ route('storage.branch.request.store') }}" method="POST" id="requestForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="from_warehouse_id" class="form-label">Dari Gudang (Cabang) <span class="text-danger">*</span></label>
                            <select class="form-select @error('from_warehouse_id') is-invalid @enderror"
                                id="from_warehouse_id" name="from_warehouse_id" required>
                                <option value="">Pilih Gudang Cabang</option>
                                @foreach($fromWarehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('from_warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="to_warehouse_id" class="form-label">Ke Gudang (Utama) <span class="text-danger">*</span></label>
                            <select class="form-select @error('to_warehouse_id') is-invalid @enderror"
                                id="to_warehouse_id" name="to_warehouse_id" required>
                                <option value="">Pilih Gudang Utama</option>
                                @foreach($toWarehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('to_warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                            id="notes" name="notes" rows="2"
                            placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3"><i class="fas fa-list me-2"></i>Daftar Produk yang Diminta</h6>

                    <div id="itemsContainer">
                        <div class="row mb-3 item-row">
                            <div class="col-md-7">
                                <label class="form-label">Produk <span class="text-danger">*</span></label>
                                <select class="form-select" name="items[0][product_id]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->product_code }} - {{ $product->name }} ({{ $product->unit }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="items[0][quantity]" min="1" value="1" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove-item" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mb-4" id="addItemBtn">
                        <i class="fas fa-plus me-1"></i> Tambah Produk
                    </button>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('storage.branch.request.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Kirim Request
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
                    <h6><i class="fas fa-lightbulb me-2"></i>Panduan:</h6>
                    <ul class="mb-0 ps-3 small">
                        <li>Pilih gudang tujuan (gudang utama)</li>
                        <li>Tambahkan produk yang ingin diminta</li>
                        <li>Masukkan jumlah yang dibutuhkan</li>
                        <li>Request akan dikirim ke admin gudang utama untuk di-approve</li>
                        <li>Pantau status request di halaman riwayat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let itemIndex = 1;

        // Add new item row
        $('#addItemBtn').click(function() {
            const newRow = `
                <div class="row mb-3 item-row">
                    <div class="col-md-7">
                        <select class="form-select" name="items[${itemIndex}][product_id]" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->product_code }} - {{ $product->name }} ({{ $product->unit }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="items[${itemIndex}][quantity]" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#itemsContainer').append(newRow);
            itemIndex++;
            updateRemoveButtons();
        });

        // Remove item row
        $(document).on('click', '.btn-remove-item', function() {
            $(this).closest('.item-row').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const rows = $('.item-row');
            if (rows.length === 1) {
                rows.find('.btn-remove-item').prop('disabled', true);
            } else {
                rows.find('.btn-remove-item').prop('disabled', false);
            }
        }
    });
</script>
@endpush
