@extends('layouts.dashboard')

@section('title', 'Cart Order - Arifa Jaya POS')
@section('page-title', 'Cart Order')

@section('breadcrumb')
<li class="breadcrumb-item active">Cart Order</li>
@endsection

@section('content')
<div class="row">
    {{-- Products Section --}}
    <div class="col-lg-8 mb-4">
        <div class="data-card">
            <div class="data-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-boxes me-2"></i>Daftar Produk</h5>
                <input type="text" class="form-control form-control-sm" id="searchProduct" placeholder="Cari produk..." style="width: 200px;">
            </div>
            <div class="data-card-body">
                <div class="row" id="productList">
                    @forelse($products as $product)
                    @php
                        $stock = $product->warehouseStocks->first()->current_stock ?? 0;
                    @endphp
                    <div class="col-md-4 col-sm-6 mb-3 product-item" data-name="{{ strtolower($product->name) }}">
                        <div class="card h-100 product-card">
                            <div class="card-body text-center">
                                <div class="product-icon mb-2">
                                    <i class="fas fa-cubes fa-2x"></i>
                                </div>
                                <h6 class="card-title" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <p class="text-muted small mb-1 product-code"><code>{{ $product->product_code }}</code></p>
                                <p class="product-price mb-1">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                <p class="small mb-2 product-stock">
                                    <span class="badge {{ $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        Stok: {{ $stock }}
                                    </span>
                                </p>
                                <button class="btn btn-sm btn-primary add-to-cart"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-price="{{ $product->selling_price }}"
                                        data-stock="{{ $stock }}"
                                        {{ $stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada produk tersedia di toko ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Cart Section --}}
    <div class="col-lg-4 mb-4">
        <div class="cart-sidebar sticky-top" style="top: 20px;">
            <div class="cart-header">
                <h5><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
            </div>
            <div class="cart-body">
                <div id="cartItems">
                    {{-- Cart items will be rendered here --}}
                </div>

                <div class="cart-footer">
                    {{-- Member Selection by Code --}}
                    <div class="mb-3">
                        <label class="form-label">Kode Member (Opsional)</label>
                        <div class="input-group" id="memberInputGroup">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control" id="memberCodeInput"
                                   placeholder="Masukkan kode member..." maxlength="6"
                                   style="text-transform: uppercase;">
                            <button class="btn btn-outline-primary" type="button" id="checkMemberBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div id="memberInfo" class="mt-2" style="display: none;">
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">
                                    <i class="fas fa-check-circle me-1"></i>
                                </span>
                                <input type="text" class="form-control bg-success bg-opacity-10 text-success fw-semibold"
                                       id="memberBadge" readonly>
                                <button type="button" class="btn btn-outline-danger" id="clearMemberBtn">
                                    <i class="fas fa-times"></i> Hapus
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="memberSelect" value="">
                        <small class="text-muted">Member mendapatkan diskon 5%</small>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="payment-method-grid">
                            <label class="payment-option-card">
                                <input type="radio" name="paymentMethod" value="cash" checked>
                                <div class="payment-card-content">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Cash</span>
                                </div>
                            </label>
                            <label class="payment-option-card">
                                <input type="radio" name="paymentMethod" value="qris">
                                <div class="payment-card-content">
                                    <i class="fas fa-qrcode"></i>
                                    <span>QRIS</span>
                                </div>
                            </label>
                            <label class="payment-option-card">
                                <input type="radio" name="paymentMethod" value="transfer">
                                <div class="payment-card-content">
                                    <i class="fas fa-university"></i>
                                    <span>Transfer</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Totals --}}
                    <div class="cart-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="total-row discount" id="discountRow" style="display: none;">
                            <span>Diskon (<span id="discountPercent">0</span>%):</span>
                            <span>- Rp <span id="discountAmount">0</span></span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total:</span>
                            <span class="amount" id="grandTotal">Rp 0</span>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    <button class="btn-checkout" id="checkoutBtn" disabled>
                        <i class="fas fa-check-circle"></i>
                        <span>Proses Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cash Payment Modal --}}
