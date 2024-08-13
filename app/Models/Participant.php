<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'date_of_birth',
        'school_registration_number',
        'image_file',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_registration_number', 'school_registration_number');
    }
}