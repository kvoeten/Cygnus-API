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
use App\Activation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller {

	public function __construct() {}

	public function verify(Request $request) {
		$rules = [
			'token' => 'required'
		];
		$this->validate($request, $rules);

		$activation = DB::table('activations')->where('activation_key', $request->get('token'))->first();
		if ($activation) {
			$user = DB::table('users')->where('email', $activation->email)->first();
			if ($user) {
				DB::table('users')->where('email', $activation->email)->update(['email_verified_at' => new DateTime()]);
				DB::table('activations')->where('activation_key', $request->get('token'))->delete();
				return $this->DisplayVerificationResult(true, "Your account has been", "succesfully activated!");
			} else {
				return $this->DisplayVerificationResult(false, "Something went wrong :/", "Please try again later.");
			}
		} else {
			return $this->DisplayVerificationResult(false, "Something went wrong :/", "The activation doesn't exist!");
		}
	}

	//Only Accessible with token.
	public function user(Request $request) {
		if(!$request->user()->email_verified_at) {
			return $this->error("This account hasn't been verified yet. Please verify the account first.", 428);
		}
		return $request->user();
	}

	public function DisplayVerificationResult($success, $message1, $message2) {
		return "
			<!DOCTYPE html>
			<html>
				<head>
					<meta name='viewport' content='width=device-width, initial-scale=1'>
						<style>
							body, html {
							  height: 100%;
							  margin: 0;
							  font: 400 15px/1.8 'Lato', sans-serif;
							  color: #777;
							}
								
							.bgimg-1, .bgimg-2, .bgimg-3 {
							  position: relative;
							  opacity: 0.65;
							  background-position: center;
							  background-repeat: no-repeat;
							  background-size: cover;
							}

							.bgimg-1 {
							  background-image: url('https://api.maplecygnus.com/image/Bus.jpg');
							  height: 100%;
							}

							.caption {
							  position: absolute;
							  left: 0;
							  top: 25%;
							  width: 100%;
							  text-align: center;
							  color: #000;
							}

							.caption span.border {
							  margin: 0 auto;
							  color: #fff;
							  width: 25%;
							  padding: 18px;
							  font-size: 25px;
							  letter-spacing: 8px;
							}

							h3 {
							  letter-spacing: 5px;
							  text-transform: uppercase;
							  font: 20px 'Lato', sans-serif;
							  color: #111;
							}
							a {
							  color:#fff;
							}
							a:hover {
							  color:#fff;
							}
							a:visited {
							  color:#fff;
							}
						</style>
					</head>
				<body>

				<div class='bgimg-1'>
				  <div class='caption'>
					<span class='border'>".($success ? "SUCCESS!" : "WHOOPSIE!")."</span><br>
					<span class='border'>".$message1."<br/></span>
					<span class='border'>".$message2."<br /></span>
					<span class='border'><a href='https://maplecygnus.com'><- Go back home.</a></span>
				  </div>
				</div>
				</body>
			</html>";
	}
}
