@extends('layouts.app')

@section('title', 'Catat stok keluar')

@section('content')
    <form method="POST" action="{{ route('stock-outs.store') }}" class="panel panel-pad form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="issued_at">Tanggal keluar</label>
                <input id="issued_at" type="date" name="issued_at" value="{{ old('issued_at', now()->toDateString()) }}" required>
                @error('issued_at')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="reason">Alasan pengeluaran</label>
                <select id="reason" name="reason" required>
                    @foreach ($reasons as $reason)
                        <option value="{{ $reason->value }}" @selected(old('reason', 'penjualan') === $reason->value)>{{ $reason->label() }}</option>
                    @endforeach
                </select>
                @error('reason')<span class="error">{{ $message }}</span>@enderror
            </div>

            @include('inventory::partials.items-editor', ['products' => $products, 'withCost' => false])

            <div class="field full">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" maxlength="500">{{ old('notes') }}</textarea>
                @error('notes')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan stok keluar</button>
            <a href="{{ route('stock-outs.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
