<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTamuRequest;
use App\Http\Requests\Admin\UpdateTamuRequest;
use App\Services\TamuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TamuController extends Controller
{
    public function __construct(protected TamuService $tamuService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only('search');
        $tamuList = $this->tamuService->paginate(15, $filters);

        return view('admin.tamu.index', compact('tamuList', 'filters'));
    }

    public function create(): View
    {
        return view('admin.tamu.create');
    }

    public function store(StoreTamuRequest $request): RedirectResponse
    {
        $this->tamuService->create(
            $request->safe()->except('foto_ktp'),
            $request->file('foto_ktp')
        );

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $tamu = $this->tamuService->findById($id);

        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(UpdateTamuRequest $request, int $id): RedirectResponse
    {
        $this->tamuService->update(
            $id,
            $request->safe()->except('foto_ktp'),
            $request->file('foto_ktp')
        );

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->tamuService->delete($id);

        return redirect()->route('admin.tamu.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}
