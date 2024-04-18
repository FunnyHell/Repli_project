<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function productExistence(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ProductExistence::class, 'location');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(User::class, 'location');
    }
}
