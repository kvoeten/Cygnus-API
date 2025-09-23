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

/**
 * @OA\Info(
 *   title="Cygnus API",
 *   version="1.0.0"
 * )
 */
class ServerController extends Controller {

    public function __construct() {}
    
    /**
     * @OA\Post(
     *     path="/server",
     *     summary="Update server information (from game server)",
     *     description="This endpoint is used by the game server to update its status, such as online user count. Requires system-level authentication.",
     *     tags={"Server"},
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         description="Server data to update.",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="users", type="integer", example=50, description="The number of users online on the server."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Server information updated successfully."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     )
     * )
     */
	public function set(Request $request) {
        // Simplified access authorization
        if (!$request->user() || $request->user()->access_level < 5) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        
        // TODO: Handle setting new values from the request.
        return response()->json(['status' => 'success']);
    }
    
 	//Linux only. 0 if alive, 1 if dead.
    private function isServerAlive(string $ip, int $port = 8484, int $timeout = 1): bool {
        
        // Validate that the IP is a valid format to prevent command injection
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        // Use fsockopen to check if a port is available.
        $connection = @fsockopen($ip, $port, $errno, $errstr, $timeout);

        if (is_resource($connection)) {
            fclose($connection);
            return true;
        }

        return false;
  	}

	/**
	 * @OA\Get(
	 *     path="/server",
	 *     summary="Get server status and information",
	 *     tags={"Server"},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="name", type="string", example="Cygnus"),
	 *             @OA\Property(property="version", type="string", example="188.4"),
	 *             @OA\Property(
	 *                 property="rates",
	 *                 type="object",
	 *                 @OA\Property(property="exp", type="integer", example=4),
	 *                 @OA\Property(property="meso", type="integer", example=2),
	 *                 @OA\Property(property="drop", type="integer", example=1)
	 *             ),
	 *             @OA\Property(property="alert", type="string", example="Welcome to Cygnus!"),
	 *             @OA\Property(
	 *                 property="server_status",
	 *                 type="array",
	 *                 @OA\Items(type="object", @OA\Property(property="name", type="string", example="Channel 1"), @OA\Property(property="status", type="boolean", example=true))
	 *             ),
	 *             @OA\Property(property="online_count", type="integer", example=0)
	 *         )
	 *     )
	 * )
	 */
	public function get(Request $request) {
        $players = 0;
        $status = [];
        $servers = DB::table('servers')->get();
		
		foreach ($servers as $server) {
            $players += $server->users;
			array_push($status, [
                'name' => $server->name,
                'status' => $this->isServerAlive($server->ip, $server->port ?? 8484)
            ]);
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

    /**
     * @OA\Get(
     *     path="/blocklist",
     *     summary="Get the server's blocklist",
     *     description="Retrieves a list of banned IPs and user IDs. Requires system-level authentication.",
     *     tags={"Server"},
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="ips", type="array", @OA\Items(type="string", example="127.0.0.1")),
     *             @OA\Property(property="users", type="array", @OA\Items(type="integer", example=1))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function blocklist(Request $request)
    {
        if (!$request->user() || $request->user()->access_level < 5) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        // TODO: Replace with actual data from the database.
        return response()->json(['ips' => [], 'users' => []]);
    }
}
