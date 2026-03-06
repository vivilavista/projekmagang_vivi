<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Services\KunjunganService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected KunjunganService $kunjunganService)
    {
    }

    public function index(): View
    {
        $stats = $this->kunjunganService->getDashboardStats();
        $kunjunganAktif = $this->kunjunganService->paginate(5, [
            'status' => 'Aktif',
            'operator_id' => Auth::id(),
        ]);

        return view('petugas.dashboard', compact('stats', 'kunjunganAktif'));
    }
}
