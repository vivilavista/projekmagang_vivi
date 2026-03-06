<?php

namespace App\Http\Controllers;

use App\Models\JadwalKunjungan;
use App\Models\MasterTujuan;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Halaman booking publik (tanpa login)
     */
    public function index()
    {
        $tujuanList = MasterTujuan::aktif()->orderBy('nama')->get();
        return view('booking.index', compact('tujuanList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_tamu' => 'required|string|max:255',
            'nik' => 'nullable|digits:16',
            'no_hp' => 'required|string|max:20',
            'instansi' => 'nullable|string|max:255',
            'tujuan_id' => 'required|exists:master_tujuan,id',
            'keperluan' => 'nullable|string|max:500',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jam_rencana' => 'required|date_format:H:i',
        ], [
            'nama_tamu.required' => 'Nama tamu wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'tujuan_id.required' => 'Tujuan kunjungan wajib dipilih.',
            'tujuan_id.exists' => 'Tujuan kunjungan tidak valid.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'jam_rencana.required' => 'Jam kunjungan wajib diisi.',
        ]);

        $jadwal = JadwalKunjungan::create($data);

        return redirect()->route('booking.sukses', $jadwal->id);
    }

    public function sukses(JadwalKunjungan $jadwal)
    {
        return view('booking.sukses', compact('jadwal'));
    }

    public function status()
    {
        return view('booking.status');
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'identitas' => 'required|string',
        ], [
            'identitas.required' => 'NIK atau No HP wajib diisi.',
        ]);

        $identitas = $request->identitas;

        // Cari Jadwal terbaru berdasarkan NIK atau No HP
        $jadwal = JadwalKunjungan::where('nik', $identitas)
            ->orWhere('no_hp', $identitas)
            ->latest('created_at')
            ->first();

        // Cari Kunjungan terkait jika sudah disetujui (untuk mengambil QR Code)
        $kunjungan = null;
        if ($jadwal && $jadwal->status === 'Disetujui') {
            $tamu = \App\Models\Tamu::where('nik', $jadwal->nik)
                ->orWhere('no_hp', $jadwal->no_hp)
                ->first();

            if ($tamu) {
                // Cocokkan kunjungan berdasarkan tamu dan tanggal
                $kunjungan = \App\Models\Kunjungan::where('tamu_id', $tamu->id)
                    ->whereDate('jam_masuk', $jadwal->tanggal_kunjungan)
                    ->latest()
                    ->first();
            }
        }

        if (!$jadwal) {
            return back()->with('error', 'Data jadwal kunjungan tidak ditemukan dengan NIK atau No HP tersebut.')->withInput();
        }

        return view('booking.status_result', compact('jadwal', 'kunjungan'));
    }
}
