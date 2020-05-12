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

use App\AvatarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller {

	public function __construct() {
		//Can add auth middleware here i guess
	}

	public function validateRequest(Request $request) {
		$rules = [
			'page' => 'required|numeric|min:1',
			'order' => 'required|string',
			'category' => 'required|string',
			'world' => 'required|numeric',
		];
		$this->validate($request, $rules);
	}

	public function top() {
		$avatars = AvatarData::orderBy('nOverallRank', 'asc')->limit(3)->get();
		if (sizeof($avatars) < 1) {
			return $this->error("No characters found matching provided query.", 200);
		}
		foreach ($avatars as $avatar) {
			$avatar->CharacterStat; // Load stats onto avatar object
		}
		return $this->success($avatars, 200);
	}

	public function find(Request $request) {
		$this->validateRequest($request);
		
		$world = $request->get('world');
		$category = $request->get('category');
		$order = $request->get('order');

		if ($order != 'asc' && $order != 'dec') {
			$order = 'asc';
		}

		if ($category != 'any') {
			$avatars = AvatarData::where('nWorld', $world)->where('sCategory', $category)->orderBy('nRank', $order)->get();
		} else {
			$avatars = AvatarData::where('nWorld', $world)->orderBy('nOverallRank', $order)->get();
		}

		if (sizeof($avatars) < 1) {
			return $this->error("No characters found matching provided query.", 200);
		}
	
		$page = $request->get('page');
		$entries = array_chunk($avatars->toArray(), 5);
		$pages = sizeof($entries);
	
		if ($page > $pages) {
			return $this->error("Invalid ranking page.", 200);
		}

		$data = [];
		foreach ($entries[$page - 1] as $avatar) {
			$avatardata = AvatarData::find($avatar['dwCharacterID']);
			$avatardata->CharacterStat; // Load stats onto avatar object
			array_push($data, $avatardata);
		}
	
		return response()->json([
			'success' => true,
			'prev' => ($page - 1 > 0) ? ($page - 1) : 1,
			'current' => $page,
			'next' => ($page + 1 < $pages) ? ($page + 1) : $pages,
			'last' => $pages,
			'data' => $data
		], 200);
	}
}
