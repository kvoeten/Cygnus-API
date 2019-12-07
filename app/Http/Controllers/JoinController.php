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

	public function store(Request $request) {
		$validator = Validator::make(request()->all(), [
			'name' => 'required|unique:users,name|between:3,30',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|confirmed|between:6,64',
			'birthday' => 'required|date',
			'gender' => 'required|integer|max:3',
			'g-recaptcha-response' => 'required|string'
		]);
        
        if ($validator->fails()) {
        	return $this->error($validator->errors()->first(), 200);
        }

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

		$user = User::create([
			'name' => $request->get('name'),
			'email' => $request->get('email'),
			'password' => Hash::make($password),
		]);
		
		DB::insert(
			'insert into accounts (nAccountID, nGender, pBirthDate) values (?, ?, ?)', 
			[$user->id, $request->get('gender'), $request->get('birthday')
		);

		if ($user) {
			return $this->success($user, 200);
		} else {
			return $this->error("An unknown error occured.", 422);
		}
	}
}
