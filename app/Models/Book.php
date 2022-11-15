<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'prologue',
        'edition',
        'description',
        'status_id',
    ];

    protected $casts = [
        'title' => 'string',
        'prologue' => 'string',
        'edition' => 'string',
        'description' => 'string',
        'status_id' => 'integer',
    ];


    public function isAvailable(): bool
    {
        return $this->status_id === Status::where('name', Status::STATUS_AVAILABLE)->first()->id;
    }


    public function plans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'book_plans');
    }

    public function accessLevels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AccessLevel::class, 'book_access_levels');
    }

    public function authors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_authors');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tags');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class);
    }



}
