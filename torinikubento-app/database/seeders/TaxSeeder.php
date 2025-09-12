<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            [
                'name' => 'PPN (VAT)',
                'code' => 'PPN',
                'type' => 'percentage',
                'rate' => 11.00,
                'description' => 'Pajak Pertambahan Nilai 11%',
                'is_active' => true,
                'is_inclusive' => false
            ],
            [
                'name' => 'Service Charge',
                'code' => 'SERVICE',
                'type' => 'percentage',
                'rate' => 10.00,
                'description' => 'Service charge 10%',
                'is_active' => true,
                'is_inclusive' => false
            ],
            [
                'name' => 'PB1 (Luxury Tax)',
                'code' => 'PB1',
                'type' => 'percentage',
                'rate' => 10.00,
                'description' => 'Pajak Barang Mewah for certain items',
                'is_active' => false,
                'is_inclusive' => false
            ]
        ];

        foreach ($taxes as $tax) {
            Tax::updateOrCreate(
                ['code' => $tax['code']], // Kondisi pencarian
                $tax // Data yang akan diupdate atau dibuat
            );
        }
    }
}
