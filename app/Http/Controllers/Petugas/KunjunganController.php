<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKunjunganRequest;
use App\Http\Requests\Admin\UpdateKunjunganRequest;
use App\Models\Tamu;
use App\Services\KunjunganService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KunjunganController extends Controller
{
    public function __construct(protected KunjunganService $kunjunganService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only('search', 'status');
        $kunjungan = $this->kunjunganService->paginate(15, $filters);

        return view('petugas.kunjungan.index', compact('kunjungan', 'filters'));
    }

    public function create(): View
    {
        $tamuList = Tamu::orderBy('nama')->get();

        return view('petugas.kunjungan.create', compact('tamuList'));
    }

    public function store(StoreKunjunganRequest $request): RedirectResponse
    {
        $this->kunjunganService->create(
            $request->safe()->except('foto_wajah'),
            $request->file('foto_wajah')
        );

        return redirect()->route('petugas.kunjungan.index')->with('success', 'Kunjungan berhasil dicatat.');
    }

    public function edit(int $id): View
    {
        $kunjungan = $this->kunjunganService->findById($id);
        $tamuList = Tamu::orderBy('nama')->get();

        return view('petugas.kunjungan.edit', compact('kunjungan', 'tamuList'));
    }

    public function update(UpdateKunjunganRequest $request, int $id): RedirectResponse
    {
        $this->kunjunganService->update(
            $id,
            $request->safe()->except('foto_wajah'),
            $request->file('foto_wajah')
        );

        return redirect()->route('petugas.kunjungan.index')->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function checkout(int $id): RedirectResponse|JsonResponse
    {
        $this->kunjunganService->checkout($id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tamu telah checkout.',
            ]);
        }

        return redirect()->route('petugas.kunjungan.index')->with('success', 'Tamu berhasil checkout.');
    }

    /**
     * Operator validasi kunjungan: Menunggu → Aktif
     */
    public function validasi(int $id): RedirectResponse
    {
        $kunjungan = $this->kunjunganService->findById($id);

        if ($kunjungan->status !== 'Menunggu') {
            return back()->with('error', 'Kunjungan tidak dalam status Menunggu.');
        }

        $kunjungan->update([
            'status' => 'Aktif',
            'jam_masuk' => now(),
        ]);

        return redirect()->route('petugas.kunjungan.index')->with('success', 'Kunjungan berhasil divalidasi dan diaktifkan.');
    }
}
