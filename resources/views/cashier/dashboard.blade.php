@extends('layouts.dashboard')

@section('title', 'Cashier')
@section('page-title', 'Dashboard Kasir')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Order Hari Ini</div>
                    <div class="stat-value">{{ number_format($todayOrders) }}</div>
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
                    <div class="stat-title">Income Hari Ini</div>
                    <div class="stat-value">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info me-3">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Produk</div>
                    <div class="stat-value">{{ number_format($totalProducts) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Member</div>
                    <div class="stat-value">{{ number_format($totalMemberships) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Welcome Card --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-body text-center py-5">
                <div class="welcome-icon mb-3">
                    <i class="fas fa-cash-register fa-4x text-primary"></i>
                </div>
                <h3>Selamat Datang, {{ Auth::user()->first_name }}!</h3>
                <p class="text-muted mb-0">Anda login sebagai <strong>Kasir</strong> di {{ Auth::user()->store->name ?? 'Toko' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-history me-2"></i>Order Terbaru</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><code>{{ $order->order_number ?? 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y H:i') }}</td>
                                <td>{{ $order->membership->name ?? 'Non-Member' }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    Belum ada order hari ini
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
    .welcome-icon {
        opacity: 0.8;
    }
</style>
@endpush
