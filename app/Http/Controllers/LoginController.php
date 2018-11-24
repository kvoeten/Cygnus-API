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
	
	public function __construct() {
		//Can add auth middleware here i guess
	}
	
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
				return $this->success("Your account has been activated succesfully.", 200);
			} else {
				return $this->error("There was an error with the verification process.", 404);	
			}
		} else {
			return $this->error("No activation with the supplied token was found..", 404);
		}
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
}