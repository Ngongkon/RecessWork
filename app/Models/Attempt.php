<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attempts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'score',
        'school_registration_number',
        'challenge_id',
    ];

    /**
     * Get the participant that made the attempt.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'username', 'username');
    }

    /**
     * Get the school for the attempt.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_registration_number', 'school_registration_number');
    }

    /**
     * Get the challenge that the attempt belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
