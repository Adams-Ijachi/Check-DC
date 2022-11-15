<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Author;
use App\Traits\Borrowable;
use App\Traits\StatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, Borrowable, Author, StatusTrait;

    const ROLE_ADMIN = "admin";
    const ROLE_READER = "reader";
    const ROLE_AUTHOR = "author";


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'age',
        'address',
        'username',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
    ];




    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }


    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }

    // isAuthor



    public function scopeNotAdmin($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', '!=', self::ROLE_ADMIN);
        });
    }


    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }


    public function lendings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lending::class);
    }

    public function books(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_authors');
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'subscriptions');
    }

    // active subscriptions
    public function activeSubscriptions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'subscriptions')
            ->wherePivot('status_id',
                Status::where('name', Status::STATUS_ACTIVE)->first()->id);
    }

    // points
    public function lending_points(): int
    {
        return (int)$this->lendings()->sum('points');
    }

    // get user access level
    final function accessLevelId()
    {
        $point = $this->lending_points();
        return AccessLevel::where('min_age', '<=', $this->age)
            ->where(function ($query) {
                $query->where('max_age', '>=', $this->age)
                    ->orWhereNull('max_age');
            })->where('borrowing_points', '<=', $point)->pluck('id')->last();
    }

    // borrowed books
    final function borrowedBooks(): \LaravelIdea\Helper\App\Models\_IH_Lending_C|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|array
    {
        return $this->lendings()
            ->where('returned_at', null)
            ->with('book')
            ->latest()->paginate();
    }

    final function returnedBooks(): \LaravelIdea\Helper\App\Models\_IH_Lending_C|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Pagination\LengthAwarePaginator|array
    {
        return $this->lendings()
            ->where('returned_at', '!=', null)
            ->with('book')
            ->latest()->paginate();
    }





}
