<?php 
namespace  App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $JsonValue = file_get_contents(resource_path('type.json'));
        $data = json_decode($JsonValue, true);

        $output = [];
        foreach ($data as $value) {
            $output[] = $value;
        }
        return $output;
    }
}
