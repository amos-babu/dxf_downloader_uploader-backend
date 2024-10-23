<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;

class AuthController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function register(RegisterUserRequest $request)
    {
        //
    }

    /**
     * Compare the data entered and the database login the user.
     */
    public function login(LoginUserRequest $request, User $user)
    {
        //
    }

    /**
     * Delete the logged in user session.
     */
    public function logout(User $user)
    {
        //
    }
}
