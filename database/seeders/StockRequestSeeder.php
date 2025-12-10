<?php

namespace Database\Seeders;

use App\Models\StockRequest;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get necessary data
        $storageUsers = User::where('roles', 'storage')->get();
        $warehouses = Warehouse::all();
        $mainWarehouse = $warehouses->where('warehouse_type', 'main')->first();
        $branchWarehouses = $warehouses->where('warehouse_type', 'branch');
        
        $stockRequests = [
            // Request yang sudah completed
            [
                'requested_by_user_id' => $storageUsers->first()->id,
                'from_warehouse_id' => $mainWarehouse->id,
                'to_warehouse_id' => $branchWarehouses->first()->id,
                'approved_by_user_id' => $storageUsers->first()->id,
                'status' => 'completed',
                'request_date' => Carbon::now()->subDays(10),
                'approved_date' => Carbon::now()->subDays(9),
            ],
            // Request yang sudah approved
            [
                'requested_by_user_id' => $storageUsers->skip(1)->first() ? $storageUsers->skip(1)->first()->id : $storageUsers->first()->id,
                'from_warehouse_id' => $mainWarehouse->id,
                'to_warehouse_id' => $branchWarehouses->skip(1)->first() ? $branchWarehouses->skip(1)->first()->id : $branchWarehouses->first()->id,
                'approved_by_user_id' => $storageUsers->first()->id,
                'status' => 'approved',
                'request_date' => Carbon::now()->subDays(5),
                'approved_date' => Carbon::now()->subDays(4),
            ],
            // Request yang masih pending
            [
                'requested_by_user_id' => $storageUsers->first()->id,
                'from_warehouse_id' => $mainWarehouse->id,
                'to_warehouse_id' => $branchWarehouses->first()->id,
                'approved_by_user_id' => null,
                'status' => 'pending',
                'request_date' => Carbon::now()->subDays(2),
                'approved_date' => null,
            ],
            // Request yang di-reject
            [
                'requested_by_user_id' => $storageUsers->skip(1)->first() ? $storageUsers->skip(1)->first()->id : $storageUsers->first()->id,
                'from_warehouse_id' => $mainWarehouse->id,
                'to_warehouse_id' => $branchWarehouses->first()->id,
                'approved_by_user_id' => $storageUsers->first()->id,
                'status' => 'rejected',
                'request_date' => Carbon::now()->subDays(7),
                'approved_date' => Carbon::now()->subDays(6),
            ],
        ];

        foreach ($stockRequests as $request) {
            StockRequest::create($request);
        }
    }
}