<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with('tamu', 'operator')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas(
                'tamu',
                fn($q) =>
                $q->where('nama', 'like', '%' . $request->search . '%')
            );
        }

        $filters = $request->only('search', 'status');
        $kunjungan = $query->paginate(15);

        return view('operator.kunjungan.index', compact('kunjungan', 'filters'));
    }

    public function show(int $id)
    {
        $kunjungan = Kunjungan::with('tamu', 'operator')->findOrFail($id);
        return view('operator.kunjungan.show', compact('kunjungan'));
    }

    /**
     * Validasi: Menunggu → Aktif
     */
    public function validasi(int $id)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        if ($kunjungan->status !== 'Menunggu') {
            return back()->with('error', 'Kunjungan tidak dalam status Menunggu.');
        }

        $kunjungan->update([
            'status' => 'Aktif',
            'jam_masuk' => now(),
        ]);

        return redirect()->route('operator.kunjungan.index')
            ->with('success', "Kunjungan #{$id} telah divalidasi — tamu diizinkan masuk.");
    }

    /**
     * Tolak: Menunggu → hapus / tolak
     */
    public function tolak(int $id)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        if ($kunjungan->status !== 'Menunggu') {
            return back()->with('error', 'Hanya kunjungan berstatus Menunggu yang bisa ditolak.');
        }

        $kunjungan->delete(); // soft delete

        return redirect()->route('operator.kunjungan.index')
            ->with('success', "Kunjungan #{$id} telah ditolak.");
    }
}
