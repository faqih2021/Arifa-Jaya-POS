@extends('layouts.dashboard')

@section('title', 'Superadmin')
@section('page-title', 'Dashboard Income')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard Income</li>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary me-3">
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
                <div class="stat-icon bg-success me-3">
                    <i class="fas fa-calendar-day"></i>
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
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Income Bulan Ini</div>
                    <div class="stat-value">Rp {{ number_format($monthIncome, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning me-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> {{ $todayOrders }} hari ini
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Income per Store --}}
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Income 7 Hari Terakhir</h5>
            </div>
            <div class="data-card-body">
                <div class="chart-container">
                    <canvas id="dailyIncomeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="data-card h-100">
            <div class="data-card-header">
                <h5><i class="fas fa-store me-2"></i>Income per Toko</h5>
            </div>
            <div class="data-card-body">
                @foreach($storeIncomes as $storeData)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <strong>{{ $storeData['store']->name }}</strong>
                        <div class="mt-1">
                            <a href="{{ route('superadmin.sales.history', $storeData['store']->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>See More
                            </a>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary">Rp {{ number_format($storeData['income'], 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach

                @if(count($storeIncomes) == 0)
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Belum ada data income</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Store Income Chart --}}
<div class="row">
    <div class="col-12">
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-chart-pie me-2"></i>Distribusi Income per Toko</h5>
            </div>
            <div class="data-card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="storeIncomeChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Toko</th>
                                    <th class="text-end">Income</th>
                                    <th class="text-end">Persentase</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($storeIncomes as $storeData)
                                <tr>
                                    <td>{{ $storeData['store']->name }}</td>
                                    <td class="text-end">Rp {{ number_format($storeData['income'], 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        {{ $totalIncome > 0 ? number_format(($storeData['income'] / $totalIncome) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('superadmin.sales.history', $storeData['store']->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Daily Income Chart
    const dailyCtx = document.getElementById('dailyIncomeChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($dailyIncomes)) !!},
            datasets: [{
                label: 'Income',
                data: {!! json_encode(array_values($dailyIncomes)) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Store Income Pie Chart
    const storeCtx = document.getElementById('storeIncomeChart').getContext('2d');
    new Chart(storeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(collect($storeIncomes)->pluck('store.name')) !!},
            datasets: [{
                data: {!! json_encode(collect($storeIncomes)->pluck('income')) !!},
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#11998e',
                    '#38ef7d',
                    '#f093fb',
                    '#f5576c'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
