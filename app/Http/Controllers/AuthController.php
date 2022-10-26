<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\General\DataResource;
use App\Http\Resources\General\SuccessResource;
use App\Http\Resources\Models\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * AuthController's constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'user',
            'logout',
        ]);
    }

    /**
     * Show current authenticated user
     *
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request)
    {
        return new UserResource(Auth::user());
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return DataResource
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        $user = new User();
        $user->name = $input['name'];
        $user->type = $input['type'];
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->save();

        $token = $user->createToken(User::PASSPORT_TOKEN_NAME);

        return new DataResource([
            'user' => new UserResource($user),
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return DataResource
     */
    public function login(LoginRequest $request)
    {
        $isAuth = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if (!$isAuth) {
            abort(401, 'Invalid credentials!');
        }

        $user = Auth::user();

        $token = $user->createToken(User::PASSPORT_TOKEN_NAME);

        return new DataResource([
            'user' => new UserResource($user),
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Logout
     *
     * @return SuccessResource
     */
    public function logout()
    {
        Auth::user()->token()->delete();
        return new SuccessResource([]);
    }
}
