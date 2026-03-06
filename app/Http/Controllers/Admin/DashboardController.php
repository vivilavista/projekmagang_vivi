<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KunjunganService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected KunjunganService $kunjunganService)
    {
    }

    public function index(): View
    {
        $stats = $this->kunjunganService->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }
}
