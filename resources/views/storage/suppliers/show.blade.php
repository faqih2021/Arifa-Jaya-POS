@extends('layouts.dashboard')

@section('title', 'Detail Supplier - Storage')
@section('page-title', 'Detail Supplier')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.suppliers.index') }}">Suppliers</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-truck me-2"></i>Informasi Supplier</h5>
                <div>
                    <a href="{{ route('storage.suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('storage.suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
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
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Kode Supplier</td>
                                <td class="border-0"><code class="fs-5">{{ $supplier->supplier_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Nama Supplier</td>
                                <td class="border-0"><strong>{{ $supplier->name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Telepon</td>
                                <td class="border-0">{{ $supplier->phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Alamat</td>
                                <td class="border-0">{{ $supplier->address }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Dibuat</td>
                                <td class="border-0">{{ $supplier->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Terakhir Update</td>
                                <td class="border-0">{{ $supplier->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('storage.suppliers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Supplier
    </a>
</div>
@endsection
