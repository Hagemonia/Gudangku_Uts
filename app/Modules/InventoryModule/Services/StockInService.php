<?php

namespace App\Modules\InventoryModule\Services;

use App\Modules\InventoryModule\Enums\MovementType;
use App\Modules\InventoryModule\Exceptions\InvalidStockTransactionException;
use App\Modules\InventoryModule\Models\StockCard;
use App\Modules\InventoryModule\Models\StockIn;
use App\Modules\InventoryModule\Support\DocumentCode;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use Illuminate\Support\Facades\DB;

class StockInService
{
    public function __construct(
        private readonly ProductCatalogContract $products,
        private readonly SupplierDirectoryContract $suppliers,
    ) {
    }

    public function record(array $data, int $userId, string $userName): StockIn
    {
        return DB::transaction(function () use ($data, $userId, $userName) {
            $supplier = $this->suppliers->find((int) $data['supplier_id']);

            if ($supplier === null || ! $supplier->isActive) {
                throw InvalidStockTransactionException::inactiveSupplier();
            }

            $products = $this->products->findMany(collect($data['items'])->pluck('product_id')->all());

            $stockIn = StockIn::query()->create([
                'code' => DocumentCode::make('SI'),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'received_at' => $data['received_at'],
                'notes' => $data['notes'] ?? null,
                'user_id' => $userId,
                'created_by' => $userName,
            ]);

            foreach ($data['items'] as $item) {
                $product = $products->get((int) $item['product_id']);

                if ($product === null || ! $product->isActive) {
                    throw InvalidStockTransactionException::inactiveProduct($product?->name ?? '#'.$item['product_id']);
                }

                $quantity = (int) $item['quantity'];
                $balance = $this->products->increaseStock($product->id, $quantity);

                $stockIn->items()->create([
                    'product_id' => $product->id,
                    'product_sku' => $product->sku,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'quantity' => $quantity,
                    'unit_cost' => $item['unit_cost'] ?? 0,
                ]);

                StockCard::query()->create([
                    'product_id' => $product->id,
                    'movement_type' => MovementType::In,
                    'reference_code' => $stockIn->code,
                    'quantity' => $quantity,
                    'balance_after' => $balance,
                    'occurred_at' => $data['received_at'],
                    'notes' => 'Penerimaan dari '.$supplier->name,
                ]);
            }

            return $stockIn;
        });
    }
}
