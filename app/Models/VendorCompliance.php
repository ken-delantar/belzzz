<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCompliance extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'requirement', 'document_path', 'expiry_date', 'status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
