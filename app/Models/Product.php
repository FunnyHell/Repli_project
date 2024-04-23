<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
        'deleted_at',
    ];

    protected $casts = [
      'category_id' => 'integer',
    ];
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function feature(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'product_features', 'product_id', 'feature_id');
    }
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'product_orders', 'product_id', 'order_id');
    }

    public function product_existence(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProductExistence::class);
    }

    public function supply_request(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SupplyRequest::class);
    }

    public function product_image(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
