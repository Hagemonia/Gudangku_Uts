@extends('layouts.app')

@section('title', 'Stok masuk')

@section('actions')
    <a href="{{ route('stock-ins.create') }}" class="btn btn-primary">Catat stok masuk</a>
@endsection

@section('content')
    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Tanggal terima</th><th>Pemasok</th><th class="num">Jenis barang</th><th class="num">Total unit</th><th>Dicatat oleh</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($stockIns as $stockIn)
                    <tr>
                        <td>{{ $stockIn->code }}</td>
                        <td>{{ $stockIn->received_at->format('d M Y') }}</td>
                        <td>{{ $stockIn->supplier_name }}</td>
                        <td class="num">{{ $stockIn->items_count }}</td>
                        <td class="num">{{ number_format((int) $stockIn->items_sum_quantity, 0, ',', '.') }}</td>
                        <td>{{ $stockIn->created_by }}</td>
                        <td><div class="row-actions"><a href="{{ route('stock-ins.show', $stockIn) }}" class="btn btn-sm">Lihat</a></div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Belum ada penerimaan barang. Catat stok masuk pertama dari pemasok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $stockIns->links() }}
@endsection
