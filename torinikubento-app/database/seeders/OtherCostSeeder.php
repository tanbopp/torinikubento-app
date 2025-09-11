<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OtherCost;

class OtherCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $otherCosts = [
            [
                'name' => 'Pajak PPN',
                'type' => 'percentage',
                'value' => 11.0,
                'description' => 'Pajak Pertambahan Nilai 11%',
                'is_active' => true
            ],
            [
                'name' => 'Biaya Layanan',
                'type' => 'percentage',
                'value' => 5.0,
                'description' => 'Biaya layanan restaurant 5%',
                'is_active' => true
            ],
            [
                'name' => 'Biaya Kemasan',
                'type' => 'fixed',
                'value' => 2000.0,
                'description' => 'Biaya kemasan untuk delivery',
                'is_active' => true
            ],
            [
                'name' => 'Biaya Operasional',
                'type' => 'percentage',
                'value' => 3.0,
                'description' => 'Biaya operasional harian',
                'is_active' => true
            ]
        ];

        foreach ($otherCosts as $cost) {
            OtherCost::create($cost);
        }
    }
}
