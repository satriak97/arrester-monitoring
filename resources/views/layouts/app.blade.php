<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/Logo_PLN2.png') }}">

    <title>Monitoring Arrester</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef2f7;
            color: #111827;
        }

        .top-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 72px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 18px;
            min-width: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-right: 10px;
            text-decoration: none;
            color: #111827;
            flex-shrink: 0;
        }

        .brand img {
            height: 38px;
            width: auto;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .nav-menu a {
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .nav-menu a:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .nav-menu a.active {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .user-role {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 700;
        }

        .logout-btn {
            border: none;
            border-radius: 10px;
            background: #ef4444;
            color: white;
            padding: 10px 14px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .main-content {
            padding: 24px;
        }

        .page-title {
            margin: 0 0 22px 0;
            font-size: 30px;
            font-weight: 800;
            color: #111827;
        }

        .panel {
            background: #ffffff;
            padding: 22px;
            border-radius: 18px;
            margin-bottom: 22px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
            border: 1px solid #e5e7eb;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 14px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            font-weight: 700;
            color: #111827;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn:hover {
            opacity: 0.92;
        }

        .btn-danger {
            background: #dc2626;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 700;
            color: #111827;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: white;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper ul {
            display: flex !important;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination-wrapper li {
            display: inline-block;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            display: block;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            background: #f3f4f6;
            color: #111827;
            font-size: 14px;
        }

        .pagination-wrapper .active span {
            background: #2563eb;
            color: white;
        }

        .mobile-menu-toggle {
            display: none;
            border: none;
            background: #f3f4f6;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .top-navbar {
                height: auto;
                padding: 14px 16px;
                flex-wrap: wrap;
                gap: 14px;
            }

            .nav-left,
            .nav-right {
                width: 100%;
                justify-content: space-between;
            }

            .nav-left {
                flex-wrap: wrap;
            }

            .nav-menu {
                width: 100%;
                display: none;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding-top: 6px;
            }

            .nav-menu.show {
                display: flex;
            }

            .nav-menu a {
                background: #f8fafc;
                border: 1px solid #e5e7eb;
            }

            .mobile-menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .brand-subtitle {
                display: none;
            }

            .user-chip {
                flex: 1;
            }

            .main-content {
                padding: 16px;
            }
        }

        @media (max-width: 640px) {
            .brand-text {
                display: none;
            }

            .nav-right {
                flex-direction: column;
                align-items: stretch;
            }

            .logout-btn {
                width: 100%;
            }

            .main-content {
                padding: 12px;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<nav class="top-navbar">
    <div class="nav-left">
        <a href="{{ route('dashboard') }}" class="brand">
            <img src="{{ asset('images/Logo_PLN.png') }}" alt="Logo PLN">
            <div class="brand-text">
                <span class="brand-title">Monitoring Arrester</span>
                <span class="brand-subtitle">Leakage Current System</span>
            </div>
        </a>

        <button class="mobile-menu-toggle" type="button" onclick="toggleMobileMenu()">☰</button>

        <div class="nav-menu" id="navMenu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('measurement.history') }}" class="{{ request()->routeIs('measurement.history') ? 'active' : '' }}">
                Histori
            </a>

            @auth
                @if(auth()->user()->role === 'admin_cabang')
                    <a href="{{ route('cabang-users.index') }}" class="{{ request()->routeIs('cabang-users.*') ? 'active' : '' }}">
                        User Cabang
                    </a>
                @endif
            @endauth

            @auth
                @if(in_array(auth()->user()->role, ['admin_master', 'admin_cabang']))
                    <a href="{{ url('/arrester') }}" class="{{ request()->is('arrester*') ? 'active' : '' }}">
                        Data Arrester
                    </a>
                @endif

                @if(auth()->user()->role === 'admin_master')
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        Kelola User
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @auth
        <div class="nav-right">
            <div class="user-chip">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-meta">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->role }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    @endauth
</nav>

<div class="main-content">
    @if(session('success'))
        <div id="alertSuccess" style="
            background:#dcfce7;
            color:#166534;
            padding:12px 16px;
            border-radius:10px;
            margin-bottom:16px;
            border:1px solid #86efac;
            transition: all 0.5s ease;
        ">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('alertSuccess');
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    {{ $slot }}
</div>

<script>
    function toggleMobileMenu() {
        const navMenu = document.getElementById('navMenu');
        navMenu.classList.toggle('show');
    }
</script>

</body>
</html>