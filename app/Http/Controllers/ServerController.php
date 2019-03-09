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

class ServerController extends Controller {

	public function __construct() {}

	//Only Accessible with token.
	//Used to set name, ip and update user count.
	public function set(Request $request) {
		//Simplified access authorization
		if(!$request->user()->access_level >= 5) {
			return $this->error("Unauthorized.", 401);
		}
		
		//TODO: handle setting new values.
	}

	public function get(Request $request) {
        $players = 0;
        $status = [];
        $servers = DB::table('servers')->get();
		
		foreach ($servers as $server) {
            $players += $server->users;
			status.push([
                'name' => $server->name,
                'status' => aliveCheck($server->ip)
            ])
		}
        
		$data = [
            'name' => env('APP_NAME', 'Cygnus'),
            'version' => env('SERVER_VERSION', 0),
            'rates' => [
                'exp' => env('SERVER_EXP_RATE', 1),
                'meso' => env('SERVER_MESO_RATE', 0),
                'drop' => env('SERVER_DROP_RATE', 0)
            ],
            'alert' => env('SERVER_NOTICE', 'Welcome to Cygnus!'),
            'server_status' => $status,
            'online_count' => $players
        ];
        
		return $data;
    }

  //Linux only. 0 if alive, 1 if dead.
    function aliveCheck($ip) {
    $pingresult = exec("/bin/ping -n 3 $ip", $outcome, $status);
        return $status != 0;
  }
}
