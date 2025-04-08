<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('genre');
            $table->string('author')->nullable();
            $table->string('cover_page')->nullable(); // URL or path to the cover image
            $table->string('book_pdf')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable(); // Date when the book was published
            $table->integer('number_of_copies')->default(0); // Number of copies available in stock
            $table->boolean('is_available')->default(true); // Indicates if the book is available for borrowing

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
