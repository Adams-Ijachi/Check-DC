<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\BookNotAvailableException;
use App\Http\Controllers\Controller;
use App\Http\Resources\LendingResource;
use App\Models\Book;
use App\Models\Lending;
use App\Services\BookService;
use App\Services\BorrowService;
use Auth;

class BorrowedBooksController extends Controller
{

    public function index()
    {
        return LendingResource::collection(\Auth::user()->borrowedBooks());
    }


    public function returnedBooks()
    {
        return LendingResource::collection(\Auth::user()->returnedBooks());
    }

    // store
    /**
     */
    public function store(Book $book)
    {

        try {
            app(BorrowService::class)->borrowBook($book);

            return response()->json(['message' => 'Book borrowed successfully']);
        }
        catch (BookNotAvailableException $exception){
            return response()->json(['message' => $exception->getMessage()], 400);
        }
        catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }

    }

    // return
    /**
     */
    public function update(Lending $lending)
    {
        try {


            if($lending->user_id != Auth::id()){
                return response()->json(['message' => 'You are not allowed to return this book'], 400);
            }
            app(BorrowService::class)->returnBook($lending);

            return response()->json(['message' => 'Book returned successfully']);
        }
        catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }



}
