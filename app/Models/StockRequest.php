<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockRequest extends Model
{
    protected $fillable = [
        'requested_by_user_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'approved_by_user_id',
        'status',
        'request_date',
        'approved_date',
    ];

    protected $casts = [
        'request_date' => 'date',
        'approved_date' => 'datetime',
    ];

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockRequestDetail::class);
    }
}
