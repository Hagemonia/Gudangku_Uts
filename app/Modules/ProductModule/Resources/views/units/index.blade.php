@extends('layouts.app')

@section('title', 'Satuan')

@section('content')
    <form method="POST" action="{{ route('units.store') }}" class="panel panel-pad form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama satuan</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="50">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="symbol">Simbol</label>
                <input id="symbol" type="text" name="symbol" value="{{ old('symbol') }}" required maxlength="10">
                @error('symbol')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Tambah satuan</button></div>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Simbol</th><th class="num">Jumlah barang</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->symbol }}</td>
                        <td class="num">{{ $unit->products_count }}</td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('units.destroy', $unit) }}" onsubmit="return confirm(@js('Hapus satuan '.$unit->name.'?'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Belum ada satuan. Isi form di atas untuk menambahkan yang pertama.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $units->links() }}
@endsection
