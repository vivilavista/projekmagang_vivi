<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTujuan;
use Illuminate\Http\Request;

class MasterTujuanController extends Controller
{
    public function index()
    {
        $tujuanList = MasterTujuan::latest()->paginate(15);
        return view('admin.master_tujuan.index', compact('tujuanList'));
    }

    public function create()
    {
        return view('admin.master_tujuan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:master_tujuan,nama',
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        MasterTujuan::create($data);

        return redirect()->route('admin.master-tujuan.index')
            ->with('success', 'Tujuan kunjungan berhasil ditambahkan.');
    }

    public function edit(MasterTujuan $masterTujuan)
    {
        return view('admin.master_tujuan.edit', compact('masterTujuan'));
    }

    public function update(Request $request, MasterTujuan $masterTujuan)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:master_tujuan,nama,' . $masterTujuan->id,
            'deskripsi' => 'nullable|string',
            'aktif' => 'boolean',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $masterTujuan->update($data);

        return redirect()->route('admin.master-tujuan.index')
            ->with('success', 'Tujuan kunjungan berhasil diperbarui.');
    }

    public function destroy(MasterTujuan $masterTujuan)
    {
        $masterTujuan->delete();
        return redirect()->route('admin.master-tujuan.index')
            ->with('success', 'Tujuan kunjungan berhasil dihapus.');
    }
}
