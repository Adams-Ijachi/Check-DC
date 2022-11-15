<?php


namespace App\Services;


use App\Exceptions\UserNotAnAuthorException;
use App\Http\Requests\Api\CreateBookFormRequest;
use App\Http\Requests\Api\UpdateBookFormRequest;
use App\Http\Requests\AuthorCreateBookFormRequest;
use App\Models\Book;
use App\Models\User;
use DB;
class BookService
{

    final function authorCreateBook(AuthorCreateBookFormRequest $request):Book
    {

        $validatedData = $request->validated();
        $bookValidatedData = $request->safe()->except(
            'access_level_ids', 'tag_ids', 'category_ids', 'plan_ids');
        $validatedData['author_ids'] = \Auth::id();

        return $this->createBook($bookValidatedData, $validatedData);

    }

    final function authorUpdateBook(Book $book, AuthorCreateBookFormRequest $request):Book
    {
        $validatedData = $request->validated();
        $bookValidatedData = $request->safe()->except(
            'access_level_ids', 'tag_ids', 'category_ids', 'plan_ids');
        $validatedData['author_ids'] = \Auth::id();

        return $this->updateBook($book, $bookValidatedData, $validatedData);

    }

    // create a new book
    /**
     * @throws UserNotAnAuthorException
     */
    final function adminCreateBook(CreateBookFormRequest $request): Book
    {


        $validatedData = $request->validated();
        $bookValidatedData = $request->safe()->except(
            'access_level_ids', 'tag_ids', 'category_ids', 'plan_ids', 'author_ids');

        $this->validateAuthorIdHaveAuthorRole($validatedData['author_ids']);

        return $this->createBook($bookValidatedData, $validatedData);
    }



    /**
     * @throws UserNotAnAuthorException
     */
    final function adminUpdateBook(Book $book, UpdateBookFormRequest $request): Book
    {
        $validatedData = $request->validated();

        $bookValidatedData = $request->safe()->except(
            'access_level_ids', 'tag_ids', 'category_ids', 'plan_ids', 'author_ids');

        $this->validateAuthorIdHaveAuthorRole($validatedData['author_ids']);

        return $this->updateBook($book, $bookValidatedData, $validatedData);

    }


    /**
     * @throws UserNotAnAuthorException
     */
    final function checkIfUserIsAuthor(Book $book): void
    {

        if (!\Auth::user()->isAuthor($book)) {
            throw new UserNotAnAuthorException("You are not an author of this book");
        }
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

    /**
     * @param array $bookValidatedData
     * @param mixed $validatedData
     * @return mixed
     */
    public function createBook(array $bookValidatedData, mixed $validatedData): mixed
    {
        return DB::transaction(function () use ($bookValidatedData, $validatedData) {

            $book = Book::create(
                $bookValidatedData);

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

    /**
     * @param Book $book
     * @param array $bookValidatedData
     * @param mixed $validatedData
     * @return mixed
     */
    public function updateBook(Book $book, array $bookValidatedData, mixed $validatedData): mixed
    {
        return DB::transaction(function () use ($book, $bookValidatedData, $validatedData) {

            $book->update($bookValidatedData);

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
