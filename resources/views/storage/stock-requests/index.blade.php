@extends('layouts.dashboard')

@section('title', 'Branch Stock Request - Storage')
@section('page-title', 'Branch Stock Request')

@section('breadcrumb')
<li class="breadcrumb-item active">Branch Stock Request</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-clipboard-list me-2"></i>Daftar Permintaan Stok</h5>
            </div>
            <div class="data-card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="stockRequestsTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Tanggal Request</th>
                                <th>Dari Gudang</th>
                                <th>Ke Gudang</th>
                                <th>Diminta Oleh</th>
                                <th width="12%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockRequests as $index => $request)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $request->request_date->format('d/m/Y') }}</td>
                                <td>{{ $request->fromWarehouse->name ?? '-' }}</td>
                                <td>{{ $request->toWarehouse->name ?? '-' }}</td>
                                <td>{{ $request->requestedByUser->first_name ?? '-' }} {{ $request->requestedByUser->last_name ?? '' }}</td>
                                <td>
                                    @switch($request->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-info">
                                                <i class="fas fa-check-double me-1"></i>Completed
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $request->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $request->id }}"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                                    Belum ada permintaan stok
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Modals --}}
@foreach($stockRequests as $request)
<div class="modal fade" id="detailModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i>Detail Permintaan Stok #{{ $request->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Request Info --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Tanggal Request:</td>
                                <td class="border-0"><strong>{{ $request->request_date->format('d/m/Y') }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Dari Gudang:</td>
                                <td class="border-0"><strong>{{ $request->fromWarehouse->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Ke Gudang:</td>
                                <td class="border-0"><strong>{{ $request->toWarehouse->name ?? '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted border-0" width="40%">Diminta Oleh:</td>
                                <td class="border-0"><strong>{{ $request->requestedByUser->first_name ?? '-' }} {{ $request->requestedByUser->last_name ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted border-0">Status:</td>
                                <td class="border-0">
                                    @switch($request->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-info">Completed</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                            @if($request->approved_date)
                            <tr>
                                <td class="text-muted border-0">Tanggal Approve:</td>
                                <td class="border-0"><strong>{{ $request->approved_date->format('d/m/Y H:i') }}</strong></td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted border-0">Catatan:</td>
                                <td class="border-0">{{ $request->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Product Details --}}
                @if($request->status === 'pending')
                <form action="{{ route('storage.stock-requests.approve', $request) }}" method="POST" id="approveForm{{ $request->id }}">
                    @csrf
                    @method('PUT')
                @endif

                <h6 class="mb-3"><i class="fas fa-box me-2"></i>Detail Produk</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Produk</th>
                                <th>Nama Produk</th>
                                <th width="15%">Qty Diminta</th>
                                <th width="18%">Qty Disetujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($request->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->product->product_code ?? '-' }}</td>
                                <td>{{ $detail->product->name ?? '-' }}</td>
                                <td class="text-center">{{ $detail->requested_quantity }}</td>
                                <td class="text-center">
                                    @if($request->status === 'pending')
                                        <input type="hidden" name="details[{{ $detail->id }}][id]" value="{{ $detail->id }}">
                                        <input type="number"
                                            class="form-control form-control-sm text-center approved-qty-input"
                                            name="details[{{ $detail->id }}][approved_quantity]"
                                            value="{{ $detail->requested_quantity }}"
                                            min="0"
                                            step="1"
                                            style="width: 100px; display: inline-block;"
                                            required>
                                    @else
                                        {{ $detail->approved_quantity }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada detail produk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($request->status === 'pending')
                </form>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>

                @if($request->status === 'pending')
                {{-- Approve Button - submit the form with SweetAlert --}}
                <button type="button" class="btn btn-success btn-approve" data-form-id="approveForm{{ $request->id }}">
                    <i class="fas fa-check me-1"></i> Approve
                </button>

                {{-- Reject Button --}}
                <button type="button" class="btn btn-danger btn-reject" data-request-id="{{ $request->id }}">
                    <i class="fas fa-times me-1"></i> Reject
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
@if($request->status === 'pending')
<div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Permintaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('storage.stock-requests.reject', $request) }}" method="POST" id="rejectForm{{ $request->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3">Apakah Anda yakin ingin menolak permintaan stok ini?</p>
                    <div class="mb-3">
                        <label for="rejection_reason{{ $request->id }}" class="form-label">Alasan Penolakan (Opsional)</label>
                        <textarea class="form-control" id="rejection_reason{{ $request->id }}" name="rejection_reason" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-confirm-reject" data-form-id="rejectForm{{ $request->id }}">
                        <i class="fas fa-times me-1"></i> Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#stockRequestsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [6] }
        ]
    });

    // SweetAlert for Approve
    $('.btn-approve').on('click', function() {
        var formId = $(this).data('form-id');
        Swal.fire({
            title: 'Setujui Permintaan?',
            text: 'Pastikan qty yang disetujui sudah sesuai!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#' + formId).submit();
            }
        });
    });

    // SweetAlert for opening Reject Modal
    $('.btn-reject').on('click', function() {
        var requestId = $(this).data('request-id');
        // Close detail modal first
        $('#detailModal' + requestId).modal('hide');
        // Open reject modal
        setTimeout(function() {
            $('#rejectModal' + requestId).modal('show');
        }, 300);
    });

    // SweetAlert for Confirm Reject
    $('.btn-confirm-reject').on('click', function() {
        var formId = $(this).data('form-id');
        Swal.fire({
            title: 'Tolak Permintaan?',
            text: 'Permintaan stok akan ditolak!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-times"></i> Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#' + formId).submit();
            }
        });
    });
});
</script>
@endpush
