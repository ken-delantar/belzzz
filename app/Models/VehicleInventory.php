<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class VehicleInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'driver_name',
        'route_from',
        'route_to',
        'total_capacity',
        'available_capacity',
        'status',
        'last_updated',
        'image'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : asset('assets/images/default-bus.png');
    }
}
