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

namespace App\Http\Controllers;

use App\User;
use App\Account;
use DateTime;
use Carbon\Carbon;
use App\Activation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller {

	public function __construct() {
		//Can add auth middleware here i guess
	}

	/**
	 * Handles user registration
	 */
	public function register(Request $request) {

		// Validate Request
		$rules = [
			'name' => 'required|unique:users,name|between:3,30',
			'code' => 'required|string',
			'password' => 'required|between:6,64',
			'birthday' => 'required|date',
			'gender' => 'required|integer|max:3'
		];
		$this->validate($request, $rules);
		
		// Get user info from Discord and create user
		$discordUser = null;
		try {
			$auth = $this->getDiscordAuth($request->get('code'));
			if ($auth['access_token']) {
				$token = $auth['access_token'];
				$expires = $auth['expires_in'];
				$user = $this->getDiscordUser($token);
				if ($user['id']) {

					if (!$user['verified']) {
						return $this->error('The provided discord account is not verified.', 200);
					}

					$result = User::create([
						'name' => $request->get('name'),
						'email' => $user['email'],
						'discord' => $user['id'],
						'icon' => 'http://api.maplecygnus.com/image/cygnus2.png',
						'password' => Hash::make($request->get('password')),
						'birthday' => $request->get('birthday')
					]);

					Account::create([
						'nAccountID' => $result->id, 
						'nCharSlots' => 12,
						'sAccountName' => $request->get('name'),
						'nGender' => $request->get('gender'), 
						'nGradeCode' => 0,
						'dBirthDay' => $request->get('birthday')
					]);
			
					if ($result) {
						return $this->success($user, 200);
					} else {
						return $this->error("Unable to create user.", 200);
					}
					
				} else {
					return $this->error('Unable to obtain user information. Is discord available?', 200);
				}
			} else {
				return $this->error('Code exchange failed. No discord authorization token was obtained.', 200);
			}
		} catch (Exception $error) {
			return $this->error('An unknown error has occured.', 200);
		}
	}
	
	/**
	 * Retrieve discord authorization
	 */
	public function getDiscordAuth($code) {
		$client = new Client();
		$response = $client->request('POST', 'https://discordapp.com/api/oauth2/token', [
			'headers' => [
                'content-type' => 'application/x-www-form-urlencoded',
            ],						 
		    'form_params' => [
				'grant_type' => 'authorization_code',
				'client_id' => env('DISCORD_CLIENT_ID', ''),
				'client_secret' => env('DISCORD_CLIENT_SECRET', ''),
				'redirect_uri' => env('DISCORD_REDIRECT_URI', ''),
				'code' => $code,
				'scope' => 'identify email'
			]
	    ]);
		return json_decode($response->getBody()->getContents(), true);
	}

	/**
	 * Attempts to get user information from discord with a given token
	 */
	public function getDiscordUser($token) {
		$client = new Client();
		$response = $client->request('GET', 'https://discordapp.com/api/users/@me', [
			'headers' => [
                'accept' => 'application/json',
				'authorization' => 'Bearer '.$token
            ]
	    ]);
		return json_decode($response->getBody()->getContents(), true);
	}
}
