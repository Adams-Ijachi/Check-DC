<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'status_id',
    ];

    protected $casts = [
        'duration' => 'integer'
    ];


    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::lower($value),
        );

    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
