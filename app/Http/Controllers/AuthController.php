<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginToken;
use Hash;
use Illuminate\Facades\Support\Str;
use Validator;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2|max:20',
            'last_name' => 'required|alpha|min:2|max:20',
            'username' => "required|string|min:5|max:12|unique:users,username|
            regex:'^[a-zA-Z0-9_\.]*$'",
            'password' => 'required|min:5|max:12|confirmed'
        ], [
            'username.regex' => 'Username must be only alphabetic, numeric, underscores and dot characters!'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = new LoginToken;
        $token->user_id = $user->id;
        $token->token = Hash::make($user->id);
        $token->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Register!',
            'user' => $user,
            'token' => $token->token
        ], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'invalid login'
            ], 401);
        }
 
        $check = Auth::attempt(['username' => $request->username, 'password' => $request->password]);

        if (!$check) {
            return response()->json([
                'status' => false,
                'message' => 'username and password do not match or empty!'
            ], 401);
        }

        $userId = Auth::user()->id;
        
        $token = new LoginToken;
        $token->user_id = $userId;
        $token->token = Hash::make($userId);
        $token->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Logged In!',
            'user' => User::find(Auth::user()->id),
            'token' => $token->token
        ], 200);
    }
    public function logout(Request $request)
    {
        $token = $request->get('token');

        $result = LoginToken::where('token', $token)->first();
        $result->delete();

        return response()->json([
            'status' => true,
            'message' => 'logout success'
        ], 200);
    }    
    public function getUser(Request $request)
    {
        $token = $request->get('token');

        if ($token == NULL) {
            return response()->json([
                'message' => 'User not found!'
            ], 404);
        }

        $find = LoginToken::where('token', $token)->first();

        if (!$find || !$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user!'
            ], 401);
        }

        $user = User::find($find->user_id);

        return response()->json([
            'user' => $user
        ]);
    }
}
