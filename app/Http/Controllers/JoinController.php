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
		$this->validateRequest($request);
		$password = $request->get('password');
		$email = $request->get('email');

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
		
		if (strlen($request->get('name')) < 3) {
			return $this->error("The provided name is too short. (At least three characters required.)", 200);
		}

		if (strlen($request->get('name')) > 40) {
			return $this->error("The provided name is too long. (At max. fourty characters are allowed.)", 200);
		}

		if (User::where('email', '=', $email)->exists()) {
		    return $this->error("The provided email adress is already registered.", 200);
		}

		if (User::where('name', '=', $request->get('name'))->exists()) {
		    return $this->error("That name is already taken.", 200);
		}

		if ($password != $request->get('password_confirmation')) {
			return $this->error("The provided passwords don't match.", 200);
		}
		
		$birthday = $request->get('birthday');
		if (!$this->validateDate($birthday, 'Y-m-d')) {
			return $this->error("Invalid birthday.", 200);
		}
		
		if ($request->get('gender') != "male" 
			&& $request->get('gender') != "female" 
			&& $request->get('gender') != "undefined") {
			return $this->error("Invalid gender.", 200);
		}
		
		$gender = 2;	
		if ($request->get('gender') == "male") {
			$gender = 0;
		} else if ($request->get('gender') == "female"){
			$gender = 1;
		}

		$user = User::create([
			'name' => $request->get('name'),
			'email' => $email,
			'password' => Hash::make($password),
		]);

		$token = str_random(60);
		$activation = Activation::create([
			'email' => $email,
			'activation_key' => $token
		]);
		
		DB::insert('insert into accounts (nAccountID, nGender, pBirthDate) values (?, ?, ?)', 
			[$user->id, $gender, $birthday]);

		Mail::send(
			'emails.email', 
			['title_image' => "welcome.png", 
			'content' => "The Cygnus teams welcomes you! We only ask you to verify your email before continuing.", 
			'button_url' => 'https://api.maplecygnus.com/verify?token='.$token, 
			'button_text' => 'Verify Account!'], 
			function ($message) use ($email)
			{
				$message->from('accounts@maplecygnus.com', 'Maple Cygnus');
				$message->to($email);
				$message->subject('Verify your Cygnus account.');
			}
		);

		if ($user) {
			return $this->success($user, 200);
		} else {
			return $this->error("An unknown error occured.", 422);
		}
	}
	
	function validateDate($date, $format = 'Y-m-d H:i:s'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	public function validateRequest(Request $request) {
		$rules = [
			'name' => 'required',
			'email' => 'required',
			'password' => 'required',
			'password_confirmation' => 'required',
			'birthday' => 'required',
			'gender' => 'required',
			'g-recaptcha-response' => 'required'
		];
		$this->validate($request, $rules);
	}
}
