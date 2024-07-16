<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
    protected $fillable = [
        'challenge_name', 'start_date', 'end_date', 'duration', 'num_questions',
    ];

    public function challengeQuestions()
    {
        return $this->hasMany(Question::class);
    }

    // // If you want to use the computed number of questions instead of storing it
    // public function getNumQuestionsAttribute()
    // {
    //     return $this->questions()->count();
    // }
}
    

