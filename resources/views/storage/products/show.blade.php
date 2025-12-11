@extends('layouts.dashboard')

@section('title', 'Detail Produk - Storage')
@section('page-title', 'Detail Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.products.index') }}">Products</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-box me-2"></i>Informasi Produk</h5>
                <div>
                    <a href="{{ route('storage.products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('storage.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="data-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Kode Produk</td>
                                <td><code class="fs-5">{{ $product->product_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Produk</td>
                                <td><strong>{{ $product->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Satuan</td>
                                <td>{{ $product->unit }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Deskripsi</td>
                                <td>{{ $product->description ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">Harga Modal</td>
                                <td><strong class="text-danger">Rp {{ number_format($product->actual_price, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Jual</td>
                                <td><strong class="text-success">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Margin Keuntungan</td>
                                <td>
                                    @php
                                        $margin = $product->selling_price - $product->actual_price;
                                        $marginPercent = $product->actual_price > 0 ? ($margin / $product->actual_price) * 100 : 0;
                                    @endphp
                                    <span class="badge bg-{{ $margin > 0 ? 'success' : 'danger' }}">
                                        Rp {{ number_format($margin, 0, ',', '.') }} ({{ number_format($marginPercent, 1) }}%)
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dibuat</td>
                                <td>{{ $product->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Update</td>
                                <td>{{ $product->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('storage.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
</div>
@endsection
