<?php

use App\Http\Controllers\Api\Admin\AccessLevel\ManageAccessLevelController;
use App\Http\Controllers\Api\Admin\Plan\ManagePlanController;
use App\Http\Controllers\Api\Admin\User\ManageUsersController;
use App\Http\Controllers\Api\User\BorrowedBooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;

use \App\Http\Controllers\Api\Admin\Books\ManageBooksController;
use \App\Http\Controllers\Api\Admin\Lendings\ManageLendingController;
use \App\Http\Controllers\Api\Author\AuthorBooksController;
use App\Http\Controllers\Api\User\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);


        Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {

            Route::apiResource('access-levels', ManageAccessLevelController::class);
            Route::apiResource('plans', ManagePlanController::class);
            Route::apiResource('users', ManageUsersController::class);
            Route::apiResource('books', ManageBooksController::class);
            Route::apiResource('lendings', ManageLendingController::class);

        });

        Route::group(['prefix' => 'author', 'middleware' => 'role:author'], function () {
            Route::apiResource('books', AuthorBooksController::class);

        });

        Route::group(['prefix' => 'user'], function () {
            Route::post('/plans/{plan}/subscribe', [SubscriptionController::class, 'subscribe']);
            Route::get('/subscriptions', [SubscriptionController::class, 'index']);

            Route::get('/books/borrowed', [BorrowedBooksController::class, 'index']);
            Route::get('/books/returned', [BorrowedBooksController::class, 'returnedBooks']);
            Route::post('/books/{book}/borrow', [BorrowedBooksController::class, 'store']);
            Route::post('/lending/{lending}/return', [BorrowedBooksController::class, 'update']);

        });
    });



});

