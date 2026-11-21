@extends('layouts.app')

@section('title', 'Ubah termin pembayaran')

@section('content')
    <form method="POST" action="{{ route('payment-terms.update', $term) }}" class="panel panel-pad form">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama termin</label>
                <input id="name" type="text" name="name" value="{{ old('name', $term->name) }}" required maxlength="50">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="days">Jatuh tempo (hari)</label>
                <input id="days" type="number" name="days" min="0" max="365" value="{{ old('days', $term->days) }}" required>
                @error('days')<span class="error">{{ $message }}</span>@enderror
            </div>
            <div class="field full">
                <label for="description">Keterangan</label>
                <input id="description" type="text" name="description" value="{{ old('description', $term->description) }}" maxlength="255">
                @error('description')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('payment-terms.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
