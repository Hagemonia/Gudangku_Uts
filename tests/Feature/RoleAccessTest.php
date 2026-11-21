<?php

namespace Tests\Feature;

use App\Modules\AuthModule\Models\User;
use App\Modules\ProductModule\Models\Category;
use App\Modules\ProductModule\Models\Product;
use App\Modules\ProductModule\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_products_but_not_manage_them(): void
    {
        $staff = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($staff)->get(route('products.index'))->assertOk();
        $this->actingAs($staff)->get(route('products.create'))->assertForbidden();
        $this->actingAs($staff)->delete(route('products.destroy', $product))->assertForbidden();
        $this->actingAs($staff)->get(route('categories.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('payment-terms.index'))->assertForbidden();
    }

    public function test_staff_can_open_transaction_pages(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)->get(route('dashboard'))->assertOk();
        $this->actingAs($staff)->get(route('stock-ins.create'))->assertOk();
        $this->actingAs($staff)->get(route('stock-outs.create'))->assertOk();
        $this->actingAs($staff)->get(route('stock-cards.index'))->assertOk();
    }

    public function test_admin_can_create_product_with_categories(): void
    {
        $admin = User::factory()->admin()->create();
        $unit = Unit::factory()->create();
        $categories = Category::factory()->count(2)->create();

        $this->actingAs($admin)->post(route('products.store'), [
            'sku' => 'BRG-9001',
            'name' => 'Palet Plastik',
            'unit_id' => $unit->id,
            'min_stock' => 5,
            'is_active' => 1,
            'category_ids' => $categories->pluck('id')->all(),
        ])->assertRedirect(route('products.index'));

        $product = Product::query()->where('sku', 'BRG-9001')->firstOrFail();

        $this->assertSame(0, $product->stock);
        $this->assertCount(2, $product->categories);
    }

    public function test_product_form_request_rejects_invalid_input(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->create(['sku' => 'BRG-DUP']);

        $this->actingAs($admin)->post(route('products.store'), [
            'sku' => 'BRG-DUP',
            'name' => '',
            'unit_id' => 999,
            'min_stock' => -1,
            'is_active' => 1,
            'category_ids' => [],
        ])->assertSessionHasErrors(['sku', 'name', 'unit_id', 'min_stock', 'category_ids']);
    }

    public function test_product_with_stock_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['stock' => 4]);

        $this->actingAs($admin)->delete(route('products.destroy', $product))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($product);
    }
}
