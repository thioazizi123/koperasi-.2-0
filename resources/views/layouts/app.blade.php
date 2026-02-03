<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi IKAB - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">

    @yield('styles')

    <style>
        @media print {
            @page {
                size: landscape;
                /* Force landscape for better table fit */
                margin: 1cm;
            }

            /* 1. HIDE EVERYTHING by default */
            body * {
                visibility: hidden;
            }

            /* 2. SHOW only specific printable areas and their children */
            .print-buku-kas .buku-kas-section,
            .print-buku-kas .buku-kas-section *,
            .print-matrix .matrix-report-section,
            .print-matrix .matrix-report-section * {
                visibility: visible;
            }

            /* 3. POSITION the printable area to top-left */
            .print-buku-kas .buku-kas-section,
            .print-matrix .matrix-report-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                background: white;

                /* Remove overflow to prevent scrollbars */
                overflow: visible !important;
            }

            /* Fix Table Width & Font Size */
            table {
                width: 100% !important;
                font-size: 9pt !important;
                /* Smaller font */
                border-collapse: collapse !important;
            }

            th,
            td {
                padding: 4px !important;
                /* Compact padding */
                border: 1px solid #000 !important;
                word-wrap: break-word;
            }

            /* 4. Hide page furniture explictly */
            .sidebar,
            .top-bar,
            .welcome-section,
            .filter-section,
            .no-print,
            .print-button {
                display: none !important;
            }

            /* 5. Reset Body */
            body {
                background: white;
                margin: 0;
                padding: 0;
                /* Remove hidden overflow that might clip content */
                overflow: visible !important;
            }

            /* Hide scrollbars */
            ::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar no-print">
        <div class="sidebar-header">
            <h2>Koperasi IKAB</h2>
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
                    <a href="{{ route('members.index') }}"
                        class="menu-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="fas fa-users"></i></span>
                        <span class="menu-text">Keanggotaan</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('simpanan*') ? 'open' : '' }}">
                    <div class="menu-link {{ request()->is('simpanan*') ? 'active' : '' }}"
                        onclick="toggleSubmenu(this)">
                        <span class="menu-icon"><i class="fas fa-wallet"></i></span>
                        <span class="menu-text">Simpanan</span>
                        <span class="chevron-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <ul class="submenu-list">
                        <li>
                            <a href="{{ route('simpanan.pokok') }}"
                                class="submenu-link {{ request()->routeIs('simpanan.pokok') ? 'active' : '' }}">
                                Simpanan Pokok
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('simpanan.wajib') }}"
                                class="submenu-link {{ request()->routeIs('simpanan.wajib') ? 'active' : '' }}">
                                Simpanan Wajib
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('simpanan.operasional') }}"
                                class="submenu-link {{ request()->routeIs('simpanan.operasional') ? 'active' : '' }}">
                                Dana Operasional
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('simpanan.penarikan') }}"
                                class="submenu-link {{ request()->routeIs('simpanan.penarikan') ? 'active' : '' }}">
                                Penarikan
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="{{ route('financings.index') }}"
                        class="menu-link {{ request()->routeIs('financings.*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="fas fa-hand-holding-usd"></i></span>
                        <span class="menu-text">Pembiayaan</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('report*') ? 'open' : '' }}">
                    <div class="menu-link {{ request()->is('report*') ? 'active' : '' }}"
                        onclick="toggleSubmenu(this)">
                        <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="menu-text">Report</span>
                        <span class="chevron-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <ul class="submenu-list">
                        <li>
                            <a href="{{ route('reports.members') }}"
                                class="submenu-link {{ request()->routeIs('reports.members') ? 'active' : '' }}">
                                Daftar Anggota
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.cash_book') }}"
                                class="submenu-link {{ request()->routeIs('reports.cash_book') ? 'active' : '' }}">
                                Buku Kas
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar no-print">
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
    <script>
        function toggleSubmenu(element) {
            event.preventDefault();
            const menuItem = element.closest('.menu-item');
            menuItem.classList.toggle('open');
            console.log('Toggle clicked', menuItem.classList.contains('open'));
        }
    </script>
    @yield('scripts')
</body>

</html>