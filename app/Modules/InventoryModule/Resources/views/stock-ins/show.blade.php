@extends('layouts.app')

@section('title', 'Stok masuk '.$stockIn->code)

@section('actions')
    <a href="{{ route('stock-ins.index') }}" class="btn">Kembali ke daftar</a>
@endsection

@section('content')
    <section class="panel panel-pad">
        <dl class="detail">
            <div><dt>Kode</dt><dd>{{ $stockIn->code }}</dd></div>
            <div><dt>Tanggal terima</dt><dd>{{ $stockIn->received_at->format('d M Y') }}</dd></div>
            <div><dt>Pemasok</dt><dd>{{ $stockIn->supplier_name }}</dd></div>
            <div><dt>Dicatat oleh</dt><dd>{{ $stockIn->created_by }}</dd></div>
            <div><dt>Waktu pencatatan</dt><dd>{{ $stockIn->created_at->format('d M Y H:i') }}</dd></div>
            <div><dt>Catatan</dt><dd>{{ $stockIn->notes ?: '-' }}</dd></div>
        </dl>
    </section>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>SKU</th><th>Barang</th><th class="num">Jumlah</th><th class="num">Harga satuan</th><th class="num">Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach ($stockIn->items as $item)
                    <tr>
                        <td>{{ $item->product_sku }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td class="num">{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit }}</td>
                        <td class="num">Rp {{ number_format((float) $item->unit_cost, 0, ',', '.') }}</td>
                        <td class="num">Rp {{ number_format($item->quantity * (float) $item->unit_cost, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="num">Total nilai penerimaan</th>
                    <th class="num">Rp {{ number_format($stockIn->items->sum(fn ($item) => $item->quantity * (float) $item->unit_cost), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
