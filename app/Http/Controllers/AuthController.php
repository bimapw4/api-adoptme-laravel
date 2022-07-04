<?php
namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        
        try {
            (new ValidatorManager)->validateJSON($request, self::ruleRegister());

            return Users::create([
                "fullname" => $request->fullname,
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

            $user  = Users::where("email", $request->email)->where("password", hash('sha256', $request->password))->count();

            if ($user == 0) {
                throw new UnauthorizedException("User Not Found", 404);
            }

            return Response([
                "status" => true,
                "message" => "sukses login"
            ]);

        } catch (\UnauthorizedException $th) {
        }
    }

    public static function ruleRegister()
    {
        return [
            "fullname" => "required",
            "email" => "required",
            "password"  => "required",
            "address"  => "required",
        ];
    }

    public static function ruleLogin()
    {
        return [
            "email" => "required",
            "password"  => "required",
        ];
    }
}
