@extends('layouts.dashboard')

@section('title', 'History Order - Arifa Jaya POS')
@section('page-title', 'History Order')

@section('breadcrumb')
<li class="breadcrumb-item active">History Order</li>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $orders->count() }}</h3>
                    <p>Total Transaksi</p>
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
                    <h3>Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-card-body">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $orders->sum(function($o) { return $o->details->sum('order_quantity'); }) }}</h3>
                    <p>Item Terjual</p>
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
                    <h3>{{ $orders->whereNotNull('membership_id')->count() }}</h3>
                    <p>Transaksi Member</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h5><i class="fas fa-history me-2"></i>Riwayat Transaksi</h5>
    </div>
    <div class="data-card-body">
        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-calendar me-1"></i>Dari Tanggal</label>
                    <input type="date" id="filterDateFrom" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-calendar-check me-1"></i>Sampai Tanggal</label>
                    <input type="date" id="filterDateTo" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-credit-card me-1"></i>Metode Pembayaran</label>
                    <select id="filterPayment" class="form-select">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('payment') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ request('payment') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ request('payment') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-secondary w-100 form-control" id="btnReset">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive">
            <table id="historyTable" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Kode Order</th>
                        <th width="15%">Tanggal</th>
                        <th width="18%">Member</th>
                        <th width="10%">Item</th>
                        <th width="15%">Total</th>
                        <th width="12%">Pembayaran</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <code class="order-code">{{ $order->order_code }}</code>
                        </td>
                        <td data-order="{{ $order->order_date }}" data-date="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}">
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
                            <span class="badge bg-light text-dark">{{ $order->details->sum('order_quantity') }} item</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td data-payment="{{ $order->payment_method }}">
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
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('cashier.history.detail', $order->id) }}" class="btn-action btn-detail" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Table ID specific styling */
    #historyTable thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 15px 12px;
    }
    #historyTable thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    #historyTable thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    #historyTable tbody tr {
        transition: all 0.2s ease;
    }
    #historyTable tbody tr:hover {
        background: #f8f9ff;
    }
    #historyTable tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Custom filtering function untuk rentang tanggal dan metode pembayaran
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var filterDateFrom = $('#filterDateFrom').val();
            var filterDateTo = $('#filterDateTo').val();
            var filterPayment = $('#filterPayment').val();

            // Ambil data dari row
            var row = $(settings.aoData[dataIndex].nTr);
            var rowDate = row.find('td:eq(2)').data('date');
            var rowPayment = row.find('td:eq(6)').data('payment');

            // Filter rentang tanggal
            if (filterDateFrom && rowDate < filterDateFrom) {
                return false;
            }
            if (filterDateTo && rowDate > filterDateTo) {
                return false;
            }

            // Filter metode pembayaran
            if (filterPayment && rowPayment !== filterPayment) {
                return false;
            }

            return true;
        });

        var table = $('#historyTable').DataTable({
            responsive: true,
            language: {
                search: "<i class='fas fa-search'></i>",
                searchPlaceholder: "Cari transaksi...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "<div class='empty-state py-4'><i class='fas fa-search fa-3x text-muted mb-3'></i><h6 class='text-muted'>Data Tidak Ditemukan</h6><p class='text-muted small mb-0'>Tidak ada transaksi yang cocok dengan filter atau pencarian Anda</p></div>",
                emptyTable: "<div class='empty-state py-4'><i class='fas fa-inbox fa-3x text-muted mb-3'></i><h6 class='text-muted'>Tidak Ada Transaksi</h6><p class='text-muted small mb-0'>Belum ada transaksi tersedia</p></div>",
                paginate: {
                    first: "<i class='fas fa-angle-double-left'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    previous: "<i class='fas fa-angle-left'></i>"
                }
            },
            order: [[2, 'desc']],
            dom: '<"row align-items-center mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row align-items-center mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });

        // Filter otomatis saat input berubah
        $('#filterDateFrom, #filterDateTo, #filterPayment').on('change', function() {
            table.draw();
        });

        // Validasi rentang tanggal - "Dari Tanggal" tidak boleh melebihi "Sampai Tanggal"
        $('#filterDateFrom').on('change', function() {
            var fromDate = $(this).val();
            if (fromDate) {
                $('#filterDateTo').attr('min', fromDate);
                // Jika "Sampai Tanggal" sudah diisi dan lebih kecil dari "Dari Tanggal", reset
                var toDate = $('#filterDateTo').val();
                if (toDate && toDate < fromDate) {
                    $('#filterDateTo').val(fromDate);
                }
            } else {
                $('#filterDateTo').removeAttr('min');
            }
        });

        // Validasi rentang tanggal - "Sampai Tanggal" tidak boleh kurang dari "Dari Tanggal"
        $('#filterDateTo').on('change', function() {
            var toDate = $(this).val();
            if (toDate) {
                $('#filterDateFrom').attr('max', toDate);
                // Jika "Dari Tanggal" sudah diisi dan lebih besar dari "Sampai Tanggal", reset
                var fromDate = $('#filterDateFrom').val();
                if (fromDate && fromDate > toDate) {
                    $('#filterDateFrom').val(toDate);
                }
            } else {
                $('#filterDateFrom').removeAttr('max');
            }
        });

        $('#btnReset').click(function() {
            $('#filterDateFrom').val('').removeAttr('max');
            $('#filterDateTo').val('').removeAttr('min');
            $('#filterPayment').val('');
            table.draw();
        });

        // Initial draw dengan filter dari URL (jika ada)
        @if(request('date_from') || request('date_to') || request('payment'))
        table.draw();
        @endif
    });
</script>
@endpush
