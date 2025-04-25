<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:100',
                'c_password' => 'required|same:password',

            ]
        );

        if ($validator->fails()) {
            return apiResponse([], $validator->errors(), 403);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image_name' => 'profile.png'
            ]);

            return apiResponse(['user' => $user], "User registered successfully.", 201);
        } catch (\Exception $e) {
            return apiResponse([], "Failed to register user.", 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|max:100',
            ]
        );

        if ($validator->fails()) {
            return apiResponse([], $validator->errors(), 403);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return apiResponse([], "Unauthorised", 401);
            }

            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return apiResponse([], "Login failed. Please try again.", 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return apiResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ], null);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return apiResponse([], "Unauthorized", 401);
        }

        return apiResponse(['auth' => new UserResource($user)], null, 200);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (!Auth::guard('api')->check()) {
            return apiResponse([], 'User not authenticated.', 401);
        }

        Auth::guard('api')->logout();

        return apiResponse([], 'Successfully logged out.', 200);
    }
}
