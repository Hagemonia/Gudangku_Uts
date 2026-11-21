@extends('layouts.app')

@section('title', 'Ubah barang')

@section('content')
    <form method="POST" action="{{ route('products.update', $product) }}" class="panel panel-pad form">
        @csrf
        @method('PUT')
        @include('product::products._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('products.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
