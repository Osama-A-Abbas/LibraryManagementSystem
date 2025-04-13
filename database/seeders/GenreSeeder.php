<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Fiction',
            'Non-Fiction',
            'Science Fiction',
            'Fantasy',
            'Mystery',
            'Biography',
            'Self-Help',
            'Romance',
            'Thriller',
            'Historical',
            'Poetry',
            'Graphic Novel',
            'Children\'s',
            'Young Adult',
            'Cookbook',
            'Travel',
            'Health',
            'Religion',
            'Philosophy',
            'Business',
            'Technology',
            'True Crime',
            'Memoir',
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Horror',
            'Western'
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => Str::slug($genre)
            ]);
        }
    }
}
