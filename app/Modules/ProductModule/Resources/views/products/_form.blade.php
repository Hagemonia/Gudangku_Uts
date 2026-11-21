@php
    $selectedCategories = old('category_ids', isset($product) ? $product->categories->pluck('id')->all() : []);
@endphp

<div class="form-grid">
    <div class="field">
        <label for="sku">SKU</label>
        <input id="sku" type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required maxlength="50">
        @error('sku')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="name">Nama barang</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required maxlength="150">
        @error('name')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="unit_id">Satuan</label>
        <select id="unit_id" name="unit_id" required>
            <option value="">Pilih satuan</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected((string) old('unit_id', $product->unit_id ?? '') === (string) $unit->id)>{{ $unit->name }} ({{ $unit->symbol }})</option>
            @endforeach
        </select>
        @error('unit_id')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="min_stock">Stok minimum</label>
        <input id="min_stock" type="number" name="min_stock" min="0" value="{{ old('min_stock', $product->min_stock ?? 0) }}" required>
        <span class="hint">Barang masuk daftar "stok menipis" saat stoknya sama dengan atau di bawah angka ini.</span>
        @error('min_stock')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field full">
        <span class="label">Kategori</span>
        <div class="check-list">
            @foreach ($categories as $category)
                <label class="check">
                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" @checked(in_array($category->id, $selectedCategories))>
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
        @error('category_ids')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field full">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" maxlength="1000">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<span class="error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <span class="label">Status</span>
        <input type="hidden" name="is_active" value="0">
        <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Aktif dan bisa ditransaksikan</label>
    </div>

    @isset($product)
        <div class="field">
            <span class="label">Stok saat ini</span>
            <strong>{{ number_format($product->stock, 0, ',', '.') }} {{ $product->unit->symbol }}</strong>
            <span class="hint">Stok berubah lewat transaksi stok masuk dan stok keluar.</span>
        </div>
    @endisset
</div>
