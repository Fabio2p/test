<?php
namespace App\Repositories\Users;

use App\Models\User;
use Exception;
class UserRepositories{
    public function authennticateUserInSystem($email){
        try{
            $user = User::where('email', $email)->first();
            return $user;

        }catch(Exception $e){ return $e; }
    }
}