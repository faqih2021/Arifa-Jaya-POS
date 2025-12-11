@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir - Arifa Jaya POS')
@section('page-title', 'Dashboard Kasir')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="welcome-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">Selamat Datang, {{ Auth::user()->first_name }}! 👋</h3>
                        <p class="mb-0 opacity-85">
                            Anda login sebagai <strong>Kasir</strong> di <strong>{{ Auth::user()->store->name ?? 'Toko' }}</strong>
                        </p>
                        <p class="mb-0 opacity-75 small mt-2">
                            <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="fas fa-cash-register fa-5x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($totalTransactions) }}</h3>
                    <p>Total Transaksi</p>
                    <span class="stat-badge">
                        <i class="fas fa-calendar-day me-1"></i>{{ $todayOrders }} hari ini
                    </span>
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
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Rp {{ number_format($todayIncome, 0, ',', '.') }}</h3>
                    <p>Income Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($totalMemberships) }}</h3>
                    <p>Total Member</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-history me-2"></i>Transaksi Terbaru</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="recentOrdersTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Order</th>
                                <th width="15%">Tanggal</th>
                                <th width="20%">Member</th>
                                <th width="15%">Pembayaran</th>
                                <th width="20%" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <code class="order-code">{{ $order->order_code }}</code>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }} WIB</small>
                                    </div>
                                </td>
                                <td>
                                    @if($order->membership)
                                    <div class="member-badge">
                                        <i class="fas fa-crown text-warning me-1"></i>
                                        <span>{{ $order->membership->name }}</span>
                                    </div>
                                    @else
                                    <span class="text-muted">Non-Member</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->payment_method)
                                        @case('cash')
                                            <span class="payment-badge payment-cash">
                                                <i class="fas fa-money-bill-wave me-1"></i>Cash
                                            </span>
                                            @break
                                        @case('qris')
                                            <span class="payment-badge payment-qris">
                                                <i class="fas fa-qrcode me-1"></i>QRIS
                                            </span>
                                            @break
                                        @case('transfer')
                                            <span class="payment-badge payment-transfer">
                                                <i class="fas fa-university me-1"></i>Transfer
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada transaksi di toko ini</p>
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

@push('styles')
<style>
    /* Table ID specific styling */
    #recentOrdersTable thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px 12px;
    }
    #recentOrdersTable thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    #recentOrdersTable thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    #recentOrdersTable tbody tr {
        transition: all 0.2s ease;
    }
    #recentOrdersTable tbody tr:hover {
        background: #f8f9ff;
    }
    #recentOrdersTable tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endpush
