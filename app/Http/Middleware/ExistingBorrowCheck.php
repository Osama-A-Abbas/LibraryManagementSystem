<?php

namespace App\Http\Middleware;

use App\Models\Borrowing;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExistingBorrowCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = $request->auth()->user()->id;
        $book_id = $request->book_id;

        if (Borrowing::where('user_id', $user_id)->where('book_id', $book_id)){
            return response()->json(['message' => 'You have already borrowed this book!']);
        }
        return $next($request);
    }
}
