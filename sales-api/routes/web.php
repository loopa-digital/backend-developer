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

$apiPrefix = 'api/' . env('API_VERSION');

$router->group([ 'prefix' => $apiPrefix ], function () use ($router) {
  $router->get('/', 'HomeController@index');
  $router->post('/upload-sales', 'SalesController@uploadSales');
});
