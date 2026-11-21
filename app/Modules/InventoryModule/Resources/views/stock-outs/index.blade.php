@extends('layouts.app')

@section('title', 'Stok keluar')

@section('actions')
    <a href="{{ route('stock-outs.create') }}" class="btn btn-primary">Catat stok keluar</a>
@endsection

@section('content')
    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Tanggal keluar</th><th>Alasan</th><th class="num">Jenis barang</th><th class="num">Total unit</th><th>Dicatat oleh</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($stockOuts as $stockOut)
                    <tr>
                        <td>{{ $stockOut->code }}</td>
                        <td>{{ $stockOut->issued_at->format('d M Y') }}</td>
                        <td>{{ $stockOut->reason->label() }}</td>
                        <td class="num">{{ $stockOut->items_count }}</td>
                        <td class="num">{{ number_format((int) $stockOut->items_sum_quantity, 0, ',', '.') }}</td>
                        <td>{{ $stockOut->created_by }}</td>
                        <td><div class="row-actions"><a href="{{ route('stock-outs.show', $stockOut) }}" class="btn btn-sm">Lihat</a></div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">Belum ada pengeluaran barang. Catat stok keluar saat barang meninggalkan gudang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $stockOuts->links() }}
@endsection
