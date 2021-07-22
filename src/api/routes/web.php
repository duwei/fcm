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
    $router->post('login', 'FcmController@login');
    $router->post('set_config_version', 'FcmController@SetVersion');
    $router->post('set_maintenance_mode', 'FcmController@SetMaintenance');
    $router->post('send_cmd_to_all_server', 'FcmController@SendCmdToAllServer');
    $router->post('get_server_list', 'FcmController@ListAllServers');
    $router->post('get_server_status', 'FcmController@GetServerStatus');
    $router->get('game_data_version', 'FcmController@GetDataVersion');
});
