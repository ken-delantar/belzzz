<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['vendor_id', 'name', 'type', 'price', 'stock', 'description'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
