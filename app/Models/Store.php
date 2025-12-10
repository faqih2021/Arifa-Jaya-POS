<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = [
        'store_code',
        'name',
        'address',
        'phone',
        'is_main_store',
    ];

    protected $casts = [
        'is_main_store' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'registered_at_store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
