<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') | Gudangku</title>
    <link rel="stylesheet" href="{{ asset('css/warehouse.css') }}">
</head>
<body>
@auth
    @php($currentUser = auth()->user())
    <div class="app">
        <aside class="sidebar">
            <a class="brand" href="{{ url('/') }}"><span class="brand-mark">G</span> Gudangku</a>
            <nav class="nav" aria-label="Menu utama">
                <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>Dashboard</a>

                <span class="nav-label">Data master</span>
                <a href="{{ route('products.index') }}" @class(['active' => request()->routeIs('products.*')])>Barang</a>
                @if ($currentUser->isAdmin())
                    <a href="{{ route('categories.index') }}" @class(['active' => request()->routeIs('categories.*')])>Kategori</a>
                    <a href="{{ route('units.index') }}" @class(['active' => request()->routeIs('units.*')])>Satuan</a>
                @endif
                <a href="{{ route('suppliers.index') }}" @class(['active' => request()->routeIs('suppliers.*')])>Pemasok</a>
                @if ($currentUser->isAdmin())
                    <a href="{{ route('payment-terms.index') }}" @class(['active' => request()->routeIs('payment-terms.*')])>Termin pembayaran</a>
                @endif

                <span class="nav-label">Transaksi</span>
                <a href="{{ route('stock-ins.index') }}" @class(['active' => request()->routeIs('stock-ins.*')])>Stok masuk</a>
                <a href="{{ route('stock-outs.index') }}" @class(['active' => request()->routeIs('stock-outs.*')])>Stok keluar</a>
                <a href="{{ route('stock-cards.index') }}" @class(['active' => request()->routeIs('stock-cards.*')])>Kartu stok</a>
            </nav>
            <div class="side-user">
                <strong>{{ $currentUser->name }}</strong>
                <span>{{ $currentUser->role->label() }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm btn-block">Keluar</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <header class="page-head">
                <h1>@yield('title')</h1>
                <div class="actions">@yield('actions')</div>
            </header>
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
@else
    <div class="guest">
        <div class="auth-wrap">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
@endauth
</body>
</html>
