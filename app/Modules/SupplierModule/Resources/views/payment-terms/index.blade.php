@extends('layouts.app')

@section('title', 'Termin pembayaran')

@section('content')
    <form method="POST" action="{{ route('payment-terms.store') }}" class="panel panel-pad form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama termin</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="50">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="days">Jatuh tempo (hari)</label>
                <input id="days" type="number" name="days" min="0" max="365" value="{{ old('days', 0) }}" required>
                @error('days')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field full">
                <label for="description">Keterangan</label>
                <input id="description" type="text" name="description" value="{{ old('description') }}" maxlength="255">
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Tambah termin</button></div>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th class="num">Jatuh tempo</th><th>Keterangan</th><th class="num">Jumlah pemasok</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($terms as $term)
                    <tr>
                        <td>{{ $term->name }}</td>
                        <td class="num">{{ $term->days }} hari</td>
                        <td>{{ $term->description ?: '-' }}</td>
                        <td class="num">{{ $term->suppliers_count }}</td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('payment-terms.edit', $term) }}" class="btn btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('payment-terms.destroy', $term) }}" onsubmit="return confirm(@js('Hapus termin '.$term->name.'?'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada termin pembayaran. Isi form di atas untuk menambahkan yang pertama.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $terms->links() }}
@endsection
