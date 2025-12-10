<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and stores for assignment
        $cashiers = User::where('roles', 'cashier')->get();
        $stores = Store::all();

        $memberships = [
            [
                'registered_by_user_id' => $cashiers->first()->id,
                'registered_at_store_id' => $stores->first()->id,
                'membership_code' => 'MBR001',
                'name' => 'Bapak Agus Santoso',
                'address' => 'Jl. Diponegoro No. 45, Sananwetan, Blitar',
                'phone' => '081234567890',
                'joined_at' => Carbon::now()->subDays(30),
            ],
            [
                'registered_by_user_id' => $cashiers->first()->id,
                'registered_at_store_id' => $stores->first()->id,
                'membership_code' => 'MBR002',
                'name' => 'Ibu Fitri Handayani',
                'address' => 'Jl. Supriyadi No. 12, Kepanjenkidul, Blitar',
                'phone' => '081234567891',
                'joined_at' => Carbon::now()->subDays(25),
            ],
            [
                'registered_by_user_id' => $cashiers->skip(1)->first() ? $cashiers->skip(1)->first()->id : $cashiers->first()->id,
                'registered_at_store_id' => $stores->skip(1)->first() ? $stores->skip(1)->first()->id : $stores->first()->id,
                'membership_code' => 'MBR003',
                'name' => 'Bapak Bambang Sutrisno',
                'address' => 'Jl. Ir. Soekarno No. 78, Kanigoro, Blitar',
                'phone' => '081234567892',
                'joined_at' => Carbon::now()->subDays(20),
            ],
            [
                'registered_by_user_id' => $cashiers->first()->id,
                'registered_at_store_id' => $stores->first()->id,
                'membership_code' => 'MBR004',
                'name' => 'Bapak Joko Priyanto',
                'address' => 'Jl. Brawijaya No. 33, Sukorejo, Blitar',
                'phone' => '081234567893',
                'joined_at' => Carbon::now()->subDays(15),
            ],
            [
                'registered_by_user_id' => $cashiers->first()->id,
                'registered_at_store_id' => $stores->first()->id,
                'membership_code' => 'MBR005',
                'name' => 'Ibu Sri Wahyuni',
                'address' => 'Jl. Patimura No. 67, Pakunden, Blitar',
                'phone' => '081234567894',
                'joined_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($memberships as $membership) {
            Membership::create($membership);
        }
    }
}
