@extends('layouts.dashboard')

@section('title', 'Stock Request History - Storage')
@section('page-title', 'Stock Request')

@section('breadcrumb')
<li class="breadcrumb-item active">Stock Request</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-paper-plane me-2"></i>Riwayat Permintaan Stok</h5>
                <a href="{{ route('storage.branch.request.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Buat Request Baru
                </a>
            </div>
            <div class="data-card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="requestsTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Request</th>
                                <th>Dari Gudang</th>
                                <th>Ke Gudang</th>
                                <th>Jumlah Item</th>
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
                                <td>
                                    <span class="badge bg-secondary">{{ $request->details->count() }} item</span>
                                </td>
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
                                    <a href="{{ route('storage.branch.request.show', $request) }}" class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada riwayat permintaan stok
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
        $('#requestsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [[1, 'desc']]
        });
    });
</script>
@endpush
