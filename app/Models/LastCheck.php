<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_check',
        'next_check'
    ];
}
