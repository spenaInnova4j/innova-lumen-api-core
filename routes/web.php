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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(
        ['middleware' => 'auth:api'], function () use ($router) {
            // Remover el token
            $router->post('/logout', 'Controller@logout');
            // Refresca el token
            $router->post('refresh-token', function () use ($router) {
                $refresh = app()->make('request')->input("refresh_token");
                return (new \App\Auth\Proxy())->attemptRefresh(["refresh_token" => $refresh]);
            });
        }
    );

    $router->group(['namespace' => 'App\Http\Controllers'], function ($group) use ($router) {

        // Permite el inicio de sesion
        $group->post('login', function () use ($router) {
            $username = app()->make('request')->input("email");
            $password = app()->make('request')->input("password");
            return (new \App\Auth\Proxy())->attemptLogin(["username" => $username, "password" => $password]);
        });
    });
});
