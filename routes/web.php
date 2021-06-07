<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('foo', function () {
    return 'GET:Hello World foo';
});

$router->post('foo', function () {
    return 'POST:Hello World foo';
});

// $router->get('user/{id}', function ($id) {
//     return 'User '.$id;
// });

$router->get('user/{id}', 'UserController@getUserById');

$router->post('user', 'UserController@createUser');

$router->put('user/{id}', 'UserController@updateUser');

$router->get('user2[/{name}]', function ($name = null) {
    return 'Name = ' . $name;
});