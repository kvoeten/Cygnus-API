<?php
/*
	This file is a part of the Cygnus API, a RESTful Lumen based API.
    Copyright (C) 2018 Kaz Voeten

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

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
 * OAuth2 Protected endpoints.
*/
$router->group(['middleware' => 'auth'], function () use ($router) {

	// ANY USER: User information
	$router->get('/user','LoginController@user'); //show user info
	$router->get('/account','AccountController@show'); //get cygnus account

	// GM USER: Access Level >= 2
	$router->post('/post','NewsController@store'); //Access Level > 3
	$router->put('/post/{news_id}', 'NewsController@update'); //Access Level > 3
	$router->delete('/post/{news_id}', 'NewsController@destroy'); //
	
	// ADMIN USER: Access Level >= 3
	$router->post('/ban','AccountController@ban'); //Ban User
	$router->post('/account','AccountController@store'); //Alter cygnus account

	// CENTER/SYSTEM USER: Access Level == 5
	$router->post('/server','ServerController@set'); //Post Server Information
	$router->post('/ranking', 'RankingController@store'); //Post Avatar/Ranking data
	$router->get('/blocklist','ServerController@blocklist'); //Get blocklist

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
