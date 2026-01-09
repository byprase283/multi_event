<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Multi Event</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --sidebar-width: 260px;
        }

        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #0d1b2a 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #0ea5e9;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-navbar {
            background: #fff;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-wrapper {
            padding: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        }

        .badge {
            padding: 0.5em 0.75em;
            border-radius: 6px;
        }

        .table th {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-from) 0%, var(--bg-to) 100%);
            color: white;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .stat-card.blue {
            --bg-from: #0ea5e9;
            --bg-to: #0284c7;
        }

        .stat-card.green {
            --bg-from: #10b981;
            --bg-to: #059669;
        }

        .stat-card.purple {
            --bg-from: #8b5cf6;
            --bg-to: #7c3aed;
        }

        .stat-card.orange {
            --bg-from: #f59e0b;
            --bg-to: #d97706;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"
                class="text-white text-decoration-none d-flex align-items-center gap-2">
                <span class="fs-4">🎪</span>
                <span class="fw-bold">Multi Event</span>
            </a>
        </div>

        <ul class="sidebar-nav nav flex-column mt-3">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.events.index') }}"
                    class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.vouchers.index') }}"
                    class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Vouchers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.participants.index') }}"
                    class="nav-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.scanner.index') }}"
                    class="nav-link {{ request()->routeIs('admin.scanner.*') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Scan Tiket</span>
                </a>
            </li>
        </ul>

        <div class="position-absolute bottom-0 w-100 p-3 border-top border-secondary">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-left me-2"></i>Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">{{ Auth::user()->name ?? 'Admin' }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=0ea5e9&color=fff"
                    alt="Avatar" class="rounded-circle" width="36">
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')
</body>

</html>