<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateKunjunganRequest;
use App\Services\KunjunganService;
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

        return view('admin.kunjungan.index', compact('kunjungan', 'filters'));
    }

    public function edit(int $id): View
    {
        $kunjungan = $this->kunjunganService->findById($id);

        return view('admin.kunjungan.edit', compact('kunjungan'));
    }

    public function update(UpdateKunjunganRequest $request, int $id): RedirectResponse
    {
        $this->kunjunganService->update(
            $id,
            $request->safe()->except('foto_wajah'),
            $request->file('foto_wajah')
        );

        return redirect()->route('admin.kunjungan.index')->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    public function checkout(int $id): RedirectResponse
    {
        $this->kunjunganService->checkout($id);

        return redirect()->route('admin.kunjungan.index')->with('success', 'Kunjungan berhasil di-checkout.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->kunjunganService->delete($id);

        return redirect()->route('admin.kunjungan.index')->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
