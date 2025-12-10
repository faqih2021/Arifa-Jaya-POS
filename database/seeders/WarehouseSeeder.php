<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\Store;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get stores for assignment
        $stores = Store::all();

        foreach ($stores as $store) {
            if ($store->is_main_store) {
                // Main store gets main warehouse
                Warehouse::create([
                    'store_id' => $store->id,
                    'warehouse_code' => 'WH001',
                    'warehouse_type' => 'main',
                    'name' => 'Gudang Utama - ' . $store->name,
                    'address' => 'Jl. Merdeka No. 47 (Belakang Toko), Blitar, Jawa Timur 66111',
                ]);
            } else {
                // Branch stores get branch warehouses
                $branchNumber = str_pad($store->id, 3, '0', STR_PAD_LEFT);
                Warehouse::create([
                    'store_id' => $store->id,
                    'warehouse_code' => 'WH' . $branchNumber,
                    'warehouse_type' => 'branch',
                    'name' => 'Gudang - ' . $store->name,
                    'address' => $store->address . ' (Area Gudang)',
                ]);
            }
        }
    }
}
