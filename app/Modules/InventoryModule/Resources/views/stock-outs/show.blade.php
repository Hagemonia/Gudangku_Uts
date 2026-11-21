@extends('layouts.app')

@section('title', 'Stok keluar '.$stockOut->code)

@section('actions')
    <a href="{{ route('stock-outs.index') }}" class="btn">Kembali ke daftar</a>
@endsection

@section('content')
    <section class="panel panel-pad">
        <dl class="detail">
            <div><dt>Kode</dt><dd>{{ $stockOut->code }}</dd></div>
            <div><dt>Tanggal keluar</dt><dd>{{ $stockOut->issued_at->format('d M Y') }}</dd></div>
            <div><dt>Alasan</dt><dd>{{ $stockOut->reason->label() }}</dd></div>
            <div><dt>Dicatat oleh</dt><dd>{{ $stockOut->created_by }}</dd></div>
            <div><dt>Waktu pencatatan</dt><dd>{{ $stockOut->created_at->format('d M Y H:i') }}</dd></div>
            <div><dt>Catatan</dt><dd>{{ $stockOut->notes ?: '-' }}</dd></div>
        </dl>
    </section>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>SKU</th><th>Barang</th><th class="num">Jumlah</th></tr>
            </thead>
            <tbody>
                @foreach ($stockOut->items as $item)
                    <tr>
                        <td>{{ $item->product_sku }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td class="num">{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
