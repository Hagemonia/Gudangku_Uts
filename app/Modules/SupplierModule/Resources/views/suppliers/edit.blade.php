@extends('layouts.app')

@section('title', 'Ubah pemasok')

@section('content')
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="panel panel-pad form">
        @csrf
        @method('PUT')
        @include('supplier::suppliers._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            <a href="{{ route('suppliers.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
