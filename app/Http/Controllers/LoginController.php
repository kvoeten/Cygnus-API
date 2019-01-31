<?php 
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
			return $this->error(["This account hasn't been verified yet. Please verify the account first."], 404);
		}
		return $request->user();
		
	}
	
	//Only Accessible with token.
	public function login(Request $request) {
		if(!$request->user()->email_verified_at) {
			return $this->error(["This account hasn't been verified yet. Please verify the account first."], 404);
		}
		return $this->success([
				'logged_in' => true,
				'username' => $request->user()->name,
				'gm_level' => 1
				], 200);
		
	}
	
	//Only Accessible with token.
	public function logout(Request $request) {
		//You could basically invalidate the token here
		//Literally only here cus of how aria handles logout lmfao
		return $this->success([
				'logged_in' => false,
				'username' => "0",
				'gm_level' => 0
				], 200);
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