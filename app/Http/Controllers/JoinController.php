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
use DateTime;
use Carbon\Carbon;
use App\Activation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class JoinController extends Controller {

	public function __construct() {
		//Can add auth middleware here i guess
	}

	/**
	 * Handles user registration
	 */
	public function join(Request $request) {

		// Validate Request
		$validator = Validator::make(request()->all(), [
			'name' => 'required|unique:users,name|between:3,30',
			'code' => 'required|string',
			'password' => 'required|confirmed|between:6,64',
			'birthday' => 'required|date',
			'gender' => 'required|integer|max:3',
			'g-recaptcha-response' => 'required|string'
		]);
        
        if ($validator->fails()) {
        	return $this->error($validator->errors()->first(), 200);
        }

		// Check Captcha 
		$client = new Client();
		$response = $client->post(
			'https://www.google.com/recaptcha/api/siteverify',
			['form_params'=>
				[
					'secret'=> env('CAPTCHA_SECRET', null),
					'response'=>$request->get('g-recaptcha-response')
				 ]
			]
		);

		$body = json_decode((string)$response->getBody());

		if (!$body->success) {
			return $this->error("Improper Captcha Response.", 200);
		}
		
		// Get user info from Discord and create user
		$discordUser = null;
		try {
			$auth = $this->getDiscordAuth($code);
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
						'password' => Hash::make($password),
						'gender' => $request->get('gender'), 
						'birthday' => $request->get('birthday')
					]);
			
					if ($result) {
						return $this->success($user, 200);
					} else {
						return $this->error("Unable to create user.", 422);
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
				'client_id' => '652970958742618112',
				'client_secret' => 'UzYKZRk0jobB3Y-lljyvJjdvF2LApyww',
				'redirect_uri' => 'https://api.skycastlems.com/verify',
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
