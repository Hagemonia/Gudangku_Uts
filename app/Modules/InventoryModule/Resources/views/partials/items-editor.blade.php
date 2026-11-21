@php
    $rows = old('items', [['product_id' => '', 'quantity' => 1, 'unit_cost' => 0]]);
@endphp

<div class="field full">
    <span class="label">Daftar barang</span>
    <div class="panel table-wrap" style="margin-bottom: 0">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th style="width: 130px">Jumlah</th>
                    @if ($withCost)<th style="width: 180px">Harga satuan (Rp)</th>@endif
                    <th style="width: 90px"></th>
                </tr>
            </thead>
            <tbody id="items-body">
                @foreach ($rows as $index => $row)
                    @include('inventory::partials.item-row', ['index' => $index, 'row' => $row, 'products' => $products, 'withCost' => $withCost])
                @endforeach
            </tbody>
        </table>
    </div>
    @error('items')<span class="error">{{ $message }}</span>@enderror
    <div><button type="button" class="btn btn-sm" id="add-row">Tambah baris</button></div>
</div>

<template id="item-row-template">
    @include('inventory::partials.item-row', ['index' => '__INDEX__', 'row' => [], 'products' => $products, 'withCost' => $withCost])
</template>

<script>
    (() => {
        const body = document.getElementById('items-body');
        const template = document.getElementById('item-row-template');
        let index = body.querySelectorAll('tr').length;

        document.getElementById('add-row').addEventListener('click', () => {
            body.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', index++));
        });

        body.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove]');

            if (button && body.querySelectorAll('tr').length > 1) {
                button.closest('tr').remove();
            }
        });
    })();
</script>
