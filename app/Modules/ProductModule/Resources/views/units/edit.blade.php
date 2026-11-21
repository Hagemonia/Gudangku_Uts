@extends('layouts.app')

@section('title', 'Ubah satuan')

@section('content')
    <form method="POST" action="{{ route('units.update', $unit) }}" class="panel panel-pad form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama satuan</label>
                <input id="name" type="text" name="name" value="{{ old('name', $unit->name) }}" required maxlength="50">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="symbol">Simbol</label>
                <input id="symbol" type="text" name="symbol" value="{{ old('symbol', $unit->symbol) }}" required maxlength="10">
                @error('symbol')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('units.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
