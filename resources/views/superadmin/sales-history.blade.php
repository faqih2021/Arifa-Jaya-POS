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
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <h3 style="font-size: 1.2rem;">{{ $store->name }}</h3>
                    <p>Toko</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-success">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <p>Total Income</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($totalOrders) }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <h3>Rp {{ $totalOrders > 0 ? number_format($totalIncome / $totalOrders, 0, ',', '.') : 0 }}</h3>
                    <p>Rata-rata per Order</p>
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
                                <th>Pembayaran</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="order-code">{{ $order->order_code }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td>{{ $order->cashierUser->first_name ?? '-' }} {{ $order->cashierUser->last_name ?? '' }}</td>
                                <td>
                                    @if($order->membership)
                                        <span class="member-badge"><i class="fas fa-crown me-1"></i>{{ $order->membership->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->payment_method)
                                        @case('cash')
                                            <span class="payment-badge payment-cash"><i class="fas fa-money-bill-wave me-1"></i>Cash</span>
                                            @break
                                        @case('qris')
                                            <span class="payment-badge payment-qris"><i class="fas fa-qrcode me-1"></i>QRIS</span>
                                            @break
                                        @case('transfer')
                                            <span class="payment-badge payment-transfer"><i class="fas fa-university me-1"></i>Transfer</span>
                                            @break
                                        @default
                                            <span class="payment-badge" style="background: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">{{ $order->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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
