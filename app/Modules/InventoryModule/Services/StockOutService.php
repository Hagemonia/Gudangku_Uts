<?php

namespace App\Modules\InventoryModule\Services;

use App\Modules\InventoryModule\Enums\MovementType;
use App\Modules\InventoryModule\Enums\StockOutReason;
use App\Modules\InventoryModule\Exceptions\InvalidStockTransactionException;
use App\Modules\InventoryModule\Models\StockCard;
use App\Modules\InventoryModule\Models\StockOut;
use App\Modules\InventoryModule\Support\DocumentCode;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use Illuminate\Support\Facades\DB;

class StockOutService
{
    public function __construct(private readonly ProductCatalogContract $products)
    {
    }

    public function record(array $data, int $userId, string $userName): StockOut
    {
        return DB::transaction(function () use ($data, $userId, $userName) {
            $products = $this->products->findMany(collect($data['items'])->pluck('product_id')->all());
            $reason = StockOutReason::from($data['reason']);

            $stockOut = StockOut::query()->create([
                'code' => DocumentCode::make('SO'),
                'reason' => $reason,
                'issued_at' => $data['issued_at'],
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
                $balance = $this->products->decreaseStock($product->id, $quantity);

                $stockOut->items()->create([
                    'product_id' => $product->id,
                    'product_sku' => $product->sku,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'quantity' => $quantity,
                ]);

                StockCard::query()->create([
                    'product_id' => $product->id,
                    'movement_type' => MovementType::Out,
                    'reference_code' => $stockOut->code,
                    'quantity' => $quantity,
                    'balance_after' => $balance,
                    'occurred_at' => $data['issued_at'],
                    'notes' => $reason->label(),
                ]);
            }

            return $stockOut;
        });
    }
}
