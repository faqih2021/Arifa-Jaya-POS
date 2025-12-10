@extends('layouts.dashboard')

@section('title', 'History Penjualan ' . $store->name . ' - Arifa Jaya POS')
@section('page-title', 'History Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard Income</a></li>
<li class="breadcrumb-item active">{{ $store->name }}</li>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Toko</div>
                    <div class="stat-value" style="font-size: 1.2rem;">{{ $store->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success me-3">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Income</div>
                    <div class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-check-circle"></i> {{ $paidOrders }} paid
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-value">{{ number_format($pendingOrders) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Orders Table --}}
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-history me-2"></i>History Penjualan - {{ $store->name }}</h5>
                <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Order</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Member</th>
                                <th>Metode Bayar</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $order->order_code }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td>{{ $order->cashierUser->first_name ?? '-' }} {{ $order->cashierUser->last_name ?? '' }}</td>
                                <td>
                                    @if($order->membership)
                                        <span class="badge bg-info">{{ $order->membership->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->payment_method)
                                        @case('cash')
                                            <span class="badge bg-success"><i class="fas fa-money-bill me-1"></i>Cash</span>
                                            @break
                                        @case('card')
                                            <span class="badge bg-primary"><i class="fas fa-credit-card me-1"></i>Card</span>
                                            @break
                                        @case('e-wallet')
                                            <span class="badge bg-info"><i class="fas fa-wallet me-1"></i>E-Wallet</span>
                                            @break
                                        @case('transfer')
                                            <span class="badge bg-secondary"><i class="fas fa-university me-1"></i>Transfer</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @switch($order->payment_status)
                                        @case('paid')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Paid</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                            @break
                                        @case('declined')
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Declined</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada data penjualan untuk toko ini
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
        $('#ordersTable').DataTable({
            pageLength: 25,
            order: [[2, 'desc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ditemukan data yang cocok",
                emptyTable: "Tidak ada data penjualan",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush
