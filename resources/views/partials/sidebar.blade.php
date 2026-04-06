{{-- Sidebar --}}
<div class="sidebar">
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div
                    style="width:32px;height:32px;border-radius:8px;background:var(--c-lightest);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-book-half" style="color:var(--c-dark);font-size:1rem;"></i>
                </div>
                <h5 class="mb-0">Buku Tamu</h5>
            </div>
            <small>Sistem Digital</small>
        </div>
        <button class="btn btn-link link-light p-0 d-lg-none toggle-sidebar" style="font-size: 1.5rem;">
            <i class="bi bi-x"></i>
        </button>
    </div>

    <nav class="nav flex-column mt-2 flex-grow-1" style="overflow-y:auto;">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelola User
            </a>
            <a href="{{ route('admin.tamu.index') }}" class="nav-link {{ request()->is('admin/tamu*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Data Tamu
            </a>
            <a href="{{ route('admin.kunjungan.index') }}"
                class="nav-link {{ request()->is('admin/kunjungan*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Data Kunjungan
            </a>
            <div class="nav-divider mt-2"></div>
            <span class="nav-section-label">Master Data</span>
            <a href="{{ route('admin.master-tujuan.index') }}"
                class="nav-link {{ request()->is('admin/master-tujuan*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Master Tujuan
            </a>
            <a href="{{ route('admin.jadwal.index') }}"
                class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Jadwal Booking
                @php $pending = \App\Models\JadwalKunjungan::where('status', 'Menunggu')->count(); @endphp
                @if($pending > 0)
                    <span class="badge ms-auto" style="background:#f59e0b;color:#fff;font-size:0.65rem;">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.scan.index') }}" class="nav-link {{ request()->is('admin/scan') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Scan QR Kunjungan
            </a>

        @elseif(auth()->user()->isOperator())
            <a href="{{ route('operator.dashboard') }}"
                class="nav-link {{ request()->is('operator/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('operator.kunjungan.index') }}"
                class="nav-link {{ request()->is('operator/kunjungan*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Validasi Kunjungan
            </a>
            <a href="{{ route('operator.scan.index') }}"
                class="nav-link {{ request()->is('operator/scan') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Scan QR Kunjungan
            </a>

        @else {{-- petugas --}}
            <a href="{{ route('petugas.dashboard') }}"
                class="nav-link {{ request()->is('petugas/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('petugas.kunjungan.index') }}"
                class="nav-link {{ request()->is('petugas/kunjungan*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i> Data Kunjungan
            </a>
            <a href="{{ route('petugas.scan.index') }}"
                class="nav-link {{ request()->is('petugas/scan') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Scan QR Kunjungan
            </a>
        @endif
    </nav>

    {{-- User info + Logout --}}
    <div class="mt-auto pb-1">
        <div class="sidebar-user d-flex align-items-center gap-2">
            <div
                style="width:34px;height:34px;border-radius:9px;background:rgba(194,232,255,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-person-fill" style="color:var(--c-lightest);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="u-name text-truncate">{{ auth()->user()->nama }}</div>
                <div class="u-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>