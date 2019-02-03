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

/*
 * OAuth2 Protected endpoints. (Center endpoints are user id=1)
*/
$router->group(['middleware' => 'auth'], function () use ($router) {

	// ANY USER: User information
  $router->post('/login','LoginController@login'); //login on Aria-based website
	$router->post('/logout','LoginController@logout'); //logout (invalidates token)
	$router->get('/user','LoginController@user'); //show user info
	$router->get('/account','AccountController@show'); //get cygnus account

	// ADMIN USER: News Editing
	$router->post('/news','NewsController@store');
	$router->put('/post/{news_id}', 'NewsController@update');
	$router->delete('/post/{news_id}', 'NewsController@destroy');

	// CENTER USER
	$router->post('/server','ServerController@set'); //Post Server Information
	$router->post('/ranking', 'RankingController@store'); //Post Avatar/Ranking data
	$router->post('/ban','AccountController@ban'); //Ban User
	$router->get('/blocklist','ServerController@blocklist'); //Get blocklist
	$router->post('/account','AccountController@store'); //alter cygnus account

});

/*
 * Public endpoints.
*/

// Get Avatar/Ranking information
$router->get('/ranking', 'RankingController@show');
$router->get('/avatar/{name}', 'AvatarController@show');

// Get Server Information
$router->get('/server','ServerController@get');

// Get Images
$router->get('/image/{name}','AssetController@show');

// Get News
$router->get('/news/all','NewsController@index');
$router->get('/news','NewsController@page');
$router->get('/news/{page_id}','NewsController@page');
$router->get('/post/{news_id}','NewsController@show');

// User Creation
$router->post('/join','JoinController@store');
$router->get('/verify','LoginController@verify');

// Wz Endpoints
$router->get('/map/{id}','WzController@map');
$router->get('/npc/{id}','WzController@npc');
$router->get('/item/{id}','WzController@item');
$router->get('/mob/{id}','WzController@mob');
$router->get('/map/image/{id}','WzController@showMap');
$router->get('/npc/image/{id}','WzController@showNpc');
$router->get('/item/image/{id}','WzController@showItem');
$router->get('/mob/image/{id}','WzController@showMob');
$router->get('/search','WzController@find');
