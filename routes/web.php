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

$router->get('/', function () {
    return redirect('/docs');
});

/*
 * OAuth2 Protected endpoints.
*/
$router->group(['middleware' => 'auth'], function () use ($router) {

	// ANY USER: User information
	$router->get('/user','UserController@get'); // Show user info
	$router->get('/account','AccountController@get'); // Show account info

	// GM USER: Access Level >= 2
	$router->post('/article','NewsController@store'); // Access Level > 3
	$router->put('/article/{news_id}', 'NewsController@update'); // Access Level > 3
	$router->delete('/article/{news_id}', 'NewsController@destroy'); //
	
	// ADMIN USER: Access Level >= 3
	$router->post('/ban','AccountController@ban'); // Ban User
	$router->post('/account','AccountController@store'); // Alter cygnus account

	// CENTER/SYSTEM USER: Access Level == 5
	$router->post('/server','ServerController@set'); // Post Server Information
	$router->post('/ranking', 'RankingController@store'); // Post Avatar/Ranking data
	$router->get('/blocklist','ServerController@blocklist'); // Get blocklist
	$router->post('/avatar', 'AvatarController@create'); // Create or update avatar
	$router->put('/avatar/{id}', 'AvatarController@update'); // Update avatar info

});

/*
 * Public endpoints.
*/

// Get Avatar/Ranking information
$router->get('/avatar/{id}', 'AvatarController@get');
$router->get('/avatar/image/{id}', 'AvatarController@image');

// Get Ranking Info
$router->get('/ranking', 'RankingController@find');
$router->get('/ranking/top', 'RankingController@top');

// Get Server Information
$router->get('/server','ServerController@get');

// Get Images
$router->get('/image/{name}','AssetController@show');

// Get News
$router->get('/news','NewsController@page');
$router->get('/news/{page}','NewsController@page');
$router->get('/article/{article}','NewsController@show');

// User Creation
$router->post('/register','RegisterController@register');

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
