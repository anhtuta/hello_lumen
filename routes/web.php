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
$router->get('hello-world', function () {
    return 'Hello world!';
});

/***********
 * User routes
 ***********/
$router->get('user/{id}', 'UserController@getUserById');
$router->get('user2[/{name}]', function ($name = null) {
    return 'Name = ' . $name;
});
$router->post('user', 'UserController@createUser');
$router->put('user/{id}', 'UserController@updateUser');

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->get('/me', 'AuthController@me');
});

/***********
 * Song routes
 ***********/
$router->group(['prefix' => 'api/song'], function () use ($router) {
    $router->get('', 'Liliana\SongController@getSongs');  // with pagination
    $router->post('', 'Liliana\SongController@createSong');
    $router->get('/all', 'Liliana\SongController@getAllSongs');
    $router->get('/file', 'Liliana\SongController@getSongByFile');
    $router->get('/id/{id}', 'Liliana\SongController@getSongById');
    $router->post('/id/{id}', 'Liliana\SongController@updateSong');
    $router->delete('/id/{id}', 'Liliana\SongController@deleteSong');
    $router->get('/album', 'Liliana\SongController@getPictureByFile');
    $router->get('/picture', 'Liliana\SongController@getPictureByFile');
    $router->put('/listens', 'Liliana\SongController@updateListens');
    $router->get('/type/all', 'Liliana\SongController@getAllTypes');
});


/***********
 * Lyric routes
 ***********/
$router->group(['prefix' => 'api/lyric'], function () use ($router) {
    $router->get('', 'Liliana\LyricController@getLyricByFileName');
    $router->get('/update/offset', 'Liliana\LyricController@updateOffset');
});
