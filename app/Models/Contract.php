<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;


    protected $fillable = [
        'vendor_id',
        'file_path',
        'status',
        'fraud_notes',
        'purpose',
        'admin_notes',
        'admin_status',
        'approved_by',
        'actioned_by',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
