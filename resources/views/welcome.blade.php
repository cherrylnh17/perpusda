<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Perpustakaan') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #1e293b; background: #f8fafc; line-height: 1.6; }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .navbar-brand img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
        }
        .navbar-brand span {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.025em;
        }
        .navbar-links {
            display: flex;
            gap: 0.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .btn-ghost {
            color: rgba(255,255,255,0.8);
            background: transparent;
        }
        .btn-ghost:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .btn-primary {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 1px 3px rgba(59,130,246,0.3);
        }
        .btn-primary:hover {
            background: #2563eb;
            box-shadow: 0 4px 12px rgba(59,130,246,0.4);
            transform: translateY(-1px);
        }
        .btn-white {
            background: #fff;
            color: #0f172a;
            font-weight: 600;
        }
        .btn-white:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Hero Content */
        .hero-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        .hero-logo {
            width: 100px;
            height: 100px;
            border-radius: 24px;
            object-fit: cover;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            border: 3px solid rgba(255,255,255,0.1);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 1rem;
            background: rgba(59,130,246,0.15);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 9999px;
            color: #93c5fd;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .hero-badge svg {
            width: 16px;
            height: 16px;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.035em;
            max-width: 700px;
            margin-bottom: 1rem;
        }
        .hero h1 span {
            background: linear-gradient(135deg, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 1.125rem;
            color: rgba(255,255,255,0.6);
            max-width: 520px;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            background: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }
        .section-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 0.75rem;
        }
        .section-header p {
            font-size: 1.0625rem;
            color: #64748b;
            max-width: 500px;
            margin: 0 auto;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            padding: 2rem;
            background: #f8fafc;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
        }
        .feature-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.25rem;
        }
        .feature-icon.blue { background: #eff6ff; color: #3b82f6; }
        .feature-icon.green { background: #ecfdf5; color: #10b981; }
        .feature-icon.amber { background: #fffbeb; color: #f59e0b; }
        .feature-icon.purple { background: #faf5ff; color: #8b5cf6; }
        .feature-card h3 {
            font-size: 1.0625rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.65;
        }

        /* Footer */
        .footer {
            padding: 2rem;
            text-align: center;
            background: #0f172a;
            color: rgba(255,255,255,0.4);
            font-size: 0.875rem;
        }
        .footer span {
            color: rgba(255,255,255,0.6);
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .navbar { padding: 1rem 1.25rem; }
            .hero-content { padding: 1.5rem; }
            .hero-logo { width: 72px; height: 72px; border-radius: 18px; }
            .features { padding: 3rem 1.25rem; }
            .hero-actions { flex-direction: column; width: 100%; max-width: 300px; }
            .hero-actions .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <section class="hero">
        <nav class="navbar">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('image/logo.png') }}" alt="Logo Perpustakaan">
                <span>Perpustakaan</span>
            </a>
            <div class="navbar-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                    @endauth
                @endif
            </div>
        </nav>

        <div class="hero-content">
            <img src="{{ asset('image/logo.png') }}" alt="Logo Perpustakaan" class="hero-logo">
            <div class="hero-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                Sistem Informasi Manajemen
            </div>
            <h1>Kelola <span>Perpustakaan</span><br>Lebih Mudah & Efisien</h1>
            <p>Sistem manajemen perpustakaan digital yang membantu mengelola data karyawan, kenaikan pangkat, dan administrasi kepegawaian secara terintegrasi.</p>
            <div class="hero-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-white">
                            Masuk ke Dashboard
                            <svg style="margin-left:6px;width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-white">
                            Masuk ke Sistem
                            <svg style="margin-left:6px;width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2>Fitur Utama</h2>
                <p>Kemudahan dalam mengelola seluruh aspek kepegawaian perpustakaan</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3>Manajemen Karyawan</h3>
                    <p>Kelola data karyawan, jabatan, golongan, dan riwayat pendidikan dalam satu sistem terpusat.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <h3>Kenaikan Pangkat</h3>
                    <p>Proses kenaikan golongan dan kenaikan gaji berkala dengan alur persetujuan yang jelas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon amber">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <h3>Rekap & Laporan</h3>
                    <p>Rekapitulasi data kepegawaian dan export laporan dalam format yang dibutuhkan.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <h3>Akses Aman</h3>
                    <p>Sistem autentikasi yang aman dengan pembagian hak akses untuk admin dan karyawan.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} <span>{{ config('app.name', 'Perpustakaan') }}</span>. Seluruh hak dilindungi.</p>
    </footer>
</body>
</html>
