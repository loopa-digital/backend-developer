<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'v1'], function ($app) {

    $app->group(['prefix' => 'sales'], function ($sales) {
        $sales->post('/', 'SaleController@store');
    });
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});
