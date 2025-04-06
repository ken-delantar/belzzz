<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    /** @use HasFactory<\Database\Factories\ComplianceFactory> */
    use HasFactory;
    protected $table = 'vendor_compliance';
    protected $fillable = [
        'vendor_id',
        'requirement',
        'status',

    ];

    // public function documents()
    // {
    //     return $this->hasMany(ComplianceDocument::class);
    // }



    public function documents()
    {
        return $this->hasMany(ComplianceDocument::class, 'vendor_compliance_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
