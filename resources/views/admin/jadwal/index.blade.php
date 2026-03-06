@extends('layouts.app')
@section('title', 'Jadwal Kunjungan')
@section('page-title', 'Jadwal Kunjungan')

@section('styles')
    <style>
        /* ── LAYOUT ── */
        .jadwal-wrap {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .jadwal-wrap {
                grid-template-columns: 1fr;
            }
        }

        /* ── KALENDER ── */
        .kalender-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(5, 35, 85, 0.07);
            overflow: hidden;
        }

        .kalender-header {
            background: linear-gradient(135deg, var(--c-darkest) 0%, var(--c-dark) 100%);
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kalender-header h5 {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .kalender-nav-btn {
            background: rgba(194, 232, 255, 0.15);
            border: 1px solid rgba(194, 232, 255, 0.25);
            color: #fff;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
            font-size: 0.9rem;
        }

        .kalender-nav-btn:hover {
            background: rgba(194, 232, 255, 0.28);
        }

        .kalender-grid {
            padding: 0.75rem 1rem 1rem;
        }

        .kalender-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            margin-bottom: 4px;
        }

        .kalender-weekdays span {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--c-mid1);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 4px 0;
        }

        .kalender-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .kal-day {
            aspect-ratio: 1;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 5px 3px 3px;
            cursor: default;
            position: relative;
            transition: background 0.14s, transform 0.14s;
            min-height: 52px;
        }

        .kal-day.has-booking {
            cursor: pointer;
            background: #f0f8ff;
            border: 1px solid #bfdbfe;
        }

        .kal-day.has-booking:hover {
            background: #dbeafe;
            transform: scale(1.05);
            z-index: 2;
            box-shadow: 0 3px 12px rgba(5, 35, 85, 0.14);
        }

        .kal-day.today .day-num {
            background: var(--c-dark);
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kal-day.selected {
            background: #dbeafe;
            border: 2px solid var(--c-mid1);
        }

        .kal-day.other-month .day-num {
            color: #d1d5db;
        }

        .day-num {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            line-height: 1;
            margin-bottom: 2px;
        }

        .kal-dots {
            display: flex;
            gap: 2px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 2px;
        }

        .kal-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-menunggu {
            background: #f59e0b;
        }

        .dot-disetujui {
            background: #22c55e;
        }

        .dot-ditolak {
            background: #ef4444;
        }

        .booking-count-badge {
            position: absolute;
            top: 3px;
            right: 4px;
            font-size: 0.55rem;
            font-weight: 700;
            background: var(--c-dark);
            color: #fff;
            border-radius: 6px;
            padding: 1px 4px;
            line-height: 1.4;
        }

        /* ── LEGEND ── */
        .kalender-legend {
            display: flex;
            gap: 1rem;
            padding: 0.55rem 1rem 0.75rem;
            border-top: 1px solid #f0f5fb;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.7rem;
            color: #6b7280;
        }

        /* ── PANEL DETAIL ── */
        .detail-panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(5, 35, 85, 0.07);
            position: sticky;
            top: 80px;
        }

        .detail-header {
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid #f0f5fb;
        }

        .detail-header h6 {
            font-weight: 700;
            color: var(--c-dark);
            margin: 0;
            font-size: 0.9rem;
        }

        .detail-date-label {
            font-size: 0.72rem;
            color: var(--c-mid1);
            font-weight: 600;
            margin-top: 2px;
        }

        .detail-body {
            padding: 0.75rem 1rem 1rem;
            max-height: 560px;
            overflow-y: auto;
        }

        .booking-card-item {
            border: 1px solid #e9effe;
            border-radius: 12px;
            padding: 0.7rem 0.85rem;
            margin-bottom: 0.6rem;
            background: #fafcff;
            transition: box-shadow 0.15s;
            position: relative;
        }

        .booking-card-item:hover {
            box-shadow: 0 3px 14px rgba(5, 35, 85, 0.10);
        }

        .booking-card-item.status-menunggu {
            border-left: 3px solid #f59e0b;
        }

        .booking-card-item.status-disetujui {
            border-left: 3px solid #22c55e;
        }

        .booking-card-item.status-ditolak {
            border-left: 3px solid #ef4444;
        }

        .bc-name {
            font-weight: 700;
            color: #052355;
            font-size: 0.88rem;
        }

        .bc-meta {
            font-size: 0.72rem;
            color: #6b7280;
            margin-top: 2px;
        }

        .bc-jam {
            position: absolute;
            top: 8px;
            right: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            background: #f0f5fb;
            color: var(--c-mid1);
            border-radius: 6px;
            padding: 2px 7px;
        }

        .bc-badge {
            display: inline-block;
            font-size: 0.62rem;
            font-weight: 700;
            border-radius: 10px;
            padding: 2px 8px;
            margin-top: 5px;
        }

        .bc-badge.menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .bc-badge.disetujui {
            background: #dcfce7;
            color: #166534;
        }

        .bc-badge.ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .bc-actions {
            margin-top: 7px;
            display: flex;
            gap: 5px;
        }

        .bc-btn {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.14s;
        }

        .bc-btn-approve {
            background: #dcfce7;
            color: #166534;
        }

        .bc-btn-approve:hover {
            background: #22c55e;
            color: #fff;
        }

        .empty-detail {
            text-align: center;
            padding: 2rem 1rem;
            color: #9ca3af;
        }

        .empty-detail i {
            font-size: 2rem;
            color: #e5e7eb;
            display: block;
            margin-bottom: 8px;
        }

        /* ── TABEL LIST ── */
        .list-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(5, 35, 85, 0.07);
            margin-top: 1.25rem;
        }

        .status-badge-pill {
            font-size: 0.68rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            display: inline-block;
        }

        .pill-menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .pill-disetujui {
            background: #dcfce7;
            color: #166534;
        }

        .pill-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        /* loading spinner untuk kalender */
        .kal-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 0;
            color: var(--c-mid1);
            font-size: 0.85rem;
            gap: 8px;
        }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0" style="color:var(--c-dark);">
                <i class="bi bi-calendar3 me-2 text-primary"></i>Jadwal Kunjungan
            </h5>
            <div class="text-muted" style="font-size:0.78rem;">
                Klik tanggal di kalender untuk melihat daftar booking
            </div>
        </div>
    </div>

    <div class="jadwal-wrap">

        {{-- ────── KALENDER ────── --}}
        <div>
            <div class="kalender-card">
                {{-- Header navigasi bulan --}}
                <div class="kalender-header">
                    <button class="kalender-nav-btn" id="btnPrev" title="Bulan sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h5 id="kalHeaderTitle">—</h5>
                    <button class="kalender-nav-btn" id="btnNext" title="Bulan berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                {{-- Grid hari --}}
                <div class="kalender-grid">
                    <div class="kalender-weekdays">
                        <span>Min</span><span>Sen</span><span>Sel</span>
                        <span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                    </div>
                    <div class="kalender-days" id="kalGrid">
                        <div class="kal-loading colspan-7">
                            <span class="spinner-border spinner-border-sm"></span> Memuat...
                        </div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="kalender-legend">
                    <div class="legend-item">
                        <span class="kal-dot dot-menunggu"></span> Menunggu
                    </div>
                    <div class="legend-item">
                        <span class="kal-dot dot-disetujui"></span> Disetujui
                    </div>
                    <div class="legend-item">
                        <span class="kal-dot dot-ditolak"></span> Ditolak
                    </div>
                    <div class="legend-item ms-auto">
                        <span
                            style="width:20px;height:14px;background:#f0f8ff;border:1px solid #bfdbfe;border-radius:3px;display:inline-block;"></span>
                        Ada booking
                    </div>
                </div>
            </div>

            {{-- ── Tabel daftar jadwal (bawah kalender) ── --}}
            <div class="list-card">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span>
                        <i class="bi bi-list-ul text-primary me-2"></i>Semua Jadwal
                    </span>
                    <form method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" style="max-width:140px;"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @if(request('status'))
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x"></i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:0.85rem;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tamu</th>
                                    <th>Tujuan</th>
                                    <th>Tanggal & Jam</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalList as $i => $j)
                                    <tr>
                                        <td class="text-muted">{{ $jadwalList->firstItem() + $i }}</td>
                                        <td>
                                            <div class="fw-semibold" style="color:#052355;">{{ $j->nama_tamu }}</div>
                                            <div style="font-size:0.7rem;color:#9ca3af;">{{ $j->no_hp }}</div>
                                            @if($j->instansi)
                                                <div style="font-size:0.7rem;color:#6b7280;">
                                                    <i class="bi bi-building" style="font-size:0.6rem;"></i>
                                                    {{ $j->instansi }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $j->tujuan?->nama ?? '—' }}</td>
                                        <td>
                                            <div style="font-weight:600;">
                                                {{ $j->tanggal_kunjungan->format('d/m/Y') }}
                                            </div>
                                            <div style="font-size:0.72rem;color:#9ca3af;">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($j->jam_rencana)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge-pill pill-{{ strtolower($j->status) }}">
                                                {{ $j->status }}
                                            </span>
                                            @if($j->catatan && $j->status === 'Ditolak')
                                                <div style="font-size:0.68rem;color:#ef4444;margin-top:2px;">
                                                    {{ Str::limit($j->catatan, 30) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                @if($j->status === 'Menunggu')
                                                    <form action="{{ route('admin.jadwal.setujui', $j->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success" title="Setujui"
                                                            style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;">
                                                            <i class="bi bi-check-lg" style="font-size:0.8rem;"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-sm btn-danger" title="Tolak"
                                                        style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;"
                                                        onclick="toggleTolak({{ $j->id }})">
                                                        <i class="bi bi-x-lg" style="font-size:0.8rem;"></i>
                                                    </button>
                                                    <div id="tolak-{{ $j->id }}" style="display:none;width:100%;margin-top:4px;">
                                                        <form action="{{ route('admin.jadwal.tolak', $j->id) }}" method="POST">
                                                            @csrf
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" name="catatan" class="form-control"
                                                                    placeholder="Alasan penolakan..." required>
                                                                <button class="btn btn-danger"
                                                                    style="font-size:0.75rem;">Tolak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Hapus jadwal {{ $j->nama_tamu }}?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"
                                                        style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;">
                                                        <i class="bi bi-trash" style="font-size:0.75rem;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-calendar-x"
                                                style="font-size:1.8rem;color:#e5e7eb;display:block;"></i>
                                            Belum ada jadwal kunjungan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($jadwalList->hasPages())
                        <div class="p-3 d-flex justify-content-end">
                            {{ $jadwalList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ────── PANEL DETAIL TANGGAL ────── --}}
        <div class="detail-panel">
            <div class="detail-header">
                <h6><i class="bi bi-calendar-day me-2 text-primary"></i>Detail Booking</h6>
                <div class="detail-date-label" id="detailDateLabel">Pilih tanggal di kalender</div>
            </div>
            <div class="detail-body" id="detailBody">
                <div class="empty-detail">
                    <i class="bi bi-hand-index-thumb"></i>
                    Klik tanggal yang memiliki booking untuk melihat detailnya
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const DATA_URL = "{{ route('admin.jadwal.kalender-data') }}";
        const BULAN_NAMA = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const HARI_NAMA = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        let currentYear = {{ now()->year }};
        let currentMonth = {{ now()->month }};
        let kalData = {};   // { 'YYYY-MM-DD': [...] }
        let selectedDate = null;

        // ── Ambil data dari server ──────────────────────────────
        async function fetchKalender(year, month) {
            document.getElementById('kalGrid').innerHTML =
                `<div class="kal-loading" style="grid-column:span 7">
                <span class="spinner-border spinner-border-sm"></span>&nbsp;Memuat...
             </div>`;

            const resp = await fetch(`${DATA_URL}?tahun=${year}&bulan=${month}`);
            kalData = await resp.json();
            renderKalender(year, month);
        }

        // ── Render grid kalender ────────────────────────────────
        function renderKalender(year, month) {
            document.getElementById('kalHeaderTitle').textContent =
                `${BULAN_NAMA[month - 1]} ${year}`;

            const grid = document.getElementById('kalGrid');
            grid.innerHTML = '';

            const firstDay = new Date(year, month - 1, 1).getDay(); // 0=Min
            const daysCount = new Date(year, month, 0).getDate();
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            // Isi hari kosong dari bulan sebelumnya
            const prevDays = new Date(year, month - 1, 0).getDate();
            for (let i = firstDay - 1; i >= 0; i--) {
                const cell = makeDay(prevDays - i, null, true, false);
                grid.appendChild(cell);
            }

            // Isi hari bulan ini
            for (let d = 1; d <= daysCount; d++) {
                const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const bookings = kalData[dateStr] || [];
                const isToday = dateStr === todayStr;
                const isSelected = dateStr === selectedDate;

                const cell = makeDay(d, dateStr, false, isToday, bookings, isSelected);
                grid.appendChild(cell);
            }

            // Isi sisa baris (hari bulan depan)
            const total = firstDay + daysCount;
            const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
            for (let d = 1; d <= remaining; d++) {
                const cell = makeDay(d, null, true, false);
                grid.appendChild(cell);
            }
        }

        function makeDay(num, dateStr, otherMonth, isToday, bookings = [], isSelected = false) {
            const div = document.createElement('div');
            div.className = 'kal-day';
            if (otherMonth) div.classList.add('other-month');
            if (isToday) div.classList.add('today');
            if (bookings.length > 0) div.classList.add('has-booking');
            if (isSelected) div.classList.add('selected');

            const numEl = document.createElement('div');
            numEl.className = 'day-num';
            numEl.textContent = num;
            div.appendChild(numEl);

            if (bookings.length > 0) {
                // Badge jumlah
                const badge = document.createElement('div');
                badge.className = 'booking-count-badge';
                badge.textContent = bookings.length;
                div.appendChild(badge);

                // Dots warna
                const dots = document.createElement('div');
                dots.className = 'kal-dots';
                const shown = bookings.slice(0, 4);
                shown.forEach(b => {
                    const dot = document.createElement('span');
                    dot.className = `kal-dot dot-${b.status.toLowerCase()}`;
                    dots.appendChild(dot);
                });
                div.appendChild(dots);

                div.addEventListener('click', () => showDetail(dateStr, bookings));
            }

            return div;
        }

        // ── Tampilkan detail di panel kanan ────────────────────
        function showDetail(dateStr, bookings) {
            selectedDate = dateStr;
            renderKalender(currentYear, currentMonth); // re-render untuk highlight

            const [yr, mo, dy] = dateStr.split('-');
            const dt = new Date(Number(yr), Number(mo) - 1, Number(dy));
            document.getElementById('detailDateLabel').textContent =
                `${HARI_NAMA[dt.getDay()]}, ${Number(dy)} ${BULAN_NAMA[Number(mo) - 1]} ${yr}`;

            const body = document.getElementById('detailBody');
            if (!bookings || bookings.length === 0) {
                body.innerHTML = `<div class="empty-detail">
                <i class="bi bi-calendar-x"></i>Tidak ada booking pada tanggal ini
            </div>`;
                return;
            }

            body.innerHTML = bookings.map(b => `
            <div class="booking-card-item status-${b.status.toLowerCase()}">
                <div class="bc-jam"><i class="bi bi-clock me-1"></i>${b.jam}</div>
                <div class="bc-name">${b.nama}</div>
                <div class="bc-meta">
                    <i class="bi bi-telephone" style="font-size:0.6rem;"></i> ${b.no_hp}
                    ${b.instansi ? `<br><i class="bi bi-building" style="font-size:0.6rem;"></i> ${b.instansi}` : ''}
                </div>
                <div class="bc-meta mt-1">
                    <i class="bi bi-flag" style="font-size:0.6rem;"></i>
                    <strong>${b.tujuan}</strong>
                    ${b.keperluan ? `<br><i class="bi bi-chat-left-text" style="font-size:0.6rem;"></i> ${b.keperluan}` : ''}
                </div>
                <span class="bc-badge ${b.status.toLowerCase()}">${b.status}</span>
                ${b.status === 'Menunggu' && b.setujui_url
                    ? `<div class="bc-actions">
                        <form method="POST" action="${b.setujui_url}" style="display:inline;" id="sf-${b.id}">
                            <input type="hidden" name="_token" value="${CSRF}">
                            <button type="submit" class="bc-btn bc-btn-approve"
                                onclick="return confirm('Setujui booking ${b.nama}?')">
                                <i class="bi bi-check-lg me-1"></i>Setujui
                            </button>
                        </form>
                       </div>`
                    : ''}
            </div>
        `).join('');
        }

        // ── Navigasi bulan ──────────────────────────────────────
        document.getElementById('btnPrev').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 1) { currentMonth = 12; currentYear--; }
            selectedDate = null;
            document.getElementById('detailDateLabel').textContent = 'Pilih tanggal di kalender';
            document.getElementById('detailBody').innerHTML =
                `<div class="empty-detail"><i class="bi bi-hand-index-thumb"></i>Klik tanggal yang memiliki booking</div>`;
            fetchKalender(currentYear, currentMonth);
        });
        document.getElementById('btnNext').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 12) { currentMonth = 1; currentYear++; }
            selectedDate = null;
            document.getElementById('detailDateLabel').textContent = 'Pilih tanggal di kalender';
            document.getElementById('detailBody').innerHTML =
                `<div class="empty-detail"><i class="bi bi-hand-index-thumb"></i>Klik tanggal yang memiliki booking</div>`;
            fetchKalender(currentYear, currentMonth);
        });

        // Tabel tolak toggle
        function toggleTolak(id) {
            const el = document.getElementById('tolak-' + id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        // ── Inisialisasi ────────────────────────────────────────
        fetchKalender(currentYear, currentMonth);
    </script>
@endsection