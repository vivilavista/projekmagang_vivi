<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request->validated());

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if ($this->authService->login($request->validated())) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'petugas' => redirect()->route('petugas.dashboard'),
                'operator' => redirect()->route('operator.dashboard'),
                'pengguna' => redirect()->route('booking.index'),
                default => redirect()->route('login'),
            };
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput($request->only('username'));
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