<div class="modal fade" id="cashPaymentModal" tabindex="-1" aria-labelledby="cashPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="cashPaymentModalLabel">
                    <i class="fas fa-money-bill-wave me-2"></i>Pembayaran Cash
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-summary mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span id="modalSubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" id="modalDiscountRow" style="display: none !important;">
                        <span class="text-muted">Diskon (5%):</span>
                        <span class="text-danger">- Rp <span id="modalDiscount">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Total Bayar:</span>
                        <span class="fw-bold fs-4 text-primary" id="modalTotal">Rp 0</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Uang Diterima</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control form-control-lg text-end fw-bold"
                               id="cashReceived" placeholder="0" autocomplete="off">
                    </div>
                </div>

                <div class="quick-amount-buttons mb-3">
                    <label class="form-label fw-semibold">Uang Pas:</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="exact">Uang Pas</button>
                        <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="10000">10rb</button>
                        <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="20000">20rb</button>
                        <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="50000">50rb</button>
                        <button type="button" class="btn btn-outline-secondary quick-amount" data-amount="100000">100rb</button>
                    </div>
                </div>

                <div class="change-display p-3 rounded-3" id="changeDisplay" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5">Kembalian:</span>
                        <span class="fs-3 fw-bold text-success" id="changeAmount">Rp 0</span>
                    </div>
                </div>

                <div class="insufficient-display p-3 rounded-3 bg-danger bg-opacity-10 text-danger" id="insufficientDisplay" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5"><i class="fas fa-exclamation-triangle me-2"></i>Kurang:</span>
                        <span class="fs-3 fw-bold" id="insufficientAmount">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-outline-secondary btn-lg flex-fill btn-cancel-payment" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success btn-lg flex-fill" id="completeCashPayment" disabled>
                        <i class="fas fa-check-circle me-1"></i>Selesaikan Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- QRIS Payment Modal --}}
