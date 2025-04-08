<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    /**
     * Download the book PDF.
     *
     * @param \App\Models\Book $book
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function downloadPdf(Book $book)
    {
        try {
            if (!$book->book_pdf) {
                return response()->json(['error' => 'No PDF available for this book.'], 404);
            }

            $filePath = storage_path('app/public/' . $book->book_pdf);

            // Log the file path for debugging
            Log::info('Attempting to download PDF: ' . $filePath);
            Log::info('Book PDF field value: ' . $book->book_pdf);
            Log::info('File exists: ' . (file_exists($filePath) ? 'Yes' : 'No'));

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'PDF file not found at path: ' . $filePath], 404);
            }

            return response()->download($filePath, basename($book->book_pdf));
        } catch (\Exception $e) {
            Log::error('PDF download error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to download PDF: ' . $e->getMessage()], 500);
        }
    }


    /**
     * View the book PDF inline.
     *
     * @param \App\Models\Book $book
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function viewBookPdf(Book $book)
    {
        try {
            if (!$book->book_pdf) {
                return response()->json(['error' => 'No PDF available for this book.'], 404);
            }

            $filePath = storage_path('app/public/' . $book->book_pdf);

            // Log the file path for debugging
            Log::info('Attempting to view PDF: ' . $filePath);
            Log::info('Book PDF field value: ' . $book->book_pdf);
            Log::info('File exists: ' . (file_exists($filePath) ? 'Yes' : 'No'));

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'PDF file not found at path: ' . $filePath], 404);
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($book->book_pdf) . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('PDF view error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to view PDF: ' . $e->getMessage()], 500);
        }
    }
}
