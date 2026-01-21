<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Koperasi - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
    
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Koperasi Syariah</h2>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="/" class="menu-link {{ request()->is('/') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="fas fa-home"></i></span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="fas fa-users"></i></span>
                        <span class="menu-text">Keanggotaan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="fas fa-wallet"></i></span>
                        <span class="menu-text">Simpanan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="fas fa-hand-holding-dollar"></i></span>
                        <span class="menu-text">Pembiayaan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="menu-text">Report</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div class="search-bar">
                <!-- Can add search here -->
            </div>
            <div class="user-profile">
                <span>Selamat Datang, <strong>Admin</strong></span>
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    @yield('scripts')
</body>
</html>
