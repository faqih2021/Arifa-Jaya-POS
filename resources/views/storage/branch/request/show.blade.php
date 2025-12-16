@extends('layouts.dashboard')

@section('title', 'Detail Request Stok - Storage')
@section('page-title', 'Detail Request Stok')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('storage.branch.request.index') }}">Stock Request</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-clipboard-list me-2"></i>Detail Permintaan Stok</h5>
                @switch($stockRequest->status)
                    @case('pending')
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock me-1"></i>Menunggu Persetujuan
                        </span>
                        @break
                    @case('approved')
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check me-1"></i>Disetujui
                        </span>
                        @break
                    @case('rejected')
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-times me-1"></i>Ditolak
                        </span>
                        @break
                    @case('completed')
                        <span class="badge bg-info fs-6">
                            <i class="fas fa-check-double me-1"></i>Selesai
                        </span>
                        @break
                @endswitch
            </div>
            <div class="data-card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">ID Request</td>
                                <td class="border-0"><strong>#{{ $stockRequest->id }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Tanggal Request</td>
                                <td class="border-0">{{ $stockRequest->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Diminta Oleh</td>
                                <td class="border-0">{{ $stockRequest->requestedByUser->first_name ?? '' }} {{ $stockRequest->requestedByUser->last_name ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Dari Gudang</td>
                                <td class="border-0"><strong>{{ $stockRequest->fromWarehouse->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Ke Gudang</td>
                                <td class="border-0"><strong>{{ $stockRequest->toWarehouse->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Catatan</td>
                                <td class="border-0">{{ $stockRequest->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3"><i class="fas fa-list me-2"></i>Daftar Produk yang Diminta</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Satuan</th>
                                <th class="text-center">Jumlah Diminta</th>
                                <th class="text-center">Jumlah Disetujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockRequest->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $detail->product->product_code ?? '-' }}</code></td>
                                <td>{{ $detail->product->name ?? '-' }}</td>
                                <td>{{ $detail->product->unit ?? '-' }}</td>
                                <td class="text-center"><strong>{{ number_format($detail->requested_quantity) }}</strong></td>
                                <td class="text-center">
                                    @if($stockRequest->status === 'pending')
                                        <span class="text-muted">-</span>
                                    @else
                                        <strong class="text-success">{{ number_format($detail->approved_quantity) }}</strong>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada detail produk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($stockRequest->status === 'rejected' && $stockRequest->rejection_reason)
                <div class="alert alert-danger mt-4">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Alasan Penolakan:</h6>
                    <p class="mb-0">{{ $stockRequest->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Status Timeline</h5>
            </div>
            <div class="data-card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Request Dibuat</h6>
                            <small class="text-muted">{{ $stockRequest->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>

                    @if($stockRequest->status === 'approved' || $stockRequest->status === 'completed')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Disetujui</h6>
                            <small class="text-muted">{{ $stockRequest->approved_at ? \Carbon\Carbon::parse($stockRequest->approved_at)->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    </div>
                    @elseif($stockRequest->status === 'rejected')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Ditolak</h6>
                            <small class="text-muted">{{ $stockRequest->rejected_at ? \Carbon\Carbon::parse($stockRequest->rejected_at)->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    </div>
                    @elseif($stockRequest->status === 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Menunggu Persetujuan</h6>
                            <small class="text-muted">Dari gudang utama</small>
                        </div>
                    </div>
                    @endif

                    @if($stockRequest->status === 'completed')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Selesai</h6>
                            <small class="text-muted">Stok telah diterima</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('storage.branch.request.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -24px;
        top: 10px;
        height: 100%;
        width: 2px;
        background: #dee2e6;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
    }
</style>
@endpush
