<?php

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

/**
 * @var Laravel\Lumen\Routing\Router $router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route group
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'Auth\AuthController@login');

    $router->group(['middleware' => ['jwt.verify']], function () use($router) {
        $router->get('me', 'Auth\AuthController@me');
        $router->get('logout', 'Auth\AuthController@logout');

        $router->group(['prefix' => 'v1'], function () use ($router) {

            // Authors
            $router->get('authors', 'V1\Author\AuthorController@index');
            $router->get('authors/{author}', 'V1\Author\AuthorController@show');
            $router->post('authors', 'V1\Author\AuthorController@store');
            $router->put('authors/{author}', 'V1\Author\AuthorController@update');
            $router->patch('authors/{author}', 'V1\Author\AuthorController@restore');
            $router->delete('authors/{author}', 'V1\Author\AuthorController@destroy');
        });
    });
});