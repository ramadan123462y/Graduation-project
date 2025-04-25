<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Twilio\Rest\Client;
use App\Traits\Response;
use App\Mail\SendOtpEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\AuthLoginResource;
use Illuminate\Container\Attributes\Auth;
use App\Http\Requests\AuthRegisterRequest;


class AuthController extends Controller
{
    use Response;

    public function register(AuthRegisterRequest $request)
    {

        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return $this->sendResponse([], __('auth.Register successfully'), 200);
    }


    public function login(AuthLoginRequest $request)
    {
        $validated = $request->validated();


        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->sendError(__('auth.Credentials do not match'), 401);
        }

        return $this->sendResponse(new AuthLoginResource($user), __('auth.Login successfully'), 200);
    }

    public function logout()
    {
        $user = auth()->guard('users')->user();
        $user->tokens()->delete();

        return $this->sendResponse([], __('auth.Logout successfully'), 200);
    }
    public function passwordRecovery(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError(__('auth.Email not found'), 404);
        }

        $tokenEntry = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if ($tokenEntry) {
            return $this->sendError(__('auth.email already sent'), 400);
        }

        $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $user->otp = $otp;
        $user->save();

        $token = Hash::make(Str::random(60));

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        Mail::to($user->email)->send(new SendOtpEmail($user));

        return $this->sendResponse([], __('auth.Otp Send successfully'), 200);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp != $request->otp) {
            return $this->sendError(__('auth.Otp incorrect'), 404);
        }

        $user->otp = null;
        $user->save();

        $tokenEntry = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        if (!$tokenEntry) {
            return $this->sendError('Token not found', 404);
        }

        $response = [
            'token' => $tokenEntry->token
        ];

        return $this->sendResponse($response, __('auth.Otp Correct successfully'), 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError(__('auth.Email not found'), 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return $this->sendResponse([], __('auth.Reset password successfully'), 200);
    }



    public function deleteAccount (Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);
        
        $user = auth('users')->user();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError(__('auth.Credentials do not match'), 401);
        }
        $user->delete();
        return $this->sendResponse([], __('auth.Account deleted successfully'), 200);
    }
        



    private function sendMessage($message, $recipients)
    {

        // $message = "Hi $user->user_name, Your OTP is $otp";
        // $phone = $user->phone_number;
        // $this->sendMessage($message,   $phone);


        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);

        // $client->messages->create('+201068335370', ['from' => $twilio_number, 'body' => $message]);

        // $client->messages->create('+2'.$recipients, ['from' => $twilio_number, 'body' => $message]);

    }
}
