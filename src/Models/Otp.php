<?php

namespace Dwikipeddos\PeddosLaravelTools\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
    ];
}
