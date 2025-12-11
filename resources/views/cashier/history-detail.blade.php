@extends('layouts.dashboard')

@section('title', 'Detail Order - Arifa Jaya POS')
@section('page-title', 'Detail Order')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cashier.history') }}">History Order</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <!-- Order Info -->
    <div class="col-lg-4">
        <div class="data-card mb-4">
            <div class="data-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Informasi Order</h5>
            </div>
            <div class="data-card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Kode Order</strong></td>
                        <td>{{ $order->order_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Transaksi</strong></td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('H:i:s') }} WIB</td>
                    </tr>
                    <tr>
                        <td><strong>Kasir</strong></td>
                        <td>
                            @php
                                $firstName = $order->cashierUser->first_name ?? 'Unknown';
                                $lastName = $order->cashierUser->last_name ?? '';
                                $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            @endphp
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-size: 13px; font-weight: 600;">
                                    {{ $initials }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $firstName }}</span>
                                    @if($lastName)
                                    <small class="text-muted">{{ $lastName }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Pembayaran</strong></td>
                        <td>
                            @switch($order->payment_method)
                                @case('cash')
                                    <span class="badge bg-success"><i class="fas fa-money-bill-wave me-1"></i>Cash</span>
                                    @break
                                @case('qris')
                                    <span class="badge bg-primary"><i class="fas fa-qrcode me-1"></i>QRIS</span>
                                    @break
                                @case('transfer')
                                    <span class="badge bg-info"><i class="fas fa-university me-1"></i>Transfer</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Member Info -->
        <div class="data-card mb-4">
            <div class="data-card-header">
                <h5><i class="fas fa-user-tag me-2"></i>Informasi Member</h5>
            </div>
            <div class="data-card-body">
                @if($order->membership)
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Kode</strong></td>
                        <td>{{ $order->membership->membership_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>{{ $order->membership->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Telepon</strong></td>
                        <td>{{ $order->membership->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diskon</strong></td>
                        <td><span class="badge bg-success">{{ $order->membership->discount_percentage }}%</span></td>
                    </tr>
                </table>
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-user-times fa-3x mb-2"></i>
                    <p class="mb-0">Non-Member</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="col-lg-8">
        <div class="data-card mb-4">
            <div class="data-card-header">
                <h5><i class="fas fa-shopping-basket me-2"></i>Detail Item</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Produk</th>
                                <th width="15%">Harga Satuan</th>
                                <th width="10%">Qty</th>
                                <th width="20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $detail->product->name ?? 'Produk Dihapus' }}</strong>
                                    @if($detail->product)
                                    <br><small class="text-muted">SKU: {{ $detail->product->sku }}</small>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                <td><span class="badge bg-secondary">{{ $detail->order_quantity }}</span></td>
                                <td>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="data-card">
            <div class="data-card-header">
                <h5><i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran</h5>
            </div>
            <div class="data-card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->subtotal > $order->total_amount)
                            <tr class="text-danger">
                                <td><strong>Diskon Member</strong></td>
                                <td class="text-end">- Rp {{ number_format($order->subtotal - $order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="table-light">
                                <td><h5 class="mb-0"><strong>Total Bayar</strong></h5></td>
                                <td class="text-end">
                                    <h5 class="mb-0 text-primary">
                                        <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                    </h5>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('cashier.history') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary" onclick="printReceipt()">
                <i class="fas fa-print me-1"></i> Print Struk
            </button>
        </div>
    </div>
</div>

<!-- Print Template (Hidden) -->
<div id="printArea" style="display: none;">
    <div class="print-receipt">
        <div class="print-header">
            <h3>ARIFA JAYA</h3>
            <p>{{ $order->store->name ?? 'Toko' }}</p>
            <p>{{ $order->store->address ?? '' }}</p>
            <p>Telp: {{ $order->store->phone ?? '' }}</p>
        </div>
        <div class="print-divider"></div>
        <div class="print-info">
            <p>No: {{ $order->order_code }}</p>
            <p>Tgl: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</p>
            <p>Kasir: {{ ($order->cashierUser->first_name ?? '') . ' ' . ($order->cashierUser->last_name ?? '') }}</p>
            @if($order->membership)
            <p>Member: {{ $order->membership->name }}</p>
            @endif
        </div>
        <div class="print-divider"></div>
        <div class="print-items">
            @foreach($order->details as $detail)
            <div class="print-item">
                <p class="item-name">{{ $detail->product->name ?? 'Produk' }}</p>
                <p class="item-calc">{{ $detail->order_quantity }} x {{ number_format($detail->unit_price, 0, ',', '.') }} = {{ number_format($detail->total_price, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        <div class="print-divider"></div>
        <div class="print-summary">
            <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
            @if($order->subtotal > $order->total_amount)
            <p>Diskon: -Rp {{ number_format($order->subtotal - $order->total_amount, 0, ',', '.') }}</p>
            @endif
            <p class="total">TOTAL: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            <p>Bayar: {{ strtoupper($order->payment_method) }}</p>
        </div>
        <div class="print-divider"></div>
        <div class="print-footer">
            <p>Terima Kasih</p>
            <p>Selamat Berbelanja Kembali</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-borderless td {
        padding: 8px 0;
    }

    /* Print Receipt Styles */
    .print-receipt {
        width: 300px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        padding: 10px;
    }
    .print-receipt p {
        margin: 3px 0;
    }
    .print-header {
        text-align: center;
        margin-bottom: 10px;
    }
    .print-header h3 {
        margin: 0 0 5px 0;
        font-size: 18px;
    }
    .print-divider {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }
    .print-info, .print-summary {
        margin: 5px 0;
    }
    .print-item {
        margin: 5px 0;
    }
    .print-item .item-name {
        font-weight: 500;
    }
    .print-summary .total {
        font-weight: bold;
        font-size: 14px;
    }
    .print-footer {
        text-align: center;
        margin-top: 10px;
    }

    /* Print Media Query */
    @media print {
        body * {
            visibility: hidden !important;
        }
        #printArea, #printArea * {
            visibility: visible !important;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            display: block !important;
            width: 100%;
        }
        .print-receipt {
            margin: 0 auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function printReceipt() {
        // Buat window baru untuk print
        var printWindow = window.open('', '_blank', 'width=400,height=600');
        var printContent = document.getElementById('printArea').innerHTML;

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk - {{ $order->order_code }}</title>
                <style>
                    body {
                        margin: 0;
                        padding: 20px;
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                    }
                    .print-receipt {
                        width: 300px;
                        margin: 0 auto;
                    }
                    .print-receipt p {
                        margin: 3px 0;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 10px;
                    }
                    .print-header h3 {
                        margin: 0 0 5px 0;
                        font-size: 18px;
                    }
                    .print-divider {
                        border-top: 1px dashed #000;
                        margin: 8px 0;
                    }
                    .print-info, .print-summary {
                        margin: 5px 0;
                    }
                    .print-item {
                        margin: 5px 0;
                    }
                    .print-item .item-name {
                        font-weight: 500;
                    }
                    .print-summary .total {
                        font-weight: bold;
                        font-size: 14px;
                    }
                    .print-footer {
                        text-align: center;
                        margin-top: 10px;
                    }
                </style>
            </head>
            <body>
                ${printContent}
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                        // Fallback untuk browser yang tidak support onafterprint
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    };
                <\/script>
            </body>
            </html>
        `);

        printWindow.document.close();
    }
</script>
@endpush
