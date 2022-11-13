<?php


namespace App\Services;


use App\Exceptions\UserNotAnAuthorException;
use App\Http\Requests\Api\CreateBookFormRequest;
use App\Http\Requests\Api\UpdateBookFormRequest;
use App\Models\Book;
use App\Models\User;
use DB;
class BookService
{

    // create a new book
    /**
     * @throws UserNotAnAuthorException
     */
    final function createBook(CreateBookFormRequest $request): Book
    {


        $validatedData = $request->validated();

        $this->validateAuthorIdHaveAuthorRole($validatedData['author_ids']);

        return DB::transaction(function () use ( $request, $validatedData) {

            $book = Book::create(
                $request->safe()->except('access_level_ids','tag_ids','category_ids', 'plan_ids','author_ids'));

            $book->accessLevels()->attach($validatedData['access_level_ids']);

            $book->plans()->attach($validatedData['plan_ids']);

            $book->authors()->attach($validatedData['author_ids']);


            if (array_key_exists('tag_ids', $validatedData)) {
                $book->tags()->attach($validatedData['tag_ids']);
            }

            if (array_key_exists('category_ids', $validatedData)) {

                $book->categories()->attach($validatedData['category_ids']);
            }


            return $book;

        });
    }

    // update a book

    /**
     * @throws UserNotAnAuthorException
     */
    final function updateBook(Book $book, UpdateBookFormRequest $request): Book
    {
        $validatedData = $request->validated();

        $this->validateAuthorIdHaveAuthorRole($validatedData['author_ids']);

        return DB::transaction(function () use ($book, $request, $validatedData) {

            $book->update($request->safe()->except('access_level_ids','tag_ids','category_ids', 'plan_ids','author_ids'));

            $book->accessLevels()->sync($validatedData['access_level_ids']);

            $book->plans()->sync($validatedData['plan_ids']);

            $book->authors()->sync($validatedData['author_ids']);

            if (array_key_exists('tag_ids', $validatedData)) {
                $book->tags()->sync($validatedData['tag_ids']);
            }

            if (array_key_exists('category_ids', $validatedData)) {
                $book->categories()->sync($validatedData['category_ids']);
            }

            return $book;
        });

    }

    /**
     * @throws UserNotAnAuthorException
     */
    private function validateAuthorIdHaveAuthorRole(array $authorIds): void
    {
        $authors = User::whereIn('id', $authorIds)->get();

        foreach ($authors as $author) {
            if (!$author->hasRole('author')) {
                throw new UserNotAnAuthorException("User with id {$author->id} is not an author");
            }
        }
    }

    private function createBookTags(Book $book, mixed $tags)
    {

        foreach ($tags as $tag) {

            $book->tags()->firstOrCreate(['name' => $tag]);
        }
    }

    private function createBookCategories(Book $book, mixed $categories)
    {
        foreach ($categories as $category) {
            $book->categories()->firstOrCreate(['name' => $category]);
        }
    }


    private function update(Book $book, mixed $tags)
    {
        $book->tags()->delete();
        $this->createBookTags($book, $tags);
    }

    // update book categories
    private function giveBookCategories(Book $book, mixed $categories)
    {
        $book->categories()->delete();
        $this->createBookCategories($book, $categories);
    }
}
