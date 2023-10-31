<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'project',
        'url',
        'manager',
        'last_check',
        'up_at',
        'down_at',
        'status',
        'code',
        'message',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
