<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'min_age',
        'max_age',
        'borrowing_points'
    ];
    protected $casts = [
        'min_age' => 'integer',
        'max_age' => 'integer',
        'borrowing_points' => 'integer'
    ];
}
