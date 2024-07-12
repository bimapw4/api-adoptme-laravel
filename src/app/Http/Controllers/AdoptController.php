<?php 
namespace  App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalDetail;
use App\Models\City;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdoptController extends Controller
{
    public function GetAnimal(Request $request)
    {
        $animal = Animal::query();

        if ($request->id_city) {
            $animal->where('id_city', $request->id_city);
        }
        return $animal->orderBy('id', 'desc')->get();
    }

    public function PostAnimal(Request $request)
    {
        try {
            DB::beginTransaction();
            (new ValidatorManager)->validateJSON($request, self::rule(true));

            $animal = Animal::create([
                'image' => $request->image_cover, 
                'animal' => $request->animal, 
                'deskripsi' => $request->description,
                'id_user' => $request->userdata->id,
                'id_type' => $request->id_type,
                'id_city' => $request->id_city
            ]);

            AnimalDetail::create([
                'id_animal' => $animal->id, 
                'image' => $request->image_cover, 
                'breed' => $request->breed,
                'age' => $request->age,
                'sex' => $request->sex,
                'about' => $request->about
            ]);
            DB::commit();
            return;
        } catch (\ValidateException $th) {
            DB::rollback();
        } catch (\UnauthorizedException $th) {
        } 
    }

    public function EditAnimal(Request $request)
    {
        try {
            DB::beginTransaction();

            (new ValidatorManager)->validateJSON($request, self::rule(false));

            Animal::where("id", $request->id_animal)->update([
                'image' => $request->cover_image, 
                'animal' => $request->animal, 
                'status' => $request->status,
                'deskripsi' => $request->description,
                'id_user' => $request->userdata->id,
                'id_type' => $request->id_type,
                'id_city' => $request->id_city
            ]);
    
            AnimalDetail::where("id", $request->id_animal)->update([ 
                'image' => $request->image, 
                'breed' => $request->breed,
                'age' => $request->age,
                'sex' => $request->sex,
                'about' => $request->about
            ]);
            DB::commit();
            return;
        } catch (\ValidateException $th) {
            DB::rollback();
        }
    }

    public static function rule($post)
    {
        $validate =  [
            "image_cover" => "required",
            "description" => "required",
            "id_type" => "required",
            "id_city" => "required",
            "image" => "required",
            "breed" => "required",
            "age" => "required",
            "sex" => "required",
            "about" => "required",
            "animal" => "required"
        ];

        if (!$post) {
            $validate["status"] = "required";
        }

        return $validate;
    }
}
