<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBooksComment;
use App\Models\UserBooksFavourite;
use App\Models\UserBooksLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBookController extends Controller
{
    public function index() {
        $books = Book::with(['user_books_likes','user_books_comments','user_books_favourites'])->paginate(10);
        $favourites = UserBooksFavourite::where(['user_id' => \Auth::id(), 'favourite' => true])->pluck('book_id');
        $likes = UserBooksLike::where(['user_id' => \Auth::id(), 'like' => true])->pluck('book_id');

        return response()->json([
            'books' => $books,
            'favourites' => $favourites,
            'likes' => $likes
        ]);
    }

    public function manageFavouritesBooks(Request $request) {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer',
            'favourite' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => "Validation Failed"
            ]);
        }

        $book = Book::find($request['book_id']);
        if (!$book) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => "Book Not Found"
            ], 404);
        }

        $favourite = UserBooksFavourite::updateOrInsert([
            'book_id' => $request['book_id'],
            'user_id' => \Auth::id()
        ], ['favourite' => $request['favourite']]);

        if ($favourite) {
            return response()->json([
                'status' => true,
                'data' => UserBooksFavourite::where(['user_id' => \Auth::id(), 'favourite' => true])->pluck('book_id'),
                'message' => "Favourites Books Updated"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => "Failed to Update Favourites Books"
        ]);
    }

    public function manageLikesBooks(Request $request) {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer',
            'like' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => "Validation Failed"
            ]);
        }

        $book = Book::find($request['book_id']);
        if (!$book) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => "Book Not Found"
            ], 404);
        }

        $like = UserBooksLike::updateOrInsert([
            'book_id' => $request['book_id'],
            'user_id' => \Auth::id()
        ], ['like' => $request['like']]);

        if ($like) {
            return response()->json([
                'status' => true,
                'data' => UserBooksLike::where(['user_id' => \Auth::id(), 'like' => true])->pluck('book_id'),
                'message' => "Liked Books Updated"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => "Failed to Update Liked Books"
        ]);
    }

    public function manageCommentsBooks(Request $request) {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => "Validation Failed"
            ]);
        }

        $book = Book::find($request['book_id']);
        if (!$book) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => "Book Not Found"
            ], 404);
        }

        $comment = UserBooksComment::updateOrInsert([
            'book_id' => $request['book_id'],
            'user_id' => \Auth::id()
        ], ['comment' => $request['comment']]);

        if ($comment) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => "Comment Updated"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => "Failed to Update Comment"
        ]);
    }
}
