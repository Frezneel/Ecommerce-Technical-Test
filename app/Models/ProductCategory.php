<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $fillable = [
        'category_id',
        'product_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'is_deleted',
        'deleted_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }
}
