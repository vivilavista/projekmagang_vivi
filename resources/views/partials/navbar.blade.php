{{-- Topbar --}}
<div class="topbar">
    <div class="d-flex align-items-center">
        <button class="btn-toggle-sidebar toggle-sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
            <span class="meta d-none d-sm-block">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 gap-sm-3">
        <a href="{{ route('booking.index') }}" class="btn btn-sm btn-outline-secondary" target="_blank"
            title="Form Booking" style="font-size:0.78rem;">
            <i class="bi bi-calendar-plus"></i><span class="ms-1 d-none d-md-inline">Form Booking</span>
        </a>
        <div class="dropdown">
            <div class="d-flex align-items-center gap-2" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <div style="width:34px;height:34px;border-radius:9px;background:var(--c-lightest);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-person-fill" style="color:var(--c-dark);"></i>
                </div>
                <div class="d-none d-sm-block text-end">
                    <div class="fw-semibold" style="font-size:0.82rem;color:var(--c-dark);">{{ auth()->user()->nama }}</div>
                    <span class="role-badge role-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <div class="d-block d-sm-none">
                    <span class="role-badge role-{{ auth()->user()->role }}" style="font-size: 0.6rem; padding: 2px 6px;">{{ strtoupper(substr(auth()->user()->role, 0, 1)) }}</span>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; min-width: 200px;">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger py-2">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout Aman
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>