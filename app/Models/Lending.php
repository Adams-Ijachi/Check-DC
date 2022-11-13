<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class Lending extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "book_id",
        "user_id",
        "borrowed_at",
        "due_at",
        "returned_at",
        "points"
    ];

    protected $dates = [
        "borrowed_at",
        "due_at",
        "returned_at"
    ];


    public function book(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
