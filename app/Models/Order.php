<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'store_id',
        'membership_id',
        'cashier_user_id',
        'order_code',
        'order_date',
        'subtotal',
        'total_amount',
        'payment_method',
        'payment_status',
        'is_membership_transaction',
    ];

    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'integer',
        'total_amount' => 'integer',
        'is_membership_transaction' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function cashierUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
