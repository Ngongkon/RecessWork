<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = [
        'question_text',
        'challenge_id',
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
