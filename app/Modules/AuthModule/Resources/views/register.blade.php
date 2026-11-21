@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <section class="auth-card">
        <h1>Buat akun staf gudang</h1>
        <p>Akun admin hanya dibuat oleh administrator sistem.</p>

        <form method="POST" action="{{ route('register.store') }}" class="form">
            @csrf
            <div class="field">
                <label for="name">Nama lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                <span class="hint">Minimal 8 karakter, kombinasi huruf dan angka.</span>
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Ulangi password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Buat akun</button>
        </form>

        <p class="auth-alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </section>
@endsection
