<?php

namespace App\Models;

use App\Models\UserTrainingProgress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'category', 'file_path', 'duration'];

    public function progress()
    {
        return $this->hasMany(UserTrainingProgress::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
