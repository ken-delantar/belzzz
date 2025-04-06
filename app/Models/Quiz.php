<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['training_course_id', 'question', 'options', 'correct_answer'];

    protected $casts = [
        'options' => 'array',
    ];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }
}
