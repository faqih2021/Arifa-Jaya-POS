<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Kadaluarsa | Arifa Jaya POS</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #f39c12;
            --secondary-color: #e67e22;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }

        .error-code {
            font-size: 150px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 4px 4px 0 rgba(0, 0, 0, 0.1);
            line-height: 1;
            margin-bottom: 10px;
        }

        .error-icon {
            font-size: 80px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 20px;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .error-title {
            font-size: 28px;
            font-weight: 600;
            color: white;
            margin-bottom: 15px;
        }

        .error-message {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 35px;
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 16px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: var(--secondary-color);
        }

        .btn-refresh {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 35px;
            background: transparent;
            color: white;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: 15px;
            cursor: pointer;
        }

        .btn-refresh:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
        }

        .brand {
            position: absolute;
            top: 30px;
            left: 30px;
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand:hover {
            color: white;
        }

        @media (max-width: 576px) {
            .error-code {
                font-size: 100px;
            }

            .error-icon {
                font-size: 60px;
            }

            .error-title {
                font-size: 22px;
            }

            .btn-home, .btn-refresh {
                display: block;
                margin: 10px auto;
            }

            .brand {
                position: relative;
                top: 0;
                left: 0;
                justify-content: center;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    {{-- Brand Logo --}}
    <a href="{{ url('/') }}" class="brand">
        <i class="fas fa-cash-register"></i>
        Arifa Jaya POS
    </a>

    {{-- Error Content --}}
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-clock"></i>
        </div>

        <div class="error-code">419</div>

        <h1 class="error-title">Sesi Telah Kadaluarsa</h1>

        <p class="error-message">
            Sesi Anda telah berakhir karena tidak ada aktivitas.
            Silakan refresh halaman atau login kembali untuk melanjutkan.
        </p>

        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn-home">
                <i class="fas fa-sign-in-alt"></i>
                Login Kembali
            </a>

            <button onclick="location.reload()" class="btn-refresh">
                <i class="fas fa-sync-alt"></i>
                Refresh Halaman
            </button>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
