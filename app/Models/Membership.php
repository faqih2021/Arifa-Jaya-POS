<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    protected $fillable = [
        'registered_by_user_id',
        'registered_at_store_id',
        'membership_code',
        'name',
        'address',
        'phone',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'date',
    ];

    public function registeredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public function registeredAtStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'registered_at_store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
