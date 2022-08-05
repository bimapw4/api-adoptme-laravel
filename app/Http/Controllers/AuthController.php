<?php
namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthManager;
use App\User;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        
        try {
            (new ValidatorManager)->validateJSON($request, self::ruleRegister());

            return Users::create([
                "fullname" => $request->username,
                "email" => $request->email,
                "password" => hash("sha256", $request->password),
                "address" => $request->address,
            ]);

        } catch (\ValidateException $th) {
        }
    }

    public function Login(Request $request)
    { 
        try {
            (new ValidatorManager)->validateJSON($request, self::ruleLogin());

            $data =  Users::where("email", $request->email)->where("password", hash('sha256', $request->password))->first();

            $jwt = (new AuthManager)->generateJWT($data);

            return [
                "status" => true,
                "data" =>  $data,
                "token" => $jwt
            ];

        } catch (\UnauthorizedException $th) {
        }
    }

    public static function ruleRegister()
    {
        return [
            "username" => "required",
            "email" => "required|email|unique:users,email",
            "password"  => "required",
        ];
    }

    public static function ruleLogin()
    {
        return [
            "email" => "required|email|exists:users,email",
            "password"  => "required",
        ];
    }
}
