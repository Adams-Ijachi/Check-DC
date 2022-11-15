<?php


namespace App\Services;


use App\Exceptions\BookNotAvailableException;
use App\Models\Book;
use App\Models\Status;
use Auth;

class BorrowService
{
    //borrowBook
    /**
     * @throws BookNotAvailableException
     */
    final function borrowBook(Book $book)
    {
        // check if book is available
        $this->checkBookIsAvailable($book);
        // check if user has active subscription for book plan
        $this->checkUserHasActiveSubscriptionForBookPlan($book);
        // check if user access_level is allowed to borrow book
        $this->checkUserAccessLevelIsAllowedToBorrowBook($book);

        $this->createLendingRecord($book);


        $book->update([
            'status_id' => Status::where('name', Status::STATUS_BORROWED)->first()->id
        ]);

    }


    // returnBook

    /**
     * @throws \Exception
     */
    final function returnBook($lending)
    {



        $lending->update([
            'returned_at' => now(),
            'points' => $this->calculatePoints($lending)
        ]);



        $lending->book->update([
            'status_id' => Status::where('name', Status::STATUS_AVAILABLE)->first()->id
        ]);
    }

    /**
     * @throws BookNotAvailableException
     */
    private function checkBookIsAvailable(Book $book)
    {
        if(!$book->isAvailable()){
            throw new BookNotAvailableException('Book is not available');
        }
    }

    /**
     * @throws BookNotAvailableException
     */
    private function checkUserHasActiveSubscriptionForBookPlan(Book $book)
    {
        $bookPlanIds = $book->plans->pluck('id');

        $userPlanIds = Auth::user()->activeSubscriptions()->pluck('plan_id');

        if (!$bookPlanIds->intersect($userPlanIds)->count()) {
            throw new BookNotAvailableException('User does not have active subscription for book plan');
        }


    }

    /**
     * @throws BookNotAvailableException
     */
    private function checkUserAccessLevelIsAllowedToBorrowBook(Book $book)
    {

        $bookAccessLevelIds = $book->accessLevels->pluck('id');
        $userAccessLevelIds = Auth::user()->accessLevelId();
        if(!$userAccessLevelIds){
            throw new BookNotAvailableException('User does not have access level');
        }

        if (!$bookAccessLevelIds->intersect($userAccessLevelIds)->count()) {
            throw new BookNotAvailableException('User access level is not allowed to borrow book');
        }

    }

    /**
     * @param Book $book
     */
    private function createLendingRecord(Book $book): void
    {
        $data = [
            "book_id" => $book->id,
            "user_id" => Auth::id(),
            "borrowed_at" => now(),
            "due_at" => now()->addDays(config('app.borrow_days')),
        ];

        app(LendingService::class)->lendBook($data);
    }

    private function calculatePoints($lending): int
    {

        if(now()->lt($lending->due_at)){
            return 2;
        }

        if(now()->gt($lending->due_at)){
            return -1;
        }

    }

}
