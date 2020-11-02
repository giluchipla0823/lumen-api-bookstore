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

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API route groupH
$router->group(['prefix' => 'api'], function() use($router) {
    $router->post('login', 'Auth\AuthController@login');

    $router->group(['middleware' => ['jwt.verify']], function() use($router) {
        $router->get('me', 'Auth\AuthController@me');
        $router->get('logout', 'Auth\AuthController@logout');

        $router->group(['prefix' => 'v1'], function() use($router) {

            // Authors
            setAuthorsRoute($router);

            // Reports
            $router->get('report/books', 'V1\Report\BookController@index');
        });
    });
});

/**
 * Rutas para autores
 *
 * @param Router $router
 */
function setAuthorsRoute(Router $router){
    $url = 'authors';
    $urlWithSlug = "{$url}/{author}";
    $controller = 'V1\Author\AuthorController';

    $router->get($url, "{$controller}@index");
    $router->get($urlWithSlug, "{$controller}@show");
    $router->post($url, "{$controller}@store");
    $router->put($urlWithSlug, "{$controller}@update");
    $router->patch($urlWithSlug, "{$controller}@restore");
    $router->delete($url, "{$controller}@destroy");
}