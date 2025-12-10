<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get stores for assignment
        $mainStore = Store::where('is_main_store', true)->first();
        $branchStore = Store::where('is_main_store', false)->first();

        $users = [
            [
                'store_id' => null, // Superadmin tidak terikat ke store tertentu
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'username' => 'superadmin',
                'password' => Hash::make('superadmin123'),
                'roles' => 'superadmin',
            ],
            [
                'store_id' => $mainStore->id,
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'username' => 'budi.cashier',
                'password' => Hash::make('cashier123'),
                'roles' => 'cashier',
            ],
            [
                'store_id' => $mainStore->id,
                'first_name' => 'Siti',
                'last_name' => 'Rahayu',
                'username' => 'siti.storage',
                'password' => Hash::make('storage123'),
                'roles' => 'storage',
            ],
            [
                'store_id' => $branchStore->id,
                'first_name' => 'Ahmad',
                'last_name' => 'Fauzi',
                'username' => 'ahmad.cashier',
                'password' => Hash::make('cashier123'),
                'roles' => 'cashier',
            ],
            [
                'store_id' => $branchStore->id,
                'first_name' => 'Maya',
                'last_name' => 'Sari',
                'username' => 'maya.storage',
                'password' => Hash::make('storage123'),
                'roles' => 'storage',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