<div class="modal fade" id="qrisPaymentModal" tabindex="-1" aria-labelledby="qrisPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrisPaymentModalLabel">
                    <i class="fas fa-qrcode me-2"></i>Pembayaran QRIS
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="payment-summary mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span id="qrisModalSubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" id="qrisModalDiscountRow" style="display: none;">
                        <span class="text-muted">Diskon (5%):</span>
                        <span class="text-danger">- Rp <span id="qrisModalDiscount">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Total Bayar:</span>
                        <span class="fw-bold fs-4 text-primary" id="qrisModalTotal">Rp 0</span>
                    </div>
                </div>

                <div class="qris-code-container mb-4">
                    <div class="qris-wrapper p-3 bg-white rounded-3 d-inline-block shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=ARIFAJAYA-POS-QRIS-DUMMY"
                             alt="QRIS Code" class="img-fluid" style="width: 200px; height: 200px;">
                    </div>
                    <p class="text-muted mt-3 mb-0"><i class="fas fa-info-circle me-1"></i>Scan QR Code dengan aplikasi e-wallet atau mobile banking</p>
                </div>

                <div class="supported-payment mb-3">
                    <small class="text-muted d-block mb-2">Didukung oleh:</small>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <span class="badge bg-light text-dark px-3 py-2">GoPay</span>
                        <span class="badge bg-light text-dark px-3 py-2">OVO</span>
                        <span class="badge bg-light text-dark px-3 py-2">DANA</span>
                        <span class="badge bg-light text-dark px-3 py-2">ShopeePay</span>
                        <span class="badge bg-light text-dark px-3 py-2">LinkAja</span>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    <i class="fas fa-clock me-1"></i>Menunggu pembayaran...
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-outline-danger btn-lg flex-fill" id="declineQrisPayment" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batalkan
                    </button>
                    <button type="button" class="btn btn-success btn-lg flex-fill" id="acceptQrisPayment">
                        <i class="fas fa-check-circle me-1"></i>Pembayaran Diterima
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Transfer Payment Modal --}}
<div class="modal fade" id="transferPaymentModal" tabindex="-1" aria-labelledby="transferPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="transferPaymentModalLabel">
                    <i class="fas fa-university me-2"></i>Pembayaran Transfer Bank
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-summary mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span id="transferModalSubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" id="transferModalDiscountRow" style="display: none;">
                        <span class="text-muted">Diskon (5%):</span>
                        <span class="text-danger">- Rp <span id="transferModalDiscount">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Total Bayar:</span>
                        <span class="fw-bold fs-4 text-info" id="transferModalTotal">Rp 0</span>
                    </div>
                </div>

                <div class="bank-accounts">
                    <h6 class="mb-3"><i class="fas fa-landmark me-2"></i>Transfer ke Rekening:</h6>

                    <div class="bank-account-card mb-3 p-3 border rounded-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bank-logo me-3">
                                <div class="bg-primary text-white rounded px-2 py-1 fw-bold" style="font-size: 12px;">BCA</div>
                            </div>
                            <div>
                                <div class="fw-bold text-primary">Bank Central Asia</div>
                                <small class="text-muted">a.n. Arifa Jaya Store</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded">
                            <span class="fw-bold fs-5 font-monospace">1234567890</span>
                            <button class="btn btn-sm btn-outline-primary copy-rekening" data-rekening="1234567890">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bank-account-card mb-3 p-3 border rounded-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bank-logo me-3">
                                <div class="bg-warning text-dark rounded px-2 py-1 fw-bold" style="font-size: 12px;">Mandiri</div>
                            </div>
                            <div>
                                <div class="fw-bold text-warning">Bank Mandiri</div>
                                <small class="text-muted">a.n. Arifa Jaya Store</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded">
                            <span class="fw-bold fs-5 font-monospace">0987654321</span>
                            <button class="btn btn-sm btn-outline-warning copy-rekening" data-rekening="0987654321">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bank-account-card p-3 border rounded-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bank-logo me-3">
                                <div class="bg-info text-white rounded px-2 py-1 fw-bold" style="font-size: 12px;">BRI</div>
                            </div>
                            <div>
                                <div class="fw-bold text-info">Bank Rakyat Indonesia</div>
                                <small class="text-muted">a.n. Arifa Jaya Store</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded">
                            <span class="fw-bold fs-5 font-monospace">1122334455</span>
                            <button class="btn btn-sm btn-outline-info copy-rekening" data-rekening="1122334455">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>Pastikan nominal transfer sesuai dengan total bayar
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-outline-danger btn-lg flex-fill" id="declineTransferPayment" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batalkan
                    </button>
                    <button type="button" class="btn btn-success btn-lg flex-fill" id="acceptTransferPayment">
                        <i class="fas fa-check-circle me-1"></i>Pembayaran Diterima
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Hide number input spinners */
    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .cart-qty-input[type=number] {
        -moz-appearance: textfield;
    }

    /* Payment Method Grid */
    .payment-method-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .payment-option-card {
        cursor: pointer;
        margin: 0;
    }
    .payment-option-card input[type="radio"] {
        display: none;
    }
    .payment-card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        background: #fff;
        transition: all 0.2s ease;
        min-height: 70px;
    }
    .payment-card-content i {
        font-size: 1.4rem;
        margin-bottom: 6px;
        color: #6c757d;
        transition: all 0.2s ease;
    }
    .payment-card-content span {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
    }
    .payment-option-card:hover .payment-card-content {
        border-color: #667eea;
        background: #f8f9ff;
    }
    .payment-option-card input[type="radio"]:checked + .payment-card-content {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2);
    }
    .payment-option-card input[type="radio"]:checked + .payment-card-content i {
        color: #667eea;
    }
    .payment-option-card input[type="radio"]:checked + .payment-card-content span {
        color: #667eea;
    }

    /* Cash Payment Modal */
    .change-display {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
        border: 2px solid #28a745;
    }
    .quick-amount-buttons .btn {
        font-size: 13px;
        padding: 6px 12px;
    }
    .quick-amount-buttons .btn:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    #cashReceived {
        font-size: 1.5rem;
    }
    .btn-cancel-payment:hover {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: white !important;
    }

    /* QRIS Modal */
    .qris-wrapper {
        border: 3px solid #e9ecef;
    }
    .qris-code-container {
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
    }

    /* Bank Account Cards */
    .bank-account-card {
        transition: all 0.2s ease;
    }
    .bank-account-card:hover {
        border-color: #667eea !important;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.15);
    }
