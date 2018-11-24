<?php 
namespace App\Http\Controllers;

use App\User;
use App\Activation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller {
	
	public function __construct() {
		//Can add auth middleware here i guess
	}
	
	//Only Accessible with token.
	public function set(Request $request) {
		//TODO
	}
	
	public function get(Request $request) {
		$data = [
			'name' => "Cygnus",
			'version' => "188.4",
			'rates' => ['exp' => 4, 'meso' => 2, 'drop' => 1],
			'alert' => 'Welcome to Cygnus!',
			'server_status' => [
				[
					'name' => "Login",
					'status' => false
				],
				[
					'name' => "API",
					'status' => true
				],
				[
					'name' => "Center: Scania.",
					'status' => false
				],
				[
					'name' => "Game: Scania-01",
					'status' => false
				]
			],
			'online_count' => "0"
		];
		return $data;
	}
}