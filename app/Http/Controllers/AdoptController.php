<?php 
namespace  App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;

class AdoptController extends Controller
{
    public function GetAnimal(Request $request)
    {
        $JsonValue = file_get_contents(resource_path('animal.json'));
        $datas = json_decode($JsonValue, true);

        $output = [];
        foreach ($datas as $data) {
            foreach ($data["data"] as $key => $value) {
                foreach ($value["data"] as $key => $animal) {
                    $output[] = $animal;
                };
            } 
        }
        return $output;
    }

    public function PostAnimal(Request $request)
    {
        try {
            (new ValidatorManager)->validateJSON($request, self::rule());

            $request->email = $request->userdata->data->email;
            $request->id_user = $request->userdata->id;

            $JsonValue = file_get_contents(resource_path('animal.json'));
            $data = json_decode($JsonValue, true);

            if (empty($data[$request->type])) {
                $data[$request->type] = [
                    "type" => $request->type,
                    "data" => [
                        $request->email => [
                            "email" => $request->email,
                            "data" => [
                                [
                                    "id" => uniqid(),
                                    "status" => "AVAILABLE",
                                    "animal" => $request->animal,
                                    "IDUser" => $request->id_user
                                ]
                            ]
                        ]
                    ]
                ];
                file_put_contents(resource_path('animal.json'), json_encode($data, JSON_PRETTY_PRINT));
                return;
            }

            if (empty($data[$request->type]["data"][$request->email])) {
                $data[$request->type]["data"][$request->email] = [
                    "email" => $request->email,
                    "data" => [
                        [
                            "id" => uniqid(),
                            "status" => "AVAILABLE",
                            "animal" => $request->animal,
                            "IDUser" => $request->id_user
                        ]
                    ]
                ];
                file_put_contents(resource_path('animal.json'), json_encode($data, JSON_PRETTY_PRINT));
                return;
            }

            if (!empty($data[$request->type]["data"][$request->email]["data"])) {
                $datas = $data[$request->type]["data"][$request->email]["data"];
                $input = [
                    "id" => uniqid(),
                    "status" => "AVAILABLE",
                    "animal" => $request->animal,
                    "IDUser" => $request->id_user
                ];

                array_push($datas, $input);
                $data[$request->type]["data"][$request->email]["data"] = $datas;

                file_put_contents(resource_path('animal.json'), json_encode($data, JSON_PRETTY_PRINT));
                return;
            }
        } catch (\UnauthorizedException $th) {
        }
    }

    public static function rule()
    {
        return [
            "type" => "required|integer",
            "animal" => "required"
        ];
    }
}
