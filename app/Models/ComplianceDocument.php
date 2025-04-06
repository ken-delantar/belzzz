<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_compliance_id',
        'file_name',
        'file_path',
        'document_path',
        'file_size',
        'file_type',
    ];


    /**
     * Get the vendor compliance record that owns the document.
     */
    public function vendorCompliance()
    {
        return $this->belongsTo(Compliance::class, 'vendor_compliance_id');
    }

    // public function compliance()
    // {
    //     return $this->belongsTo(Compliance::class, 'vendor_compliance_id');
    // }
}
