<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Semen & Bahan Perekat
            [
                'product_code' => 'PRD001',
                'name' => 'Semen Portland Type I - 50kg',
                'description' => 'Semen Portland Type I kualitas tinggi untuk konstruksi umum',
                'unit' => 'zak',
                'actual_price' => 62000,
                'selling_price' => 68000,
                'membership_price' => 65000,
            ],
            [
                'product_code' => 'PRD002',
                'name' => 'Pasir Cor Halus per Kubik',
                'description' => 'Pasir cor halus berkualitas untuk pengecoran beton',
                'unit' => 'm3',
                'actual_price' => 280000,
                'selling_price' => 320000,
                'membership_price' => 300000,
            ],
            [
                'product_code' => 'PRD003',
                'name' => 'Kerikil Split 2-3 cm per Kubik',
                'description' => 'Kerikil split ukuran 2-3 cm untuk campuran beton',
                'unit' => 'm3',
                'actual_price' => 350000,
                'selling_price' => 400000,
                'membership_price' => 375000,
            ],
            [
                'product_code' => 'PRD004',
                'name' => 'Bata Merah 24x11x5 cm',
                'description' => 'Bata merah standar untuk dinding',
                'unit' => 'biji',
                'actual_price' => 800,
                'selling_price' => 1000,
                'membership_price' => 950,
            ],
            [
                'product_code' => 'PRD005',
                'name' => 'Batako Press 20x20x40 cm',
                'description' => 'Batako press untuk konstruksi dinding',
                'unit' => 'biji',
                'actual_price' => 4500,
                'selling_price' => 5500,
                'membership_price' => 5000,
            ],
            
            // Besi & Baja
            [
                'product_code' => 'PRD006',
                'name' => 'Besi Beton Ulir 10mm - 12m',
                'description' => 'Besi beton ulir diameter 10mm panjang 12m SNI',
                'unit' => 'btg',
                'actual_price' => 65000,
                'selling_price' => 75000,
                'membership_price' => 70000,
            ],
            [
                'product_code' => 'PRD007',
                'name' => 'Besi Beton Polos 8mm - 12m',
                'description' => 'Besi beton polos diameter 8mm panjang 12m SNI',
                'unit' => 'btg',
                'actual_price' => 35000,
                'selling_price' => 42000,
                'membership_price' => 39000,
            ],
            [
                'product_code' => 'PRD008',
                'name' => 'Kawat Bendrat 0.8mm',
                'description' => 'Kawat bendrat untuk mengikat besi beton diameter 0.8mm',
                'unit' => 'kg',
                'actual_price' => 18000,
                'selling_price' => 22000,
                'membership_price' => 20000,
            ],

            // Kayu & Triplek
            [
                'product_code' => 'PRD009',
                'name' => 'Kayu Balok 5x7 cm - 4m',
                'description' => 'Kayu balok kelas III ukuran 5x7 cm panjang 4m',
                'unit' => 'btg',
                'actual_price' => 25000,
                'selling_price' => 32000,
                'membership_price' => 29000,
            ],
            [
                'product_code' => 'PRD010',
                'name' => 'Triplek 9mm 122x244 cm',
                'description' => 'Triplek tebal 9mm ukuran 122x244 cm grade B',
                'unit' => 'lbr',
                'actual_price' => 85000,
                'selling_price' => 105000,
                'membership_price' => 95000,
            ],

            // Cat & Finishing
            [
                'product_code' => 'PRD011',
                'name' => 'Cat Tembok Dulux 2.5L Putih',
                'description' => 'Cat tembok Dulux WeatherShield 2.5L warna putih',
                'unit' => 'klg',
                'actual_price' => 180000,
                'selling_price' => 220000,
                'membership_price' => 200000,
            ],
            [
                'product_code' => 'PRD012',
                'name' => 'Paku 2 inch per Kg',
                'description' => 'Paku besi ukuran 2 inch untuk konstruksi kayu',
                'unit' => 'kg',
                'actual_price' => 16000,
                'selling_price' => 20000,
                'membership_price' => 18000,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
