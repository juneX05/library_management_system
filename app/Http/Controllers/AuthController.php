<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Data Validation Failed'
            ], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'An Error Occurred while registering user. Please contact Administrator if problem persists.',
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Registration Successful',
            'token' => $user->createToken('Application')->plainTextToken,
            'permissions' => $user->user_permissions(),
            'name' => $user->name
        ]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
                'message' => 'Data Validation Failed'
            ], 422);
        }

        if (!Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ])) {
            return response()->json([
                'status' => false,
                'message' => 'Credentials provided are incorrect. Please try again.'
            ], 422);
        }

        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'token' => $user->createToken('Application')->plainTextToken,
            'permissions' => $user->user_permissions(),
            'name' => $user->name
        ]);
    }
}
