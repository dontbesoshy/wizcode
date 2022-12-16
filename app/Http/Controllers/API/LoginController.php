<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\MainController as MainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends MainController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('wizcode')->accessToken;
            return $this->sendResponse($success, $user->email.' login successfully.');
        }
        else{
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised']);
        }
    }
}
