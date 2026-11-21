<?php

namespace App\Modules\SupplierModule\Database\Seeders;

use App\Modules\SupplierModule\Models\PaymentTerm;
use App\Modules\SupplierModule\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierModuleSeeder extends Seeder
{
    public function run(): void
    {
        $terms = collect([
            'Tunai' => [0, 'Pembayaran saat barang diterima'],
            'Net 7' => [7, 'Pembayaran maksimal 7 hari setelah invoice'],
            'Net 14' => [14, 'Pembayaran maksimal 14 hari setelah invoice'],
            'Net 30' => [30, 'Pembayaran maksimal 30 hari setelah invoice'],
        ])->map(fn (array $term, string $name) => PaymentTerm::factory()->create([
            'name' => $name,
            'days' => $term[0],
            'description' => $term[1],
        ]));

        $suppliers = [
            ['SUP-0001', 'PT Sinar Abadi Jaya', 'Net 30', '0274551001', 'Jl. Magelang Km 5, Sleman, DI Yogyakarta'],
            ['SUP-0002', 'CV Mitra Sejahtera', 'Net 14', '0274551002', 'Jl. Kaliurang Km 8, Sleman, DI Yogyakarta'],
            ['SUP-0003', 'PT Nusantara Supplies', 'Net 7', '0274551003', 'Jl. Solo Km 10, Klaten, Jawa Tengah'],
            ['SUP-0004', 'UD Berkah Makmur', 'Tunai', '0274551004', 'Jl. Wates Km 6, Bantul, DI Yogyakarta'],
        ];

        foreach ($suppliers as [$code, $name, $term, $phone, $address]) {
            Supplier::factory()->create([
                'code' => $code,
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'email' => strtolower(str_replace(' ', '', $name)).'@example.test',
                'payment_term_id' => $terms[$term]->id,
            ]);
        }
    }
}
