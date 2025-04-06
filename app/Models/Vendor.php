<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstname',
        'middlename',
        'lastname',
        'address',
        'contact_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
