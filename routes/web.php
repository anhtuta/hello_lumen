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

/***********
 * User routes
 ***********/
$router->get('user/{id}', 'UserController@getUserById');
$router->get('user2[/{name}]', function ($name = null) {
    return 'Name = ' . $name;
});
$router->post('user', 'UserController@createUser');
$router->put('user/{id}', 'UserController@updateUser');

/***********
 * Song routes
 ***********/
$router->get('api/song/all', 'Liliana\SongController@getAllSongs');
$router->get('api/song/by-id/{id}', 'Liliana\SongController@getSongById');
$router->get('api/song', 'Liliana\SongController@getSong');
$router->get('api/song/album', 'Liliana\SongController@getAlbum');

$router->put('api/song/listens', 'Liliana\SongController@updateListens');

/***********
 * Lyric routes
 ***********/
$router->get('api/lyric', 'Liliana\LyricController@getLyricByFileName');
$router->get('api/lyric/update/offset', 'Liliana\LyricController@updateOffset');
