<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        // Order 1: ORD001 - Belanja sederhana
        $order1 = $orders->where('order_code', 'ORD001')->first();
        if ($order1) {
            OrderDetail::create([
                'order_id' => $order1->id,
                'product_id' => $products->where('product_code', 'PRD001')->first()->id, // Indomie
                'order_quantity' => 5,
                'unit_price' => 3500,
                'total_price' => 17500,
            ]);
            OrderDetail::create([
                'order_id' => $order1->id,
                'product_id' => $products->where('product_code', 'PRD002')->first()->id, // Teh Pucuk
                'order_quantity' => 2,
                'unit_price' => 4500,
                'total_price' => 9000,
            ]);
        }

        // Order 2: ORD002 - Member transaction
        $order2 = $orders->where('order_code', 'ORD002')->first();
        if ($order2) {
            OrderDetail::create([
                'order_id' => $order2->id,
                'product_id' => $products->where('product_code', 'PRD003')->first()->id, // Beras
                'order_quantity' => 1,
                'unit_price' => 65000, // membership price
                'total_price' => 65000,
            ]);
            OrderDetail::create([
                'order_id' => $order2->id,
                'product_id' => $products->where('product_code', 'PRD004')->first()->id, // Minyak
                'order_quantity' => 2,
                'unit_price' => 17500, // membership price
                'total_price' => 35000,
            ]);
        }

        // Order 3: ORD003 - Belanja kecil
        $order3 = $orders->where('order_code', 'ORD003')->first();
        if ($order3) {
            OrderDetail::create([
                'order_id' => $order3->id,
                'product_id' => $products->where('product_code', 'PRD009')->first()->id, // Chitato
                'order_quantity' => 1,
                'unit_price' => 11000,
                'total_price' => 11000,
            ]);
            OrderDetail::create([
                'order_id' => $order3->id,
                'product_id' => $products->where('product_code', 'PRD007')->first()->id, // Pepsodent
                'order_quantity' => 1,
                'unit_price' => 8500,
                'total_price' => 8500,
            ]);
        }

        // Order 4: ORD004 - Belanja besar member
        $order4 = $orders->where('order_code', 'ORD004')->first();
        if ($order4) {
            OrderDetail::create([
                'order_id' => $order4->id,
                'product_id' => $products->where('product_code', 'PRD003')->first()->id, // Beras
                'order_quantity' => 1,
                'unit_price' => 65000, // membership price
                'total_price' => 65000,
            ]);
            OrderDetail::create([
                'order_id' => $order4->id,
                'product_id' => $products->where('product_code', 'PRD005')->first()->id, // Gula
                'order_quantity' => 2,
                'unit_price' => 14000, // membership price
                'total_price' => 28000,
            ]);
            OrderDetail::create([
                'order_id' => $order4->id,
                'product_id' => $products->where('product_code', 'PRD006')->first()->id, // Rinso
                'order_quantity' => 1,
                'unit_price' => 21000, // membership price
                'total_price' => 21000,
            ]);
        }

        // Order 5: ORD005 - Pending order
        $order5 = $orders->where('order_code', 'ORD005')->first();
        if ($order5) {
            OrderDetail::create([
                'order_id' => $order5->id,
                'product_id' => $products->where('product_code', 'PRD010')->first()->id, // Roma
                'order_quantity' => 2,
                'unit_price' => 16000,
                'total_price' => 32000,
            ]);
            OrderDetail::create([
                'order_id' => $order5->id,
                'product_id' => $products->where('product_code', 'PRD011')->first()->id, // Betadine
                'order_quantity' => 1,
                'unit_price' => 12000,
                'total_price' => 12000,
            ]);
        }
    }
}
