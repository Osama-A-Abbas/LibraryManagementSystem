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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->foreignId('users')->constrained()->index();
            $table->unsignedBigInteger('book_id')->foreignId('books')->constrained()->index();

            $table->enum('borrowing_status', ['pending', 'approved', 'rejected', 'returned'])->default('pending')->comment('pending, approved, rejected, returned');

            $table->timestamp('borrow_at')->nullable();
            $table->timestamp('return_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
