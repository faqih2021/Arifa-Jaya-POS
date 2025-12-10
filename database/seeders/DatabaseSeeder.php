<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Jalankan seeder dengan urutan yang benar sesuai foreign key dependencies

        // 1. Seeder untuk tabel independen (tanpa foreign key)
        $this->call([
            StoreSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);

        // 2. Seeder untuk tabel yang bergantung pada stores
        $this->call([
            UserSeeder::class,
            WarehouseSeeder::class,
        ]);

        // 3. Seeder untuk tabel yang bergantung pada users dan stores
        $this->call([
            MembershipSeeder::class,
        ]);

        // 4. Seeder untuk tabel yang bergantung pada warehouses dan products
        $this->call([
            WarehouseStockSeeder::class,
        ]);

        // 5. Seeder untuk tabel yang bergantung pada users, warehouses
        $this->call([
            StockRequestSeeder::class,
        ]);

        // 6. Seeder untuk tabel orders dan detail-detailnya
        $this->call([
            OrderSeeder::class,
            OrderDetailSeeder::class,
        ]);

        // 7. Seeder untuk detail stock requests
        $this->call([
            StockRequestDetailSeeder::class,
        ]);
    }
}
