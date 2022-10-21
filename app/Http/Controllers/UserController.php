<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = User::paginate(10);

        return response()->json([
            'status' => true,
            'data' => $users,
            'message' => 'All Users'
        ]);
    }

    public function show($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user,
                'permissions' => $user->user_permissions()
            ],
            'message' => "User Found"
        ]);
    }

    public function getUserPermissions($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user->user_permissions()->paginate(10),
            'message' => "User Permissions"
        ]);
    }

    public function getUserBooksLikes($id) {
        $user = User::with(['user_books_likes:book'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user['user_books_likes'],
            'message' => "User Books Likes"
        ]);
    }

    public function getUserBooksFavourites($id) {
        $user = User::with(['user_books_favourites:book'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user['user_books_favourites'],
            'message' => "User Books Favourites"
        ]);
    }

    public function getUserBooksComments($id) {
        $user = User::with(['user_books_comments:book'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not Found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user['user_books_comments'],
            'message' => "User Books Comments"
        ]);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to validate data'
            ], 422);
        }

        $data = $validator->getData();
        $user = User::find($data['id']);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not found'
            ], 404);
        }

        $user_update = $user->update($data);
        if ($user_update) {
            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => "User Updated Successfully"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => "Failed to update user details. Please contact Administrator if problem persists."
        ]);
    }

    public function remove(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to validate data'
            ], 422);
        }

        $data = $validator->getData();

        //Check if user to delete is the currently logged in user.
        if ($data['id'] == \Auth::user()->id) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Sorry, You cannot remove yourself.'
            ], 422);
        }

        $user = User::find($data['id']);

        if (!$user) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'User not found'
            ], 404);
        }

        $user_delete = $user->delete();
        if ($user_delete) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => "User Removed Successfully"
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => [],
            'message' => "Failed to remove user. Please contact Administrator if problem persists."
        ]);
    }
}
