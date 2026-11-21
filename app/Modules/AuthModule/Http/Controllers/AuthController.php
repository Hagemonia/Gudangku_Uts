<?php

namespace App\Modules\AuthModule\Http\Controllers;

use App\Modules\AuthModule\Enums\Role;
use App\Modules\AuthModule\Http\Requests\LoginRequest;
use App\Modules\AuthModule\Http\Requests\RegisterRequest;
use App\Modules\AuthModule\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController
{
    public function showLogin(): View
    {
        return view('access::login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function showRegister(): View
    {
        return view('access::register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            ...$request->safe()->only(['name', 'email', 'password']),
            'role' => Role::Staff,
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Akun berhasil dibuat. Selamat datang, '.$user->name.'.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
