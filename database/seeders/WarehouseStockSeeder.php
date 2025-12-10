<?php

namespace Database\Seeders;

use App\Models\WarehouseStock;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Database\Seeder;

class WarehouseStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        
        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                // Main warehouse gets higher stock
                if ($warehouse->warehouse_type === 'main') {
                    $currentStock = rand(100, 500);
                    $minimumStock = 50;
                    $maximumStock = 1000;
                } else {
                    // Branch warehouses get lower stock
                    $currentStock = rand(20, 100);
                    $minimumStock = 10;
                    $maximumStock = 200;
                }
                
                WarehouseStock::create([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'current_stock' => $currentStock,
                    'minimum_stock' => $minimumStock,
                    'maximum_stock' => $maximumStock,
                ]);
            }
        }
    }
}