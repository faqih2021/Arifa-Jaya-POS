<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'supplier_code' => 'SUP001',
                'name' => 'PT. Semen Indonesia Tbk',
                'address' => 'Jl. Veteran No. 274, Gresik, Jawa Timur 61122',
                'phone' => '031-3981733',
            ],
            [
                'supplier_code' => 'SUP002',
                'name' => 'PT. Wijaya Karya Beton Tbk',
                'address' => 'Jl. D.I. Panjaitan Kav. 9-10, Jakarta Timur 13340',
                'phone' => '021-8590-4081',
            ],
            [
                'supplier_code' => 'SUP003',
                'name' => 'PT. Steel Pipe Industry of Indonesia',
                'address' => 'Jl. Ahmad Yani No. 1, Surabaya, Jawa Timur 60234',
                'phone' => '031-8293480',
            ],
            [
                'supplier_code' => 'SUP004',
                'name' => 'CV. Kayu Jati Blitar',
                'address' => 'Jl. Kalpataru No. 15, Kepanjenkidul, Blitar, Jawa Timur 66117',
                'phone' => '0342-691234',
            ],
            [
                'supplier_code' => 'SUP005',
                'name' => 'UD. Besi Beton Makmur',
                'address' => 'Jl. Industri Raya No. 23, Kediri, Jawa Timur 64125',
                'phone' => '0354-771888',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
