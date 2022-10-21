<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

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

    public function popular_books() {
        return response()->json([
            'status' => true,
            'data' => Book::withCount('user_books_likes')
                        ->orderBy('user_books_likes_count', 'DESC')->paginate(10),
            'message' => 'Popular Books'
        ]);
        
    }

    public function show($id) {
        $book = Book::find($id);

        if ($book) {
            return response()->json([
                'status' => true,
                'data' => $book,
                'message' => 'Book Found'
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Book not Found'
        ], 404);
    }

    public function save(Request $request) {
        $validator = Validator::make($request->all(),[
            'isbn' => 'required|string',
            'title' => 'required|string',
            'publisher' => 'required|string',
            'number_of_pages' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to validate Book'
            ], 422);
        }

        $data = $request->all();
        $data['book_status_id'] = 1;

        $book = Book::create($data);
        if ($book) {
            return response()->json([
                'status' => true,
                'data' => $book,
                'message' => 'Book Added Successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Failed to create Book. Please contact administrator if problem persists.'
        ], 500);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer',
            'isbn' => 'required|string',
            'title' => 'required|string',
            'publisher' => 'required|string',
            'number_of_pages' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to validate Book update data'
            ], 422);
        }

        $data = $request->all();

        $book_record = Book::find($data['id']);

        if (!$book_record) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Book not found'
            ], 404);
        }

        $book = $book_record->update($data);
        if ($book) {
            return response()->json([
                'status' => true,
                'data' => $book_record,
                'message' => 'Book Updated Successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Failed to update Book. Please contact administrator if problem persists.'
        ], 500);
    }

    public function remove(Request $request) {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to validate data'
            ], 422);
        }

        $data = $request->all();

        $book_record = Book::find($data['id']);

        if (!$book_record) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Book not found'
            ], 404);
        }

        $book = $book_record->delete();
        if ($book) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Book Deleted Successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Failed to delete Book. Please contact administrator if problem persists.'
        ], 500);
    }
}
