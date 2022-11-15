<?php


namespace App\Traits;


use App\Models\Book;

trait Author
{

    public function isAuthor(Book $book): bool
    {
        return $book->authors()->where('user_id', \Auth::id())->exists();
    }


}
