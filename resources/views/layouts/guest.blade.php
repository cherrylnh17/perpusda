<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - {{ config('app.name', 'Perpustakaan') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; }

        .login-page {
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }
        .login-page::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -15%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .login-page::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -15%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Left Branding Panel */
        .login-branding {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            z-index: 2;
        }
        .branding-content {
            text-align: center;
            max-width: 420px;
        }
        .branding-logo {
            width: 88px;
            height: 88px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
            border: 3px solid rgba(255,255,255,0.1);
        }
        .branding-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.025em;
            margin-bottom: 0.75rem;
        }
        .branding-title span {
            background: linear-gradient(135deg, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .branding-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }

        /* Right Form Panel */
        .login-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1.25rem;
            padding: 2.5rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
        }
        .login-card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-card-header img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .login-card-header h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.025em;
        }
        .login-card-header p {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.375rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
        }
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"] {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-group input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        .form-group input::placeholder {
            color: #94a3b8;
        }
        .error-text {
            color: #ef4444;
            font-size: 0.8125rem;
            margin-top: 0.25rem;
        }

        /* Checkbox */
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .form-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid #d1d5db;
            accent-color: #3b82f6;
            cursor: pointer;
        }
        .form-checkbox label {
            font-size: 0.8125rem;
            color: #64748b;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(59,130,246,0.3);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 16px rgba(59,130,246,0.4);
            transform: translateY(-1px);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer link */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
        }
        .login-footer a {
            font-size: 0.8125rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }
        .login-footer a:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        /* Back to home */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            color: rgba(255,255,255,0.6);
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            z-index: 10;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #fff;
        }
        .back-link svg {
            width: 16px;
            height: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-page {
                flex-direction: column;
            }
            .login-branding {
                padding: 2rem 1.5rem 1rem;
                min-height: auto;
            }
            .branding-logo {
                width: 64px;
                height: 64px;
                border-radius: 16px;
                margin-bottom: 1.25rem;
            }
            .branding-title {
                font-size: 1.375rem;
            }
            .branding-subtitle {
                font-size: 0.875rem;
            }
            .login-form-panel {
                padding: 1rem 1.5rem 2rem;
            }
            .login-card {
                padding: 2rem 1.5rem;
                box-shadow: none;
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(10px);
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <a href="{{ url('/') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>

        <div class="login-branding">
            <div class="branding-content">
                <img src="{{ asset('image/logo.png') }}" alt="Logo Perpustakaan" class="branding-logo">
                <h1 class="branding-title">Kelola <span>Perpustakaan</span><br>Lebih Mudah</h1>
                <p class="branding-subtitle">Sistem manajemen perpustakaan digital untuk mengelola data kepegawaian, kenaikan pangkat, dan administrasi secara terintegrasi.</p>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-card">
                <div class="login-card-header">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo">
                    <h2>Selamat Datang Kembali</h2>
                    <p>Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                {{ $slot }}

                <div class="login-footer">
                    <a href="{{ url('/') }}">&larr; Kembali ke halaman utama</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
