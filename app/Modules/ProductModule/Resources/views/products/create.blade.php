@extends('layouts.app')

@section('title', 'Tambah barang')

@section('content')
    <form method="POST" action="{{ route('products.store') }}" class="panel panel-pad form">
        @csrf
        @include('product::products._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan barang</button>
            <a href="{{ route('products.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
