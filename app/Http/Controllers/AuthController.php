<?php
namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthManager;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        
        try {
            (new ValidatorManager)->validateJSON($request, self::ruleRegister());

            // return Users::create([
            //     "fullname" => $request->fullname,
            //     "email" => $request->email,
            //     "password" => hash("sha256", $request->password),
            //     "address" => $request->address,
            // ]);

            $JsonValue = file_get_contents(resource_path('auth.json'));
            $data = json_decode($JsonValue, true);

            if (!empty($data[$request->email])) {
                return "lalala";
            }
            
            $data[$request->email] =  [
                "id" => count($data) + 1,
                "data" => [
                    "username" => $request->username,
                    "email" => $request->email,
                    "password" => $request->password
                ]
            ];

            file_put_contents(resource_path('auth.json'), json_encode($data, JSON_PRETTY_PRINT));

            return [
                "status" => true,
                "data" => $request->all()
            ];



        } catch (\ValidateException $th) {
        }
    }

    public function Login(Request $request)
    { 
        try {
            (new ValidatorManager)->validateJSON($request, self::ruleLogin());

            $JsonValue = file_get_contents(resource_path('auth.json'));
            $data = json_decode($JsonValue, true);

            if (empty($data[$request->email])) {
                return Response([
                    "status" => false,
                    "data" => null,
                    "message" => "email already exist"
                ], 422);
            }

            if ($data[$request->email]["data"]["password"] != $request->password) {
                return Response([
                    "status" => false,
                    "data" => null,
                    "message" => "invalid password"
                ], 422);
            };

            
            $data = $data[$request->email];
            unset($data['data']["password"]);

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
            "email" => "required",
            "password"  => "required",
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
