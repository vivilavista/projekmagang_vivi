<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKunjungan;
use App\Models\MasterTujuan;
use Illuminate\Http\Request;

class JadwalKunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKunjungan::with('tujuan', 'disetujuiOleh')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        }

        $jadwalList = $query->paginate(15);
        return view('admin.jadwal.index', compact('jadwalList'));
    }

    public function kalenderData(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $jadwalList = JadwalKunjungan::with('tujuan')
            ->whereYear('tanggal_kunjungan', $tahun)
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->orderBy('jam_rencana')
            ->get();

        // Kelompokkan per tanggal
        $grouped = $jadwalList->groupBy(fn($j) => $j->tanggal_kunjungan->format('Y-m-d'));

        $result = [];
        foreach ($grouped as $tanggal => $items) {
            $result[$tanggal] = $items->map(fn($j) => [
                'id' => $j->id,
                'nama' => $j->nama_tamu,
                'no_hp' => $j->no_hp,
                'instansi' => $j->instansi,
                'tujuan' => $j->tujuan->nama ?? 'Umum',
                'keperluan' => $j->keperluan,
                'jam' => \Carbon\Carbon::parse($j->jam_rencana)->format('H:i'),
                'status' => $j->status,
                'setujui_url' => $j->status === 'Menunggu' ? route('admin.jadwal.setujui', $j->id) : null,
            ])->values();
        }

        return response()->json($result);
    }


    public function setujui(JadwalKunjungan $jadwal)
    {
        // 1. Update status jadwal
        $jadwal->update([
            'status' => 'Disetujui',
            'disetujui_oleh' => auth()->id(),
        ]);

        // 2. Cari atau Buat Data Tamu berdasarkan NIK atau No HP
        $tamu = \App\Models\Tamu::firstWhere('nik', $jadwal->nik);
        if (!$tamu && $jadwal->no_hp) {
            $tamu = \App\Models\Tamu::firstWhere('no_hp', $jadwal->no_hp);
        }

        if (!$tamu) {
            $tamu = \App\Models\Tamu::create([
                'nama' => $jadwal->nama_tamu,
                'nik' => $jadwal->nik,
                'no_hp' => $jadwal->no_hp,
                'alamat' => $jadwal->instansi ?? '-',
            ]);
        }

        // 3. Buat Data Kunjungan (Status: Menunggu)
        $jamRencana = \Carbon\Carbon::parse($jadwal->tanggal_kunjungan->format('Y-m-d') . ' ' . $jadwal->jam_rencana);

        \App\Models\Kunjungan::create([
            'tamu_id' => $tamu->id,
            'tujuan' => $jadwal->tujuan->nama ?? 'Umum',
            'instansi' => $jadwal->instansi,
            'keterangan' => $jadwal->keperluan,
            'jam_masuk' => $jamRencana, // Dianggap masuk sesuai jam rencana awal
            'status' => 'Menunggu', // Menunggu kedatangan / validasi operator
            'operator_id' => auth()->id(),
        ]);

        return back()->with('success', "Jadwal kunjungan atas nama {$jadwal->nama_tamu} telah disetujui dan masuk ke Data Kunjungan.");
    }

    public function tolak(Request $request, JadwalKunjungan $jadwal)
    {
        $request->validate(['catatan' => 'required|string|max:255']);
        $jadwal->update([
            'status' => 'Ditolak',
            'catatan' => $request->catatan,
            'disetujui_oleh' => auth()->id(),
        ]);
        return back()->with('success', "Jadwal kunjungan atas nama {$jadwal->nama_tamu} telah ditolak.");
    }

    public function destroy(JadwalKunjungan $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dihapus.');
    }
}
