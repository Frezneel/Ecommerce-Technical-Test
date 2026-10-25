<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCard extends Model
{
    protected $table = 'discount_card';
    protected $fillable = [
        'code',
        'expired_on',
        'discount_percentage',
        'is_used',
        'max_price_dicount',
        'min_price_product',
        'min_quantity_product',
        'type_id',
        'valid_on',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function type()
    {
        return $this->belongsTo(DiscountType::class, 'type_id');
    }
}
