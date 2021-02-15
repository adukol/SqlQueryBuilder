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

$router->group(['prefix' => 'api'], function () use ($router){

    $router->get('table', 'SqlQueryReadController@getAllTable');
    $router->get('{tableName}/column', 'SqlQueryReadController@getAllColumn');
    $router->post('create_sql_query', 'SqlQueryReadController@createSqlQueryRead');
    $router->post('check_token', 'SqlQueryReadController@validateToken');

    // $router->post('authors', 'AuthorController@createAuthor');
    // $router->get('authors/{id}', 'AuthorController@showOneAuthor');
    // $router->put('authors/{id}', 'AuthorController@updateAuthor');
    // $router->delete('authors/{id}', 'AuthorController@deleteAuthor');
});
