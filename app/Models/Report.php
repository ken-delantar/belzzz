<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'follow_up',
        'report_by',
        'vendor_id', // Add this
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'follow_up' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'report_by')->withDefault([
            'name' => 'Anonymous'
        ]);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function formattedDate()
    {
        return $this->created_at->format('F d, Y');
    }
}
