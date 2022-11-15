<?php


namespace App\Services;


use App\Models\Lending;

class LendingService
{
    //lendBook

    final public function lendBook(array $data): Lending
    {
        return Lending::create($data);


    }
}
