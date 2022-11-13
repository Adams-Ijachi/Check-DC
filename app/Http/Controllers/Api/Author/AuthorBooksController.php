<?php

namespace App\Http\Controllers\Api\Author;

use App\Http\Controllers\Controller;

class AuthorBooksController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'AuthorBooksController@index']);
    }
}
