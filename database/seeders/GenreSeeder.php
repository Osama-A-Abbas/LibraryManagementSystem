<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Fiction', 'Non-Fiction', 'Science Fiction', 'Fantasy', 'Mystery',
            'Biography', 'Self-Help', 'Romance', 'Thriller', 'Historical',
            'Poetry', 'Graphic Novel', "Children's", 'Young Adult', 'Cookbook',
            'Travel', 'Health', 'Religion', 'Philosophy', 'Business',
            'Technology', 'True Crime', 'Memoir', 'Action', 'Adventure',
            'Comedy', 'Drama', 'Horror', 'Western',
        ];

        DB::table('genres')->insert(
            collect($genres)->map(fn($g) => ['genre_name' => $g])->toArray()
        );
    }
}
