<?php

namespace Database\Seeders;

use App\Models\AccessLevel;
use App\Models\Book;
use App\Models\Category;
use App\Models\Status;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {

        Book::factory(10)
            ->create();
    }
}
