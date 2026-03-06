<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'menunggu' => Kunjungan::where('status', 'Menunggu')->count(),
            'aktif' => Kunjungan::where('status', 'Aktif')->count(),
            'selesai' => Kunjungan::where('status', 'Selesai')->count(),
        ];

        $kunjunganMenunggu = Kunjungan::with('tamu')
            ->where('status', 'Menunggu')
            ->latest()
            ->take(10)
            ->get();

        return view('operator.dashboard', compact('stats', 'kunjunganMenunggu'));
    }
}
