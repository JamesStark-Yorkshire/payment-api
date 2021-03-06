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

$router->group(['prefix' => 'account', 'as' => 'account'], function () use ($router) {
    $router->get('/', ['as' => 'index', 'uses' => 'AccountController@index']);
    $router->post('/', ['as' => 'store', 'uses' => 'AccountController@store']);
    $router->get('/{uuid}', ['as' => 'show', 'uses' => 'AccountController@show']);
    $router->delete('/{uuid}', ['as' => 'destroy', 'uses' => 'AccountController@destroy']);
});

$router->group(['prefix' => 'payment_method', 'as' => 'payment_method'], function () use ($router) {
    $router->post('/', ['as' => 'store', 'uses' => 'PaymentMethodController@store']);
    $router->get('/{uuid}', ['as' => 'show', 'uses' => 'PaymentMethodController@show']);
    $router->delete('/{uuid}', ['as' => 'destroy', 'uses' => 'PaymentMethodController@destroy']);
});

$router->group(['prefix' => 'payment', 'as' => 'payment'], function () use ($router) {
    $router->get('/', ['as' => 'index', 'uses' => 'PaymentController@index']);
    $router->post('/', ['as' => 'store', 'uses' => 'PaymentController@store']);
    $router->get('/{uuid}', ['as' => 'show', 'uses' => 'PaymentController@show']);
    $router->post('/{uuid}/refund', ['as' => 'refund', 'uses' => 'PaymentController@refund']);
});
