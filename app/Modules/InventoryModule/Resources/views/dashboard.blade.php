@extends('layouts.app')

@section('title', 'Dashboard')

@section('actions')
    <a href="{{ route('stock-ins.create') }}" class="btn btn-primary">Catat stok masuk</a>
    <a href="{{ route('stock-outs.create') }}" class="btn">Catat stok keluar</a>
@endsection

@section('content')
    <section class="strip" aria-label="Ringkasan gudang">
        <div><span>Jenis barang</span><strong>{{ number_format($summary['total'], 0, ',', '.') }}</strong></div>
        <div><span>Total unit di gudang</span><strong>{{ number_format($summary['total_units'], 0, ',', '.') }}</strong></div>
        <div @class(['alarm' => $summary['low_stock'] > 0])><span>Stok menipis</span><strong>{{ $summary['low_stock'] }}</strong></div>
        <div><span>Pemasok aktif</span><strong>{{ $supplierCount }}</strong></div>
        <div><span>Stok masuk hari ini</span><strong>{{ $stockInToday }}</strong></div>
        <div><span>Stok keluar hari ini</span><strong>{{ $stockOutToday }}</strong></div>
    </section>

    <div class="two-col">
        <section class="panel">
            <h2>Perlu restock</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Barang</th><th class="num">Stok</th><th class="num">Minimum</th></tr></thead>
                    <tbody>
                        @forelse ($lowStock as $product)
                            <tr>
                                <td>{{ $product->name }}<br><small>{{ $product->sku }}</small></td>
                                <td class="num stock-low">{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit }}</td>
                                <td class="num">{{ number_format($product->minStock, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">Semua barang aktif masih di atas stok minimum.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <h2>Pergerakan stok terakhir</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th class="num">Jumlah</th></tr></thead>
                    <tbody>
                        @forelse ($recent as $card)
                            <tr>
                                <td>{{ $card->occurred_at->format('d M Y') }}</td>
                                <td>
                                    {{ $productNames[$card->product_id]->name ?? 'Barang dihapus' }}<br>
                                    <small>{{ $card->reference_code }}</small>
                                </td>
                                <td class="num">
                                    <span class="badge badge-{{ $card->movement_type->value }}">{{ $card->movement_type->label() }}</span>
                                    {{ number_format($card->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">Belum ada pergerakan stok. Catat stok masuk pertama untuk memulai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
