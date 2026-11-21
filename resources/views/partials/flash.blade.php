@if (session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-error" role="alert">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <strong>Ada isian yang perlu diperbaiki:</strong>
        <ul>
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
