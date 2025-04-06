<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'created_by',
        'description',
        'maintenance_date',
        'maintenance_type',
        'cost',
        'status',
        'is_priority',
        'assigned_tech',
        'notes',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'maintenance_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(VehicleInventory::class, 'vehicle_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_tech');
    }
}
