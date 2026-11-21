<tr>
    <td>
        <select name="items[{{ $index }}][product_id]" required aria-label="Barang">
            <option value="">Pilih barang</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @selected((string) ($row['product_id'] ?? '') === (string) $product->id)>
                    {{ $product->sku }} - {{ $product->name }} (stok {{ $product->stock }} {{ $product->unit }})
                </option>
            @endforeach
        </select>
        @error("items.$index.product_id")<span class="error">{{ $message }}</span>@enderror
    </td>
    <td>
        <input type="number" name="items[{{ $index }}][quantity]" min="1" value="{{ $row['quantity'] ?? 1 }}" required aria-label="Jumlah">
        @error("items.$index.quantity")<span class="error">{{ $message }}</span>@enderror
    </td>
    @if ($withCost)
        <td>
            <input type="number" name="items[{{ $index }}][unit_cost]" min="0" step="0.01" value="{{ $row['unit_cost'] ?? 0 }}" aria-label="Harga satuan">
            @error("items.$index.unit_cost")<span class="error">{{ $message }}</span>@enderror
        </td>
    @endif
    <td><button type="button" class="btn btn-sm btn-danger" data-remove>Hapus</button></td>
</tr>
