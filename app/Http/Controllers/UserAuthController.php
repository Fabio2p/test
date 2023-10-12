<?php

namespace App\Http\Controllers;
use App\UseCases\Users\AuthenticateUseCases;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);
    // }

    public function login(Request $request, AuthenticateUseCases $authenticate)
    {
        $user = $authenticate->authenticateUserInSystem($request);
        
        return $user;
        
        // if($user){ return $this->responseTokenJwt($user); }
        
    }


    public function refreshToken(Request $request){
        return response()->json(auth()->refresh());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
