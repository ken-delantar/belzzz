<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = ['vendor_id', 'order_id', 'po_number', 'description', 'amount', 'status'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
