<?php
namespace App\UseCases\Users;

use App\Repositories\Users\UserRepositories;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthenticateUseCases{
    protected $auth;
    public function __construct(UserRepositories $users){
        $this->auth = $users;
    }

    public function authenticateUserInSystem($request){
        $user = $this->auth->authennticateUserInSystem($request->input('email'));

        if (!$user || !Hash::check($request->input('senha'), $user->senha)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $customClaims = [
            'custom_key' => 'custom_value',
            'sssssssssssss' => 'aaaaaaaaaaaaaaaa'
        ];
        
        $token = JWTAuth::claims($customClaims)->fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => auth()->user(),
            'expires_in'   => auth()->factory()->getTTL() * 60 * 24
        ]);
    }
}