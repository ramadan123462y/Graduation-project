<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserArticleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function articles()
    {
        /** @var \App\Models\User $user */
        $user =  Auth::guard('api')->user();

        if (!$user->articles()->exists()) {
            return apiResponse([], "Not Found Articles", 404);
        }

        return apiResponse([
            'articles' => UserArticleResource::collection($user->articles()->with('user')->get())

        ]);
    }
}
