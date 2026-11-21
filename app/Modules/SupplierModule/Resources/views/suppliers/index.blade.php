@extends('layouts.app')

@section('title', 'Pemasok')

@section('actions')
    @if (auth()->user()->isAdmin())
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Tambah pemasok</a>
    @endif
@endsection

@section('content')
    <form method="GET" action="{{ route('suppliers.index') }}" class="panel filters">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama pemasok" aria-label="Cari pemasok">
        <button type="submit" class="btn">Cari</button>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama pemasok</th>
                    <th>Kontak</th>
                    <th>Termin</th>
                    <th>Status</th>
                    @if (auth()->user()->isAdmin())<th></th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->code }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>
                            {{ $supplier->phone ?: '-' }}
                            @if ($supplier->email)<br><small>{{ $supplier->email }}</small>@endif
                        </td>
                        <td>{{ $supplier->paymentTerm->name }}</td>
                        <td>
                            @if ($supplier->is_active)
                                <span class="badge badge-ok">Aktif</span>
                            @else
                                <span class="badge badge-bad">Nonaktif</span>
                            @endif
                        </td>
                        @if (auth()->user()->isAdmin())
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm">Ubah</a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm(@js('Hapus pemasok '.$supplier->name.'?'))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Belum ada pemasok yang cocok. Tambahkan pemasok baru atau ubah kata kunci pencarian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $suppliers->links() }}
@endsection
