@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <section class="auth-card">
        <h1>Masuk ke Gudangku</h1>
        <p>Kelola barang, pemasok, dan arus stok gudang.</p>

        <form method="POST" action="{{ route('login.attempt') }}" class="form">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>
            <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>

        <p class="auth-alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai staf gudang</a></p>
    </section>
@endsection
