<?php

namespace App\Http\Controllers\Api\Admin\Books;

use App\Exceptions\UserNotAnAuthorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBookFormRequest;
use App\Http\Requests\Api\UpdateBookFormRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;

class ManageBooksController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::with('status')
            ->latest()->paginate());
    }

    public function show(Book $book)
    {
        return BookResource::make($book->load('status','plans','accessLevels','categories','tags','authors'));
    }

    public function store(CreateBookFormRequest $request)
    {
        try {
            $book = app(BookService::class)->createBook($request);

            return BookResource::make(
                $book->load('status','plans','accessLevels','categories','tags'));
        }
        catch (UserNotAnAuthorException $exception){
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    /**
     * @throws UserNotAnAuthorException
     */
    public function update(UpdateBookFormRequest $request, Book $book)
    {
        $book = app(BookService::class)->updateBook($book, $request);
        return BookResource::make($book->load('status','plans','accessLevels','categories','tags'));
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }
}
