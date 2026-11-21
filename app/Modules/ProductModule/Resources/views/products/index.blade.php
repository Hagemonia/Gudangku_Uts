@extends('layouts.app')

@section('title', 'Barang')

@section('actions')
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah barang</a>
    @endif
@endsection

@section('content')
    <form method="GET" action="{{ route('products.index') }}" class="panel filters">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari SKU atau nama barang" aria-label="Cari barang">
        <select name="category" aria-label="Filter kategori">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <label class="check"><input type="checkbox" name="low" value="1" @checked(request()->boolean('low'))> Stok menipis saja</label>
        <button type="submit" class="btn">Terapkan</button>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama barang</th>
                    <th>Kategori</th>
                    <th class="num">Stok</th>
                    <th class="num">Minimum</th>
                    <th>Status</th>
                    @if (auth()->user()->isAdmin())<th></th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            <div class="badges">
                                @foreach ($product->categories as $category)
                                    <span class="badge">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="num @if ($product->isLowStock()) stock-low @endif">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit->symbol }}</td>
                        <td class="num">{{ number_format($product->min_stock, 0, ',', '.') }}</td>
                        <td>
                            @if (! $product->is_active)
                                <span class="badge badge-bad">Nonaktif</span>
                            @elseif ($product->isLowStock())
                                <span class="badge badge-warn">Stok menipis</span>
                            @else
                                <span class="badge badge-ok">Aman</span>
                            @endif
                        </td>
                        @if (auth()->user()->isAdmin())
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm">Ubah</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm(@js('Hapus barang '.$product->name.'?'))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Belum ada barang yang cocok. Tambahkan barang baru atau ubah filter pencarian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
@endsection
