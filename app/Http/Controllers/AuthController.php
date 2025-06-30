<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(24));

        return response()->json([
            'user' => new RegisterResource($user),
            'token' => $token->plainTextToken,
            'token_expires_at' => $token->accessToken->expires_at,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Compare the data entered and the database login the user.
     */
    public function login(LoginUserRequest $request, User $user)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Authentication Failed!',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(24));
        return response()->json([
            'message' => 'Login Success!',
            'token' => $token->plainTextToken,
            'token_expires_at' => $token->accessToken->expires_at,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Delete the logged in user session.
     */

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'You are logged out!'
            ]);
        }

        return response()->json([
            'message' => 'No authenticated user found.'
        ], 400);
    }

    public function updateUserProfile(UpdateUserRequest $request)
    {
        $user = User::find(Auth::id());

        // Update user details
        $data = $request->validated();

        dd($data);

        // Handle profile image upload
        if ($request->hasFile('profile_pic_path')) {
            $filePath = $request->file('profile_pic_path')->store('profile_pics', 'public');
            $data['profile_pic_path'] = $filePath; 
        }

        $user->update($data);

        // return response
        return response()->json([
            'message' => 'Profile updated successfully!',
            'data' => $user->fresh(), 
        ]);
    }

    public function authenticatedUser(Request $request)
    {
        $authUser = $request->user();
        $authUser->load('files');

        return (new AuthResource($authUser))->additional([
            'can_edit' => true,
        ]);
    }

    public function userDetails($id)
    {
        $user = User::findOrFail($id);
        $user->load('files');

        return (new AuthResource($user))->additional([
            'can_edit' => false,
        ]);
    }
}
