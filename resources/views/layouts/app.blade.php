<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            /* Updated to match login theme colors */
            --primary-color: #088395;
            --primary-light: #05bfdb;
            --primary-dark: #0a4d68;
            --secondary-color: #64748b;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --surface-light: #f8fafc;
            --surface-dark: #1e293b;
            --border-light: #e2e8f0;
            --border-dark: #334155;
        }

        [data-bs-theme="light"] {
            --bs-body-bg: #ffffff;
            --bs-body-color: #1e293b;
            --bs-border-color: var(--border-light);
            --bs-secondary-bg: var(--surface-light);
            --bs-tertiary-bg: #f1f5f9;
        }

        [data-bs-theme="dark"] {
            --bs-body-bg: #0f172a;
            --bs-body-color: #cbd5e1;
            --bs-border-color: var(--border-dark);
            --bs-secondary-bg: var(--surface-dark);
            --bs-tertiary-bg: #334155;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            /* Updated gradient to match login page */
            background: linear-gradient(135deg, #0a4d68 0%, #088395 50%, #05bfdb 100%);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        [data-bs-theme="dark"] .sidebar {
            background: linear-gradient(135deg, #0a4d68 0%, #088395 50%, #05bfdb 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        [data-bs-theme="dark"] .sidebar-header {
            border-bottom: 1px solid var(--border-dark);
        }

        .sidebar-header .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed .sidebar-header .logo-text {
            display: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
            list-style: none;
        }

        .nav-item {
            margin: 0.25rem 1rem;
            list-style: none;
        }

        .nav-item::marker {
            display: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        [data-bs-theme="dark"] .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            margin: 0.25rem 0.5rem;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bs-body-bg);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Header */
        .main-header {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--bs-border-color);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: between;
            position: sticky;
            top: 0;
            z-index: 999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-bs-theme="dark"] .main-header {
            background: rgba(15, 23, 42, 0.95);
            border-bottom-color: var(--border-dark);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--bs-body-color);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-toggle:hover {
            background: var(--bs-secondary-bg);
            transform: scale(1.05);
        }

        .theme-toggle {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--bs-body-color);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .theme-toggle:hover {
            background: var(--bs-secondary-bg);
            transform: rotate(180deg) scale(1.05);
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            background: var(--bs-body-bg);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--bs-body-color);
            letter-spacing: -0.025em;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }

        /* Cards */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bs-body-bg);
        }

        [data-bs-theme="dark"] .card {
            background: var(--surface-dark);
            border-color: var(--border-dark);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2), 0 1px 2px 0 rgba(0, 0, 0, 0.12);
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        [data-bs-theme="dark"] .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.18);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Submenu Styles */
        .nav-item.has-submenu>.nav-link {
            position: relative;
        }

        .nav-item.has-submenu>.nav-link::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item.has-submenu.open>.nav-link::after {
            transform: rotate(180deg);
        }

        .submenu {
            list-style: none;
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item.has-submenu.open .submenu {
            max-height: 500px;
        }

        .submenu .nav-item {
            margin: 0.125rem 1rem 0.125rem 2rem;
        }

        .submenu .nav-link {
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
            font-weight: 400;
        }

        .submenu .nav-link i {
            width: 16px;
            font-size: 0.85rem;
        }

        .sidebar.collapsed .nav-item.has-submenu>.nav-link::after {
            display: none;
        }

        .sidebar.collapsed .submenu {
            display: none;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Utility Classes */
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .glass-effect {
            background: rgba(0, 0, 0, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="logo">
                <i class="fas fa-search-dollar"></i>
                <span class="logo-text ms-2">AKSI</span>
            </a>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/pemeriksaan" class="nav-link">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Data Pemeriksaan</span>
                </a>
            </li>
            <li class="nav-item has-submenu">
                <a href="#" class="nav-link" onclick="toggleSubmenu(event, this)">
                    <i class="fas fa-database"></i>
                    <span>Master Data</span>
                </a>
                <ul class="submenu">
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('unit.index') }}" class="nav-link">
                            <i class="fas fa-building"></i>
                            <span>Unit Kerja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('bidang.index') }}" class="nav-link">
                            <i class="fas fa-layer-group"></i>
                            <span>Master Bidang</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('sebab.index') }}" class="nav-link">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Master Penyebab</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('coso.index') }}" class="nav-link">
                            <i class="fas fa-shield-alt"></i>
                            <span>Master COSO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('temu.index') }}" class="nav-link">
                            <i class="fas fa-search"></i>
                            <span>Master Temuan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('ab.index') }}" class="nav-link">
                            <i class="fas fa-file-contract"></i>
                            <span>Master AB</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistik</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-info">
                    <!-- <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5> -->
                </div>
            </div>

            <div class="header-right">
                <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg"></i>

                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href=#
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            @if (View::hasSection('breadcrumb'))
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            @endif

            <div class="fade-in">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });

        // Theme Toggle
        document.getElementById('themeToggle').addEventListener('click', function () {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            const currentTheme = html.getAttribute('data-bs-theme');

            if (currentTheme === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Load saved preferences
        document.addEventListener('DOMContentLoaded', function () {
            // Load theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');

            html.setAttribute('data-bs-theme', savedTheme);
            themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

            // Load sidebar state
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            }

            // Set active nav link
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('show');
            });
        }

        // Submenu toggle function
        function toggleSubmenu(event, element) {
            event.preventDefault();
            const parentLi = element.parentElement;
            const wasOpen = parentLi.classList.contains('open');

            // Close all other submenus
            document.querySelectorAll('.nav-item.has-submenu').forEach(item => {
                item.classList.remove('open');
            });

            // Toggle current submenu
            if (!wasOpen) {
                parentLi.classList.add('open');
            }
        }

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const sidebarToggle = document.getElementById('sidebarToggle');

                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>