@extends('layouts.app')

@section('title', 'Ubah kategori')

@section('content')
    <form method="POST" action="{{ route('categories.update', $category) }}" class="panel panel-pad form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required maxlength="100">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="description">Deskripsi</label>
                <input id="description" type="text" name="description" value="{{ old('description', $category->description) }}" maxlength="500">
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('categories.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
