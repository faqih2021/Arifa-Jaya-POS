<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'description',
        'unit',
        'actual_price',
        'selling_price',
        'membership_price',
    ];

    protected $casts = [
        'actual_price' => 'integer',
        'selling_price' => 'integer',
        'membership_price' => 'integer',
    ];

    public function warehouseStocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function stockRequestDetails(): HasMany
    {
        return $this->hasMany(StockRequestDetail::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
