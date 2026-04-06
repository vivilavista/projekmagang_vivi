<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Buku Tamu Digital')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --c-darkest: #000F22;
            --c-dark: #052355;
            --c-mid1: #4B76A4;
            --c-mid2: #4A719B;
            --c-light: #7393BC;
            --c-lightest: #C2E8FF;
        }

        *,
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background: #f0f5fb;
            color: #1a1a2e;
            overflow-x: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(170deg, var(--c-darkest) 0%, var(--c-dark) 60%, #073a7a 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            box-shadow: 4px 0 24px rgba(0, 15, 34, 0.35);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile sidebar hidden by default */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(2px);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        .sidebar-brand {
            padding: 1.4rem 1.25rem 1.2rem;
            border-bottom: 1px solid rgba(194, 232, 255, 0.1);
        }

        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
        }

        .sidebar-brand small {
            color: var(--c-lightest);
            font-size: 0.75rem;
            opacity: 0.75;
        }

        .sidebar .nav-link {
            color: rgba(194, 232, 255, 0.72);
            padding: 0.58rem 1rem;
            border-radius: 10px;
            margin: 2px 0.7rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.18s ease;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            min-width: 1.2rem;
        }

        .sidebar .nav-link:hover {
            background: rgba(194, 232, 255, 0.1);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: rgba(194, 232, 255, 0.15);
            color: #fff;
            font-weight: 600;
            box-shadow: inset 3px 0 0 var(--c-lightest);
        }

        .nav-section-label {
            font-size: 0.62rem;
            font-weight: 600;
            color: rgba(194, 232, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0.6rem 1.75rem 0.25rem;
        }

        .nav-divider {
            border-top: 1px solid rgba(194, 232, 255, 0.1);
            margin: 0.4rem 0.7rem;
        }

        /* ── TOPBAR ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid rgba(115, 147, 188, 0.15);
            padding: 0.8rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 12px rgba(5, 35, 85, 0.06);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        @media (max-width: 575.98px) {
            .topbar {
                padding: 0.7rem 1rem;
            }
            .sidebar-brand {
                padding: 1.2rem 1rem;
            }
        }

        .topbar .page-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--c-dark);
            margin: 0;
        }

        .topbar .meta {
            font-size: 0.78rem;
            color: var(--c-mid1);
        }

        .topbar .role-badge {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .role-admin {
            background: #dbeafe;
            color: #1e40af;
        }

        .role-petugas {
            background: #dcfce7;
            color: #15803d;
        }

        .role-operator {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-aktif {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-selesai {
            background: #f3f4f6;
            color: #4b5563;
        }

        /* ── CONTENT ── */
        .content-area {
            padding: 1.5rem 1.75rem;
        }

        @media (max-width: 767.98px) {
            .content-area {
                padding: 1rem;
            }
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.4rem 1.5rem;
            border: none;
            box-shadow: 0 2px 16px rgba(5, 35, 85, 0.07);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(5, 35, 85, 0.12);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(5, 35, 85, 0.07);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(115, 147, 188, 0.15);
            font-weight: 600;
            color: var(--c-dark);
            border-radius: 14px 14px 0 0 !important;
        }

        /* ── TABLE ── */
        .table th {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--c-mid1);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            background: #f6faff;
        }

        .table-hover tbody tr:hover {
            background: #f0f5fb;
        }

        /* ── BUTTONS ── */
        .btn-primary {
            background: var(--c-dark);
            border-color: var(--c-dark);
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--c-mid1);
            border-color: var(--c-mid1);
        }

        .btn-outline-primary {
            color: var(--c-dark);
            border-color: var(--c-dark);
        }

        .btn-outline-primary:hover {
            background: var(--c-dark);
            border-color: var(--c-dark);
        }

        /* ── USER CARD in sidebar ── */
        .sidebar-user {
            padding: 0.9rem 1.1rem;
            margin: 0 0.7rem 0.5rem;
            border-radius: 12px;
            background: rgba(194, 232, 255, 0.07);
            border: 1px solid rgba(194, 232, 255, 0.1);
        }

        .sidebar-user .u-name {
            font-size: 0.82rem;
            color: #fff;
            font-weight: 600;
        }

        .sidebar-user .u-role {
            font-size: 0.65rem;
            color: var(--c-lightest);
            opacity: 0.8;
        }

        .sidebar-logout {
            margin: 0 0.7rem 1rem;
            background: rgba(239, 68, 68, 0.15);
            border: none;
            color: #fca5a5;
            border-radius: 10px;
            font-size: 0.82rem;
            padding: 0.5rem;
            width: calc(100% - 1.4rem);
            transition: background 0.18s;
        }

        .sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.28);
            color: #fff;
        }

        /* ── FORMS ── */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--c-light);
            box-shadow: 0 0 0 3px rgba(115, 147, 188, 0.2);
        }

        .invalid-feedback {
            display: block;
        }

        /* ── PAGINATION ── */
        .page-link {
            color: var(--c-dark);
        }

        .page-item.active .page-link {
            background-color: var(--c-dark);
            border-color: var(--c-dark);
        }

        /* Sidebar toggle utilities */
        .btn-toggle-sidebar {
            display: none;
            background: transparent;
            border: none;
            color: var(--c-dark);
            font-size: 1.4rem;
            padding: 0;
            margin-right: 1rem;
        }

        @media (max-width: 991.98px) {
            .btn-toggle-sidebar {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @include('partials.sidebar')
    
    <div class="main-content">
        @include('partials.navbar')
        <div class="content-area">
            @include('partials.alerts')
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtns = document.querySelectorAll('.toggle-sidebar');

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                });
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            });

            // Close sidebar when clicking internal links on mobile
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>


</html>