<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string'
        ]);

        $kunjungan = Kunjungan::with('tamu')->where('kode_qr', $request->kode_qr)->first();

        if (!$kunjungan) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau data tidak ditemukan.',
            ], 404);
        }

        if ($kunjungan->status === 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => "Tamu {$kunjungan->tamu->nama} sudah Check-In dan sedang aktif.",
            ], 400);
        }

        if ($kunjungan->status === 'Selesai') {
            return response()->json([
                'success' => false,
                'message' => "Kunjungan {$kunjungan->tamu->nama} sudah Selesai.",
            ], 400);
        }

        // Jika status Menunggu atau Disetujui, maka proses Check-In
        $kunjungan->update([
            'status' => 'Aktif',
            'jam_masuk' => Carbon::now(), // Update jam masuk ke detik saat di-scan
        ]);

        // Simpan log audit secara manual jika tidak ter-cover observer
        if (class_exists(\App\Services\AuditLogService::class)) {
            app(\App\Services\AuditLogService::class)->log(
                'Auto Check-In via QR Scanner',
                'Kunjungan',
                $kunjungan->id
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil Check-In! Selamat datang, {$kunjungan->tamu->nama}.",
        ], 200);
    }
}
