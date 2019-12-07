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

use App\Rankings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller {

	public function __construct() {
		//Can add auth middleware here i guess
	}

	public function store(Request $request) {
		//Simplified access authorization
		if(!$request->user()->access_level >= 5) {
			return $this->error("Unauthorized.", 401);
		}

		$data = json_decode($request->getContent());
		if (count($data)) {
			foreach($data as $avatar) {
				DB::table('avatar')->where("dwCharacterID", dwCharacterID)->truncate();
				DB::table('avatarequip')->where("dwCharacterID", dwCharacterID)->truncate();
				DB::insert('insert into avatar (dwCharacterID, nAccountID, nWorld, nOverallRank, nOverallRankMove, nRank, nRankMove, nLevel, nJob, nExp64, nPop, nGender, nSkin, nHair, nFace, sCharacterName) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$avatar->dwCharacterID, $avatar->nAccountID, $avatar->nWorld, $avatar->nOverallRank, $avatar->nOverallRankMove, $avatar->nRank, $avatar->nRankMove, $avatar->nLevel, $avatar->nJob, $avatar->nExp64, $avatar->nPop, $avatar->nGender, $avatar->nSkin, $avatar->nHair, $avatar->nFace, $avatar->sCharacterName]);

				$equips = $avatar->aBody;
				if (count($equips)) {
					foreach($equips as $item) {
						DB::insert('insert into avatarequip (dwCharacterID, nPos, nItemId) values (?, ?, ?)', [$avatar->dwCharacterID, $item->nPos, $item->nItemID]);
					}
				}
			}
		}

		//truncate generated images
		$path = '../resources/avatar/characters/*';
		$files = glob($path);
		foreach($files as $file){
		  if(is_file($file))
			unlink($file);
		}

		return $this->success("Ranking entries have been created.", 201);
	}

	public function show() {
		$avatars = DB::table('avatar')->limit(5)->get();
		return $this->success($avatars, 200);
	}
}
