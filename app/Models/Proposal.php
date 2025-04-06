<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'user_id',
        'proposal_title',
        'vendor_name',
        'email',
        'product_service_type',
        'pricing',
        'delivery_timeline',
        'valid_until',
        'ai_score',
        'is_fraud',
        'notes',
        'admin_status',
        'actioned_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
