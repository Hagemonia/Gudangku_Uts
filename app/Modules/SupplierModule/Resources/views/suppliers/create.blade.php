@extends('layouts.app')

@section('title', 'Tambah pemasok')

@section('content')
    <form method="POST" action="{{ route('suppliers.store') }}" class="panel panel-pad form">
        @csrf
        @include('supplier::suppliers._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan pemasok</button>
            <a href="{{ route('suppliers.index') }}" class="btn">Batal</a>
        </div>
    </form>
@endsection
