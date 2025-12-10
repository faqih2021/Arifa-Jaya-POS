<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'store_code' => 'STR001',
                'name' => 'Toko Bangunan Arifa Jaya',
                'address' => 'Jl. Merdeka No. 45, Blitar, Jawa Timur 66111',
                'phone' => '0342-801234',
                'is_main_store' => true,
            ],
            [
                'store_code' => 'STR002',
                'name' => 'Toko Bangunan Trisna Jaya',
                'address' => 'Jl. Veteran No. 12, Kanigoro, Blitar, Jawa Timur 66171',
                'phone' => '0342-802345',
                'is_main_store' => false,
            ],
            [
                'store_code' => 'STR003',
                'name' => 'Toko Bangunan Setia Abadi',
                'address' => 'Jl. Ahmad Yani No. 89, Sananwetan, Blitar, Jawa Timur 66137',
                'phone' => '0342-803456',
                'is_main_store' => false,
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}