<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
    'id','school_name','district','school_registration_number',
    'representative_name',
    'representative_email',
    'created_at', 'updated_at'];
}
