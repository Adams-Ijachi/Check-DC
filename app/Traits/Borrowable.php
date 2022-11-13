<?php


namespace App\Traits;


trait Borrowable
{
    public function borrowed(): bool
    {
        return $this->lendings()->whereNull('returned_at')->exists();
    }
}
