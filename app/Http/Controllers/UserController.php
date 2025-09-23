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
use App\Activation;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {

	public function __construct() {}

	/**
	 * @OA\Get(
	 *     path="/user",
	 *     summary="Get authenticated user's information",
	 *     tags={"User"},
	 *     security={{"passport": {}}},
	 *     @OA\Response(
	 *         response=200,
	 *         description="The authenticated user's data."
	 *     ),
	 *     @OA\Response(response=401, description="Unauthorized.")
	 * )
	 */
	public function get(Request $request) {
		return $request->user();
    }
    
    /**
     * @OA\Get(
     *     path="/account",
     *     summary="Get authenticated user's game account information",
     *     tags={"User"},
     *     security={{"passport": {}}},
     *     @OA\Response(response=200, description="The user's game account data."),
     *     @OA\Response(response=401, description="Unauthorized.")
     * )
     */
    public function getAccount(Request $request) {
        return Account::find($request->user()->id)->first();
    }
}
