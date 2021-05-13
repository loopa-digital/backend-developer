<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'customer'], function () use ($router) {  
    $router->post('interpreter', 'CustomerController@interpreter');
});

