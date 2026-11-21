@extends('layouts.app')

@section('title', 'Kartu stok')

@section('content')
    <form method="GET" action="{{ route('stock-cards.index') }}" class="panel filters">
        <select name="product_id" aria-label="Filter barang">
            <option value="">Semua barang</option>
            @foreach ($productOptions as $option)
                <option value="{{ $option->id }}" @selected((string) ($filters['product_id'] ?? '') === (string) $option->id)>{{ $option->sku }} - {{ $option->name }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" aria-label="Dari tanggal">
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" aria-label="Sampai tanggal">
        <button type="submit" class="btn">Terapkan</button>
        <a href="{{ route('stock-cards.index') }}" class="btn">Atur ulang</a>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Referensi</th>
                    <th>Barang</th>
                    <th>Keterangan</th>
                    <th class="num">Masuk</th>
                    <th class="num">Keluar</th>
                    <th class="num">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cards as $card)
                    @php($product = $productNames[$card->product_id] ?? null)
                    <tr>
                        <td>{{ $card->occurred_at->format('d M Y') }}</td>
                        <td>{{ $card->reference_code }}</td>
                        <td>{{ $product?->name ?? 'Barang dihapus' }}@if ($product)<br><small>{{ $product->sku }}</small>@endif</td>
                        <td>{{ $card->notes ?: '-' }}</td>
                        <td class="num">{{ $card->movement_type->value === 'in' ? number_format($card->quantity, 0, ',', '.') : '' }}</td>
                        <td class="num">{{ $card->movement_type->value === 'out' ? number_format($card->quantity, 0, ',', '.') : '' }}</td>
                        <td class="num"><strong>{{ number_format($card->balance_after, 0, ',', '.') }}</strong>@if ($product) {{ $product->unit }}@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Tidak ada pergerakan stok untuk filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $cards->links() }}
@endsection
