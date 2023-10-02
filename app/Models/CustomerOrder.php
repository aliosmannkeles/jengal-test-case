<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(CustomerOrderItem::class);
    }
}
