<?php 
namespace  App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Services\Validator\ValidatorManager;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
       return Type::get();
    }
}
