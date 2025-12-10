<?php

namespace Database\Seeders;

use App\Models\StockRequestDetail;
use App\Models\StockRequest;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StockRequestDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockRequests = StockRequest::all();
        $products = Product::all();

        // Request 1 - Completed request
        $request1 = $stockRequests->where('status', 'completed')->first();
        if ($request1) {
            StockRequestDetail::create([
                'stock_request_id' => $request1->id,
                'product_id' => $products->where('product_code', 'PRD001')->first()->id, // Indomie
                'requested_quantity' => 50,
                'approved_quantity' => 50,
            ]);
            StockRequestDetail::create([
                'stock_request_id' => $request1->id,
                'product_id' => $products->where('product_code', 'PRD002')->first()->id, // Teh Pucuk
                'requested_quantity' => 30,
                'approved_quantity' => 25, // Partial approval
            ]);
            StockRequestDetail::create([
                'stock_request_id' => $request1->id,
                'product_id' => $products->where('product_code', 'PRD006')->first()->id, // Rinso
                'requested_quantity' => 20,
                'approved_quantity' => 20,
            ]);
        }

        // Request 2 - Approved request
        $request2 = $stockRequests->where('status', 'approved')->first();
        if ($request2) {
            StockRequestDetail::create([
                'stock_request_id' => $request2->id,
                'product_id' => $products->where('product_code', 'PRD003')->first()->id, // Beras
                'requested_quantity' => 10,
                'approved_quantity' => 10,
            ]);
            StockRequestDetail::create([
                'stock_request_id' => $request2->id,
                'product_id' => $products->where('product_code', 'PRD004')->first()->id, // Minyak
                'requested_quantity' => 25,
                'approved_quantity' => 20, // Partial approval
            ]);
        }

        // Request 3 - Pending request
        $request3 = $stockRequests->where('status', 'pending')->first();
        if ($request3) {
            StockRequestDetail::create([
                'stock_request_id' => $request3->id,
                'product_id' => $products->where('product_code', 'PRD007')->first()->id, // Pepsodent
                'requested_quantity' => 40,
                'approved_quantity' => 0, // Not approved yet
            ]);
            StockRequestDetail::create([
                'stock_request_id' => $request3->id,
                'product_id' => $products->where('product_code', 'PRD008')->first()->id, // Shampo
                'requested_quantity' => 15,
                'approved_quantity' => 0, // Not approved yet
            ]);
        }

        // Request 4 - Rejected request
        $request4 = $stockRequests->where('status', 'rejected')->first();
        if ($request4) {
            StockRequestDetail::create([
                'stock_request_id' => $request4->id,
                'product_id' => $products->where('product_code', 'PRD009')->first()->id, // Chitato
                'requested_quantity' => 100,
                'approved_quantity' => 0, // Rejected
            ]);
        }
    }
}
