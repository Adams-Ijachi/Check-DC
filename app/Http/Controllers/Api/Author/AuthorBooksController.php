<?php

namespace App\Http\Controllers\Api\Author;

use App\Exceptions\UserNotAnAuthorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBookFormRequest;
use App\Http\Requests\AuthorCreateBookFormRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;

class AuthorBooksController extends Controller
{
    public function index()
    {
        return BookResource::collection(\Auth::user()->books()->latest()->paginate());
    }

    /**
     */
    public function show(Book $book)
    {
        try {
            app(BookService::class)->checkIfUserIsAuthor($book);

            return BookResource::make($book->load('status',
                'plans','accessLevels','categories','tags'));
        }catch (UserNotAnAuthorException $exception){
            return response()->json(['message' => $exception->getMessage()], 422);
        }

    }

    public function store(AuthorCreateBookFormRequest $request)
    {
        try {
            $book = app(BookService::class)->authorCreateBook($request);

            return BookResource::make(
                $book->load('status','plans','accessLevels','categories','tags'));
        }
        catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     */
    public function update(AuthorCreateBookFormRequest $request, Book $book)
    {
        try {
            app(BookService::class)->checkIfUserIsAuthor($book);

            $book = app(BookService::class)->authorUpdateBook($book, $request);
            return BookResource::make($book->load('status','plans','accessLevels','categories','tags'));
        }catch (UserNotAnAuthorException $exception){
            return response()->json(['message' => $exception->getMessage()], 422);
        } catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy(Book $book)
    {
        try {
            app(BookService::class)->checkIfUserIsAuthor($book);

            $book->delete();
            return response()->json(['message' => 'Book deleted successfully']);
        }catch (UserNotAnAuthorException $exception){
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }



}
