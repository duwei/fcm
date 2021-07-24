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

$router->group([], function () use ($router) {
    $router->post('get_game', 'FcmController@getGame');
    $router->post('register', 'FcmController@register');
    $router->post('login', 'FcmController@login');
    $router->post('logout', 'FcmController@logout');
    $router->post('get_user', 'FcmController@getUser');
    $router->get('success', 'FcmController@success');
});
