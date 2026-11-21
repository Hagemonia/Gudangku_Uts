<?php

namespace App\Modules\ProductModule\Database\Seeders;

use App\Modules\ProductModule\Models\Category;
use App\Modules\ProductModule\Models\Product;
use App\Modules\ProductModule\Models\Unit;
use Illuminate\Database\Seeder;

class ProductModuleSeeder extends Seeder
{
    public function run(): void
    {
        $units = collect([
            'Pcs' => 'pcs',
            'Box' => 'box',
            'Karton' => 'ktn',
            'Rim' => 'rim',
            'Kg' => 'kg',
            'Liter' => 'ltr',
        ])->map(fn (string $symbol, string $name) => Unit::factory()->create([
            'name' => $name,
            'symbol' => $symbol,
        ]));

        $categories = collect([
            'Elektronik' => 'Perangkat dan aksesoris elektronik',
            'Alat Tulis Kantor' => 'Perlengkapan tulis dan administrasi',
            'Makanan & Minuman' => 'Konsumsi pantry dan kantin',
            'Kebersihan' => 'Bahan dan alat kebersihan',
            'Peralatan Rumah Tangga' => 'Perlengkapan rumah tangga umum',
        ])->map(fn (string $description, string $name) => Category::factory()->create([
            'name' => $name,
            'description' => $description,
        ]));

        $items = [
            ['BRG-0001', 'Kertas HVS A4 80gsm', 'Rim', ['Alat Tulis Kantor'], 20],
            ['BRG-0002', 'Pulpen Gel Hitam 0.5mm', 'Box', ['Alat Tulis Kantor'], 10],
            ['BRG-0003', 'Spidol Whiteboard', 'Box', ['Alat Tulis Kantor'], 8],
            ['BRG-0004', 'Mouse Wireless', 'Pcs', ['Elektronik'], 10],
            ['BRG-0005', 'Keyboard USB', 'Pcs', ['Elektronik'], 8],
            ['BRG-0006', 'Kabel HDMI 2 Meter', 'Pcs', ['Elektronik'], 15],
            ['BRG-0007', 'Lampu LED 12 Watt', 'Box', ['Elektronik', 'Peralatan Rumah Tangga'], 12],
            ['BRG-0008', 'Air Mineral 600 ml', 'Karton', ['Makanan & Minuman'], 30],
            ['BRG-0009', 'Kopi Bubuk', 'Kg', ['Makanan & Minuman'], 10],
            ['BRG-0010', 'Gula Pasir', 'Kg', ['Makanan & Minuman'], 25],
            ['BRG-0011', 'Cairan Pembersih Lantai', 'Liter', ['Kebersihan', 'Peralatan Rumah Tangga'], 12],
            ['BRG-0012', 'Tisu Gulung', 'Karton', ['Kebersihan'], 15],
            ['BRG-0013', 'Sapu Ijuk', 'Pcs', ['Peralatan Rumah Tangga', 'Kebersihan'], 5],
            ['BRG-0014', 'Setrika Listrik', 'Pcs', ['Elektronik', 'Peralatan Rumah Tangga'], 6],
        ];

        foreach ($items as [$sku, $name, $unit, $categoryNames, $minStock]) {
            $product = Product::factory()->create([
                'sku' => $sku,
                'name' => $name,
                'unit_id' => $units[$unit]->id,
                'min_stock' => $minStock,
            ]);

            $product->categories()->attach(
                collect($categoryNames)->map(fn (string $category) => $categories[$category]->id)->all()
            );
        }
    }
}
