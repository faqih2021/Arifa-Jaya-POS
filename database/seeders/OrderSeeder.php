<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get necessary data
        $stores = Store::all();
        $cashiers = User::where('roles', 'cashier')->get();
        $memberships = Membership::all();

        $orders = [
            // Order reguler (non-member) - Cash
            [
                'store_id' => $stores->first()->id,
                'membership_id' => null,
                'cashier_user_id' => $cashiers->first()->id,
                'order_code' => 'ORD001',
                'order_date' => Carbon::now()->subDays(5),
                'subtotal' => 25000,
                'total_amount' => 25000,
                'payment_method' => 'cash',
                'is_membership_transaction' => false,
            ],
            // Order member - QRIS
            [
                'store_id' => $stores->first()->id,
                'membership_id' => $memberships->first()->id,
                'cashier_user_id' => $cashiers->first()->id,
                'order_code' => 'ORD002',
                'order_date' => Carbon::now()->subDays(4),
                'subtotal' => 42000,
                'total_amount' => 39900, // 5% discount applied
                'payment_method' => 'qris',
                'is_membership_transaction' => true,
            ],
            // Order reguler - Transfer
            [
                'store_id' => $stores->first()->id,
                'membership_id' => null,
                'cashier_user_id' => $cashiers->first()->id,
                'order_code' => 'ORD003',
                'order_date' => Carbon::now()->subDays(3),
                'subtotal' => 18500,
                'total_amount' => 18500,
                'payment_method' => 'transfer',
                'is_membership_transaction' => false,
            ],
            // Order member di cabang - Cash
            [
                'store_id' => $stores->skip(1)->first() ? $stores->skip(1)->first()->id : $stores->first()->id,
                'membership_id' => $memberships->skip(1)->first() ? $memberships->skip(1)->first()->id : $memberships->first()->id,
                'cashier_user_id' => $cashiers->skip(1)->first() ? $cashiers->skip(1)->first()->id : $cashiers->first()->id,
                'order_code' => 'ORD004',
                'order_date' => Carbon::now()->subDays(2),
                'subtotal' => 75000,
                'total_amount' => 71250, // 5% discount applied
                'payment_method' => 'cash',
                'is_membership_transaction' => true,
            ],
            // Order hari ini - QRIS
            [
                'store_id' => $stores->first()->id,
                'membership_id' => null,
                'cashier_user_id' => $cashiers->first()->id,
                'order_code' => 'ORD005',
                'order_date' => Carbon::now(),
                'subtotal' => 35000,
                'total_amount' => 35000,
                'payment_method' => 'qris',
                'is_membership_transaction' => false,
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
