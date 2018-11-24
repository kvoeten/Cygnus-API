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

$router->get('/', function () use ($router) {
    return 'Cygnus API powered by: '.$router->app->version();
});

// Authorization required enpointes
$router->group(['middleware' => 'auth'], function () use ($router) {
	
	// Login, Logout
    $router->post('/login','LoginController@login');
	$router->post('/logout','LoginController@logout');
	
	// Set Server Information
	$router->post('/server','ServerController@set');
	
	// News Editing
	$router->post('/news','NewsController@store');
	$router->put('/post/{news_id}', 'NewsController@update');
	$router->delete('/post/{news_id}', 'NewsController@destroy');
	
});

$router->post('/ranking', 'RankingController@store');
$router->get('/ranking', 'RankingController@show');
$router->get('/avatar/{name}', 'AvatarController@show');


// Get Server Information
$router->get('/server','ServerController@get');

// Images
$router->get('/image/{name}','AssetController@show');

// News
$router->get('/news/all','NewsController@index');
$router->get('/news','NewsController@page');
$router->get('/news/{page_id}','NewsController@page');
$router->get('/post/{news_id}','NewsController@show');

// User
$router->post('/join','JoinController@store');
$router->get('/verify','LoginController@verify');

