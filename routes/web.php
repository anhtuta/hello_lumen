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
 * Note: In Lumen, PUT method cannot read FormData, so I use POST to update
 ***********/
$router->group(['prefix' => 'api/song'], function () use ($router) {
    $router->get('', 'Admin\AdminSongController@getSongs');  // with pagination
    $router->post('', 'Admin\AdminSongController@createSong');
    $router->get('/all', 'Liliana\SongController@getAllSongs');
    $router->get('/file', 'Liliana\SongController@getMp3File');
    $router->get('/id/{id}', 'Liliana\SongController@getSongById');
    $router->post('/id/{id}', 'Admin\AdminSongController@updateSong');
    $router->delete('/id/{id}', 'Admin\AdminSongController@deleteSong');
    $router->get('/album', 'Liliana\SongController@getPictureByFile');
    $router->get('/picture', 'Liliana\SongController@getPictureByFile');
    $router->put('/listens', 'Liliana\SongController@updateListens');
    $router->get('/type/all', 'Liliana\SongController@getAllTypes');
    $router->get('/update-path', 'Admin\AdminSongController@updatePath');
    $router->get('/update-lyric', 'Liliana\SongController@updateLyric');
});

/***********
 * Zing MP3 routes
 ***********/
$router->group(['prefix' => 'api/zing/mp3'], function () use ($router) {
    $router->get('/suggestion', 'Admin\AdminZingMp3Controller@suggestion');
    $router->get('/search/song', 'Admin\AdminZingMp3Controller@searchSong');
    $router->get('/stream', 'Liliana\ZingMp3Controller@getStream');
});


/***********
 * Lyric routes
 ***********/
$router->group(['prefix' => 'api/lyric'], function () use ($router) {
    $router->get('', 'Liliana\LyricController@getLyricByFileName');
    $router->post('/upload', 'Admin\AdminLyricController@uploadLyricFile');
    $router->get('/download', 'Liliana\LyricController@downloadLyricFile');
    $router->get('/update/offset', 'Liliana\LyricController@updateOffset');
});

/***********
 * Landing page routes
 ***********/
$router->group(['prefix' => 'api/landing/order'], function () use ($router) {
    $router->get('', 'Ddyy\LandingPageOrderController@getOrders');  // with pagination
    $router->post('', 'Ddyy\LandingPageOrderController@createOrder');
    $router->post('/update-status', 'Ddyy\LandingPageOrderController@updateStatus');
});