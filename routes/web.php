<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Models\Users;
use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/test-post', function (Request $request) use ($router) {
    return $request->all();
});

$router->post('/test-user', function (Request $request) use ($router) {
    return Users::get();
});

$router->get('/login', "AuthController@Login");
$router->get('/login', "AuthController@Register");
