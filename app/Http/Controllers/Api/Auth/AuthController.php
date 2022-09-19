<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create User
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password)
            ]);

            return response()->success('User Created Successfully', $user);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our records.',
                ], 401);
            }

            $user = User::whereEmail($request->email)->first();
            $user_type = $user->role_id > 0 ? 'Admin' : 'User';

            return response()->json([
                'status' => true,
                'message' => $user_type . ' Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
