<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidTrainingData extends Model
{
    protected $fillable = ['pricing', 'delivery_days', 'valid_days', 'score', 'is_fraud'];
}