</style>
@endpush

@push('scripts')
<script>
    let cart = @json($cart);
    let selectedMemberId = null;

    $(document).ready(function() {
        renderCart();

        // Member code lookup
        $('#checkMemberBtn').on('click', function() {
            lookupMember();
        });

        // Enter key on member code input
        $('#memberCodeInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                lookupMember();
            }
        });

        // Clear member selection
        $('#clearMemberBtn').on('click', function() {
            clearMember();
        });

        // Search product
        $('#searchProduct').on('keyup', function() {
            const search = $(this).val().toLowerCase();
            $('.product-item').each(function() {
                const name = $(this).data('name');
                $(this).toggle(name.includes(search));
            });
        });

        // Add to cart with stock validation
        $('.add-to-cart').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = $(this).data('price');
            const stock = $(this).data('stock');

            const currentQty = cart[id] ? cart[id].quantity : 0;

            if (currentQty >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup',
                    text: 'Stok produk ini hanya ' + stock + ' unit',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            if (cart[id]) {
                cart[id].quantity++;
            } else {
                cart[id] = {
                    name: name,
                    price: price,
                    quantity: 1,
                    stock: stock,
                    product_code: $(this).closest('.product-card').find('code').text()
                };
            }

            updateCartServer('add', id, 1);
            renderCart();
        });

        // Member select change
        $('#memberSelect').on('change', function() {
            renderCart();
        });

        // Checkout
        $('#checkoutBtn').on('click', function() {
            if (Object.keys(cart).length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Tambahkan produk ke keranjang terlebih dahulu',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            const memberId = $('#memberSelect').val();
            const memberName = $('#memberBadge').val() || 'Non Member';
            const paymentMethod = $('input[name="paymentMethod"]:checked').val();
            const paymentLabel = {
                'cash': 'Cash (Tunai)',
                'qris': 'QRIS',
                'transfer': 'Transfer Bank'
            };

            // If cash payment, show cash modal
            if (paymentMethod === 'cash') {
                showCashPaymentModal();
                return;
            }

            // If QRIS payment, show QRIS modal
            if (paymentMethod === 'qris') {
                showQrisPaymentModal();
                return;
            }

            // If Transfer payment, show Transfer modal
            if (paymentMethod === 'transfer') {
                showTransferPaymentModal();
                return;
            }
        });

        // Cash payment modal handlers
        $('#cashReceived').on('input', function() {
            // Remove non-numeric characters
            let value = $(this).val().replace(/[^0-9]/g, '');
            // Format with thousand separator
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
            }
            $(this).val(value);
            calculateChange();
        });

        // Quick amount buttons
        $('.quick-amount').on('click', function() {
            const amount = $(this).data('amount');
            let finalAmount;

            if (amount === 'exact') {
                // Get current grand total
                const totalText = $('#grandTotal').text().replace(/[^0-9]/g, '');
                finalAmount = parseInt(totalText);
            } else {
                // Add to current amount
                const currentText = $('#cashReceived').val().replace(/[^0-9]/g, '') || '0';
                const currentAmount = parseInt(currentText);
                finalAmount = currentAmount + parseInt(amount);
            }

            $('#cashReceived').val(finalAmount.toLocaleString('id-ID'));
            calculateChange();
        });

        // Complete cash payment
        $('#completeCashPayment').on('click', function() {
            const cashReceivedText = $('#cashReceived').val().replace(/[^0-9]/g, '');
            const cashReceived = parseInt(cashReceivedText) || 0;
            const totalText = $('#grandTotal').text().replace(/[^0-9]/g, '');
            const total = parseInt(totalText);

            if (cashReceived < total) {
                Swal.fire({
                    icon: 'error',
                    title: 'Uang Tidak Cukup!',
                    text: 'Jumlah uang yang diterima kurang dari total belanja',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            // Close modal and process checkout
            $('#cashPaymentModal').modal('hide');
            processCheckout(cashReceived);
        });

        // Reset modal when closed
        $('#cashPaymentModal').on('hidden.bs.modal', function() {
            $('#cashReceived').val('');
            $('#changeDisplay').hide();
            $('#insufficientWarning').hide();
            $('#completeCashPayment').prop('disabled', true);
        });

        // QRIS Payment - Accept
        $('#acceptQrisPayment').on('click', function() {
            $('#qrisPaymentModal').modal('hide');
            processCheckout();
        });

        // Transfer Payment - Accept
        $('#acceptTransferPayment').on('click', function() {
            $('#transferPaymentModal').modal('hide');
            processCheckout();
        });

        // Copy rekening number
        $('.copy-rekening').on('click', function() {
            const rekening = $(this).data('rekening');
            navigator.clipboard.writeText(rekening).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disalin!',
                    text: 'Nomor rekening ' + rekening + ' telah disalin',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    });

    function renderCart() {
        const cartContainer = $('#cartItems');
        let html = '';
        let subtotal = 0;

        if (Object.keys(cart).length === 0) {
            html = '<div class="empty-cart"><i class="fas fa-shopping-cart fa-3x mb-3"></i><p>Keranjang masih kosong</p></div>';
            $('#checkoutBtn').prop('disabled', true);
        } else {
            for (const [id, item] of Object.entries(cart)) {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                const maxStock = item.stock || 999;
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name" title="${item.name}">${item.name}</div>
                            <div class="cart-item-price">Rp ${numberFormat(item.price)}</div>
                        </div>
                        <div class="cart-item-qty-wrapper">
                            <button class="btn btn-outline-secondary btn-qty-minus" data-id="${id}" data-stock="${maxStock}">-</button>
                            <input type="number" class="form-control cart-qty-input"
                                   value="${item.quantity}" min="1" max="${maxStock}"
                                   data-id="${id}" data-stock="${maxStock}">
                            <button class="btn btn-outline-secondary btn-qty-plus" data-id="${id}" data-stock="${maxStock}">+</button>
                        </div>
                        <div class="cart-item-total">Rp ${numberFormat(itemTotal)}</div>
                        <button class="cart-item-remove btn-remove" data-id="${id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            }
            $('#checkoutBtn').prop('disabled', false);
        }

        cartContainer.html(html);
        $('#subtotal').text('Rp ' + numberFormat(subtotal));

        // Calculate discount based on member selection
        const memberId = $('#memberSelect').val();
        const discount = memberId ? 5 : 0;
        const discountAmount = (subtotal * discount) / 100;
        const grandTotal = subtotal - discountAmount;

        if (discount > 0) {
            $('#discountRow').css('display', 'flex');
            $('#discountPercent').text(discount);
            $('#discountAmount').text(numberFormat(discountAmount));
        } else {
            $('#discountRow').css('display', 'none');
        }

        $('#grandTotal').text('Rp ' + numberFormat(grandTotal));

        // Bind events
        bindCartEvents();
    }

    function bindCartEvents() {
        $('.btn-qty-minus').off('click').on('click', function() {
            const id = $(this).data('id');
            if (cart[id] && cart[id].quantity > 1) {
                cart[id].quantity--;
                updateCartServer('update', id, cart[id].quantity);
                renderCart();
            }
        });

        $('.btn-qty-plus').off('click').on('click', function() {
            const id = $(this).data('id');
            const maxStock = $(this).data('stock');
            if (cart[id]) {
                if (cart[id].quantity < maxStock) {
                    cart[id].quantity++;
                    updateCartServer('update', id, cart[id].quantity);
                    renderCart();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: 'Stok produk ini hanya ' + maxStock + ' unit',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        });

        // Manual quantity input handler
        $('.cart-qty-input').off('change').on('change', function() {
            const id = $(this).data('id');
            const maxStock = $(this).data('stock');
            let newQty = parseInt($(this).val()) || 1;

            // Validate quantity
            if (newQty < 1) {
                newQty = 1;
            } else if (newQty > maxStock) {
                newQty = maxStock;
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup',
                    text: 'Stok produk ini hanya ' + maxStock + ' unit',
                    confirmButtonColor: '#667eea'
                });
            }

            if (cart[id]) {
                cart[id].quantity = newQty;
                updateCartServer('update', id, newQty);
                renderCart();
            }
        });

        // Prevent non-numeric input
        $('.cart-qty-input').off('keypress').on('keypress', function(e) {
            if (!/[0-9]/.test(String.fromCharCode(e.which))) {
                e.preventDefault();
            }
        });

        $('.btn-remove').off('click').on('click', function() {
            const id = $(this).data('id');
            delete cart[id];
            updateCartServer('remove', id, 0);
            renderCart();
        });
    }

    function updateCartServer(action, productId, quantity) {
        let url = '';
        if (action === 'add') url = '{{ route("cashier.cart.add") }}';
        else if (action === 'update') url = '{{ route("cashier.cart.update") }}';
        else if (action === 'remove') url = '{{ route("cashier.cart.remove") }}';

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            error: function(xhr) {
                console.error('Cart update failed:', xhr);
            }
        });
    }

    function processCheckout(cashReceived = null) {
        $.ajax({
            url: '{{ route("cashier.cart.checkout") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                payment_method: $('input[name="paymentMethod"]:checked').val(),
                membership_id: $('#memberSelect').val() || null
            },
            success: function(response) {
                if (response.success) {
                    let resultHtml = `
                        <p>Kode Order: <strong>${response.order_code}</strong></p>
                        <p>Total: <strong>Rp ${numberFormat(response.total)}</strong></p>
                    `;

                    // Add cash payment details if applicable
                    if (cashReceived !== null) {
                        const change = cashReceived - response.total;
                        resultHtml += `
                            <hr>
                            <p>Uang Diterima: <strong>Rp ${numberFormat(cashReceived)}</strong></p>
                            <p class="text-success fs-5">Kembalian: <strong>Rp ${numberFormat(change)}</strong></p>
                        `;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        html: resultHtml,
                        confirmButtonColor: '#667eea'
                    }).then(() => {
                        cart = {};
                        clearMember();
                        renderCart();
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#667eea'
                });
            }
        });
    }

    function showCashPaymentModal() {
        // Update modal with current values
        const subtotalText = $('#subtotal').text().replace(/[^0-9]/g, '');
        const subtotal = parseInt(subtotalText);
        const memberId = $('#memberSelect').val();
        const discount = memberId ? 5 : 0;
        const discountAmount = (subtotal * discount) / 100;
        const grandTotal = subtotal - discountAmount;

        $('#modalSubtotal').text('Rp ' + numberFormat(subtotal));

        if (discount > 0) {
            $('#modalDiscountRow').show();
            $('#modalDiscountAmount').text('Rp ' + numberFormat(discountAmount));
        } else {
            $('#modalDiscountRow').hide();
        }

        $('#modalTotal').text('Rp ' + numberFormat(grandTotal));

        // Reset input
        $('#cashReceived').val('');
        $('#changeDisplay').hide();
        $('#insufficientWarning').hide();
        $('#completeCashPayment').prop('disabled', true);

        // Show modal
        $('#cashPaymentModal').modal('show');
    }

    function showQrisPaymentModal() {
        // Update modal with current values
        const subtotalText = $('#subtotal').text().replace(/[^0-9]/g, '');
        const subtotal = parseInt(subtotalText);
        const memberId = $('#memberSelect').val();
        const discount = memberId ? 5 : 0;
        const discountAmount = (subtotal * discount) / 100;
        const grandTotal = subtotal - discountAmount;

        $('#qrisModalSubtotal').text('Rp ' + numberFormat(subtotal));

        if (discount > 0) {
            $('#qrisModalDiscountRow').show();
            $('#qrisModalDiscount').text(numberFormat(discountAmount));
        } else {
            $('#qrisModalDiscountRow').hide();
        }

        $('#qrisModalTotal').text('Rp ' + numberFormat(grandTotal));

        // Show modal
        $('#qrisPaymentModal').modal('show');
    }

    function showTransferPaymentModal() {
        // Update modal with current values
        const subtotalText = $('#subtotal').text().replace(/[^0-9]/g, '');
        const subtotal = parseInt(subtotalText);
        const memberId = $('#memberSelect').val();
        const discount = memberId ? 5 : 0;
        const discountAmount = (subtotal * discount) / 100;
        const grandTotal = subtotal - discountAmount;

        $('#transferModalSubtotal').text('Rp ' + numberFormat(subtotal));

        if (discount > 0) {
            $('#transferModalDiscountRow').show();
            $('#transferModalDiscount').text(numberFormat(discountAmount));
        } else {
            $('#transferModalDiscountRow').hide();
        }

        $('#transferModalTotal').text('Rp ' + numberFormat(grandTotal));

        // Show modal
        $('#transferPaymentModal').modal('show');
    }

    function calculateChange() {
        const cashReceivedText = $('#cashReceived').val().replace(/[^0-9]/g, '');
        const cashReceived = parseInt(cashReceivedText) || 0;
        const totalText = $('#grandTotal').text().replace(/[^0-9]/g, '');
        const total = parseInt(totalText);

        if (cashReceived > 0) {
            if (cashReceived >= total) {
                const change = cashReceived - total;
                $('#changeAmount').text('Rp ' + numberFormat(change));
                $('#changeDisplay').show();
                $('#insufficientWarning').hide();
                $('#completeCashPayment').prop('disabled', false);
            } else {
                const shortage = total - cashReceived;
                $('#shortageAmount').text('Rp ' + numberFormat(shortage));
                $('#changeDisplay').hide();
                $('#insufficientWarning').show();
                $('#completeCashPayment').prop('disabled', true);
            }
        } else {
            $('#changeDisplay').hide();
            $('#insufficientWarning').hide();
            $('#completeCashPayment').prop('disabled', true);
        }
    }

    function lookupMember() {
        const code = $('#memberCodeInput').val().trim().toUpperCase();
        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Kode Kosong',
                text: 'Masukkan kode member terlebih dahulu',
                confirmButtonColor: '#667eea'
            });
            return;
        }

        $.ajax({
            url: '{{ route("cashier.membership.lookup") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: code
            },
            success: function(response) {
                if (response.success) {
                    $('#memberSelect').val(response.member.id);
                    $('#memberBadge').val(response.member.name + ' (5% off)');
                    $('#memberInputGroup').hide();
                    $('#memberInfo').show();
                    renderCart();

                    Swal.fire({
                        icon: 'success',
                        title: 'Member Ditemukan!',
                        text: response.member.name,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Member Tidak Ditemukan',
                        text: response.message,
                        confirmButtonColor: '#667eea'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Kode member tidak ditemukan',
                    confirmButtonColor: '#667eea'
                });
            }
        });
    }

    function clearMember() {
        $('#memberSelect').val('');
        $('#memberCodeInput').val('');
        $('#memberInputGroup').show();
        $('#memberInfo').hide();
        $('#memberBadge').val('');
        renderCart();
    }

    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
@endpush
