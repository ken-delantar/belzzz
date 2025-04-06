<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTrainingProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'training_course_id', 'completed', 'completion_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class);
    }
}
