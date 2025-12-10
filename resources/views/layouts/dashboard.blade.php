<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Arifa Jaya POS')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    {{-- Custom Dashboard CSS --}}
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="wrapper">
        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Arifa Jaya" class="sidebar-logo">
                <span class="sidebar-title">Arifa Jaya POS</span>
            </div>

            <div class="sidebar-user">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="user-role badge bg-primary">{{ ucfirst(Auth::user()->roles) }}</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    @if(Auth::user()->roles === 'superadmin')
                    {{-- Superadmin Menu --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard Income</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.stock*') ? 'active' : '' }}" href="{{ route('superadmin.stock') }}">
                            <i class="fas fa-boxes"></i>
                            <span>Dashboard Stock</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.employees*') ? 'active' : '' }}" href="{{ route('superadmin.employees.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Employee</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.suppliers*') ? 'active' : '' }}" href="{{ route('superadmin.suppliers.index') }}">
                            <i class="fas fa-truck"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>

                    @elseif(Auth::user()->roles === 'cashier')
                    {{-- Cashier Menu --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}" href="{{ route('cashier.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @elseif(Auth::user()->roles === 'storage')
                    {{-- Storage Menu --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('storage.dashboard') ? 'active' : '' }}" href="{{ route('storage.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-divider"></li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <a href="{{ route('logout.get') }}" class="nav-link" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Top Navbar --}}
            <nav class="top-navbar">
                <button class="btn btn-link sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="navbar-right">
                    <span class="current-date">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ now()->format('d F Y') }}
                    </span>
                </div>
            </nav>

            {{-- Page Content --}}
            <div class="page-content">
                {{-- Breadcrumb --}}
                <div class="page-header">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if(Auth::user()->roles === 'superadmin')
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                            @elseif(Auth::user()->roles === 'cashier')
                            <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Home</a></li>
                            @elseif(Auth::user()->roles === 'storage')
                            <li class="breadcrumb-item"><a href="{{ route('storage.dashboard') }}">Home</a></li>
                            @endif
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>

                {{-- Main Content --}}
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.wrapper').classList.toggle('sidebar-collapsed');
        });

        // DataTables default config
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            }
        });

        // Global AJAX Setup - Auto handle session expired & errors
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401 || xhr.status === 419) {
                    // Session expired - redirect to login
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi Berakhir',
                        text: 'Sesi Anda telah berakhir. Halaman akan di-refresh.',
                        timer: 2000,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '{{ route("login") }}';
                    });
                } else if (xhr.status === 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: 'Anda tidak memiliki izin untuk melakukan aksi ini.',
                        confirmButtonColor: '#667eea'
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (xhr.status === 404) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Ditemukan',
                        text: 'Data yang Anda cari tidak ditemukan.',
                        confirmButtonColor: '#667eea'
                    }).then(() => {
                        window.location.reload();
                    });
                } else if (xhr.status >= 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                        confirmButtonColor: '#667eea'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            }
        });

        // Logout with SweetAlert confirmation
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Ya, Logout',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("logout.get") }}';
                }
            });
        });

        // Show success/error alerts from session with auto refresh
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false
        }).then(() => {
            // Auto refresh setelah success untuk update data terbaru
            @if(session('refresh'))
            window.location.reload();
            @endif
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#667eea',
            allowOutsideClick: false
        }).then(() => {
            // Auto refresh setelah error untuk reset state
            window.location.reload();
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#667eea',
            allowOutsideClick: false
        }).then(() => {
            // Auto refresh setelah warning
            window.location.reload();
        });
        @endif

        // Auto refresh dashboard setiap 5 menit (hanya untuk halaman dashboard)
        @if(request()->routeIs('*.dashboard'))
        let autoRefreshInterval = setInterval(function() {
            // Silent refresh - hanya jika tidak ada modal/popup terbuka
            if (!document.querySelector('.swal2-container')) {
                window.location.reload();
            }
        }, 300000); // 5 menit = 300000ms

        // Stop auto refresh jika user sedang berinteraksi
        $(document).on('click keypress', function() {
            clearInterval(autoRefreshInterval);
            // Restart setelah 5 menit tidak ada aktivitas
            autoRefreshInterval = setInterval(function() {
                if (!document.querySelector('.swal2-container')) {
                    window.location.reload();
                }
            }, 300000);
        });
        @endif

        // Handle browser back/forward button - refresh halaman
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Halaman di-load dari cache (back/forward), refresh untuk data terbaru
                window.location.reload();
            }
        });

        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    @stack('scripts')
</body>
</html>
