<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class LumenAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'senha' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'senha']);

        $user = User::where('email', $request->input('email'))->first();
        
        if (!$user || !Hash::check($request->input('senha'), $user->senha)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }
        $customClaims = [
            'custom_key' => 'custom_value',
            'sssssssssssss' => 'aaaaaaaaaaaaaaaa'
        ];
        
        $token = JWTAuth::claims($customClaims)->fromUser($user);
        
        return $this->jsonResponse($token);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->jsonResponse(auth()->refresh());
    }
}
