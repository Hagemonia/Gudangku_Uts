@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <form method="POST" action="{{ route('categories.store') }}" class="panel panel-pad form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="100">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="description">Deskripsi</label>
                <input id="description" type="text" name="description" value="{{ old('description') }}" maxlength="500">
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Tambah kategori</button></div>
    </form>

    <div class="panel table-wrap">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Deskripsi</th><th class="num">Jumlah barang</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?: '-' }}</td>
                        <td class="num">{{ $category->products_count }}</td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm(@js('Hapus kategori '.$category->name.'?'))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Belum ada kategori. Isi form di atas untuk menambahkan yang pertama.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}
@endsection
