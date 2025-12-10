<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'store_id',
        'warehouse_code',
        'warehouse_type',
        'name',
        'address',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function outgoingStockRequests(): HasMany
    {
        return $this->hasMany(StockRequest::class, 'from_warehouse_id');
    }

    public function incomingStockRequests(): HasMany
    {
        return $this->hasMany(StockRequest::class, 'to_warehouse_id');
    }
}
