<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index() {
        $books = Book::paginate(5);
        return response()->json([
            'status' => true,
            'data' => $books,
            'message' => 'Book Data'
        ]);
    }
}
