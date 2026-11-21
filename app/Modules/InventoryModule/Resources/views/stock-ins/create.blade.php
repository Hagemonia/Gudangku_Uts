@extends('layouts.app')

@section('title', 'Catat stok masuk')

@section('content')
    <form method="POST" action="{{ route('stock-ins.store') }}" class="panel panel-pad form">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="supplier_id">Pemasok</label>
                <select id="supplier_id" name="supplier_id" required>
                    <option value="">Pilih pemasok</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>{{ $supplier->code }} - {{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="received_at">Tanggal terima</label>
                <input id="received_at" type="date" name="received_at" value="{{ old('received_at', now()->toDateString()) }}" required>
                @error('received_at')<span class="error">{{ $message }}</span>@enderror
            </div>

            @include('inventory::partials.items-editor', ['products' => $products, 'withCost' => true])

            <div class="field full">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" maxlength="500">{{ old('notes') }}</textarea>
                @error('notes')<span class="error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan stok masuk</button>
            <a href="{{ route('stock-ins.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
