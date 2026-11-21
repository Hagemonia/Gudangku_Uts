<?php

namespace Tests\Feature;

use App\Modules\AuthModule\Models\User;
use App\Modules\ProductModule\Models\Product;
use App\Modules\SupplierModule\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_in_increases_stock_and_writes_stock_card(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user)->post(route('stock-ins.store'), [
            'supplier_id' => $supplier->id,
            'received_at' => now()->toDateString(),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 25, 'unit_cost' => 10000],
            ],
        ])->assertRedirect();

        $this->assertSame(30, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_ins', ['supplier_id' => $supplier->id, 'supplier_name' => $supplier->name]);
        $this->assertDatabaseHas('stock_in_items', ['product_id' => $product->id, 'quantity' => 25]);
        $this->assertDatabaseHas('stock_cards', [
            'product_id' => $product->id,
            'movement_type' => 'in',
            'quantity' => 25,
            'balance_after' => 30,
        ]);
    }

    public function test_stock_in_validation_rejects_bad_input(): void
    {
        $user = User::factory()->create();
        $inactive = Product::factory()->inactive()->create();

        $this->actingAs($user)->post(route('stock-ins.store'), [
            'supplier_id' => 999,
            'received_at' => 'bukan-tanggal',
            'items' => [
                ['product_id' => $inactive->id, 'quantity' => 0],
            ],
        ])->assertSessionHasErrors(['supplier_id', 'received_at', 'items.0.product_id', 'items.0.quantity']);

        $this->assertDatabaseCount('stock_ins', 0);
    }

    public function test_stock_out_decreases_stock_and_writes_stock_card(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 20]);

        $this->actingAs($user)->post(route('stock-outs.store'), [
            'issued_at' => now()->toDateString(),
            'reason' => 'penjualan',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 8],
            ],
        ])->assertRedirect();

        $this->assertSame(12, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_cards', [
            'product_id' => $product->id,
            'movement_type' => 'out',
            'quantity' => 8,
            'balance_after' => 12,
        ]);
    }

    public function test_stock_out_fails_with_informative_error_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3, 'name' => 'Lampu LED']);

        $response = $this->actingAs($user)->post(route('stock-outs.store'), [
            'issued_at' => now()->toDateString(),
            'reason' => 'pemakaian',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Stok Lampu LED tidak cukup. Tersedia 3, diminta 10.');
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseCount('stock_outs', 0);
    }

    public function test_stock_out_rolls_back_every_item_when_one_item_fails(): void
    {
        $user = User::factory()->create();
        $enough = Product::factory()->create(['stock' => 10]);
        $short = Product::factory()->create(['stock' => 2]);

        $this->actingAs($user)->post(route('stock-outs.store'), [
            'issued_at' => now()->toDateString(),
            'reason' => 'penjualan',
            'items' => [
                ['product_id' => $enough->id, 'quantity' => 5],
                ['product_id' => $short->id, 'quantity' => 5],
            ],
        ])->assertSessionHas('error');

        $this->assertSame(10, $enough->fresh()->stock);
        $this->assertSame(2, $short->fresh()->stock);
        $this->assertDatabaseCount('stock_outs', 0);
        $this->assertDatabaseCount('stock_out_items', 0);
        $this->assertDatabaseCount('stock_cards', 0);
    }

    public function test_stock_card_filters_by_product(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $first = Product::factory()->create();
        $second = Product::factory()->create();

        $this->actingAs($user)->post(route('stock-ins.store'), [
            'supplier_id' => $supplier->id,
            'received_at' => now()->toDateString(),
            'items' => [
                ['product_id' => $first->id, 'quantity' => 4],
                ['product_id' => $second->id, 'quantity' => 6],
            ],
        ]);

        $this->actingAs($user)->get(route('stock-cards.index', ['product_id' => $first->id]))
            ->assertOk()
            ->assertViewHas('cards', fn ($cards) => $cards->total() === 1 && $cards->first()->product_id === $first->id);
    }
}
