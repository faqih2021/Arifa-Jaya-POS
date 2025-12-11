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
            <div class="data-card-header">
                <h5><i class="fas fa-clipboard-list me-2"></i>Permintaan Stok Cabang</h5>
            </div>
            <div class="data-card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="stockRequestsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Dari Gudang</th>
                                <th>Ke Gudang</th>
                                <th>Diminta Oleh</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockRequests as $index => $request)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $request->fromWarehouse->name ?? '-' }}</td>
                                <td>{{ $request->toWarehouse->name ?? '-' }}</td>
                                <td>{{ $request->requestedBy->first_name ?? '-' }} {{ $request->requestedBy->last_name ?? '' }}</td>
                                <td>
                                    @switch($request->status)
                                        @case('pending')
                                            <span class="badge bg-warning">
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
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('storage.stock-requests.show', $request) }}" class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($request->status === 'pending')
                                        <form action="{{ route('storage.stock-requests.approve', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menyetujui permintaan ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Reject Modal --}}
                            @if($request->status === 'pending')
                            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Permintaan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('storage.stock-requests.reject', $request) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                                                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stockRequestsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [[1, 'desc']]
        });
    });
</script>
@endpush
