<?php

namespace App\Modules\ProductModule\Services;

use App\Modules\ProductModule\Exceptions\ResourceInUseException;
use App\Modules\ProductModule\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::query()->create(Arr::except($data, ['category_ids']));
            $product->categories()->sync($data['category_ids']);

            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update(Arr::except($data, ['category_ids']));
            $product->categories()->sync($data['category_ids']);

            return $product;
        });
    }

    public function delete(Product $product): void
    {
        if ($product->stock > 0) {
            throw new ResourceInUseException(
                "Barang {$product->name} masih memiliki stok {$product->stock}. Keluarkan stoknya lebih dulu sebelum menghapus."
            );
        }

        $product->delete();
    }
}
