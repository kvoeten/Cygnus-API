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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WzController extends Controller {

	public function __construct() {}

	public function map($id) {
		$wz = "../resources/Map.wz/".$id.".json";
		$info = "../resources/String.wz/map/".$id.".json";

		if (!file_exists($wz)) {
			return $this->error("Map not found.", 404);
		}

		$string = null;
		if (file_exists($info)) {
			$string = json_decode(file_get_contents($info));
		}

		$map = json_decode(file_get_contents($wz));
		return $this->success([
			'info' => $string,
			'wz' => $map
		], 200);
	}

	public function npc($id) {
		$wz = "../resources/Npc.wz/".$id.".json";
		$info = "../resources/String.wz/npc/".$id.".json";

		if (!file_exists($wz)) {
			return $this->error("Npc not found.", 404);
		}

		$string = null;
		if (file_exists($info)) {
			$string = json_decode(file_get_contents($info));
		}

		$npc = json_decode(file_get_contents($wz));
		return $this->success([
			'info' => $string,
			'wz' => $npc
		], 200);
	}

	public function item($id) {
		$wz = "../resources/Item.wz/".$id.".json";
		$info = "../resources/String.wz/item/".$id.".json";

		if (!file_exists($wz)) {
			return $this->error("Item not found.", 404);
		}

		$string = null;
		if (file_exists($info)) {
			$string = json_decode(file_get_contents($info));
		}

		$item = json_decode(file_get_contents($wz));
		return $this->success([
			'info' => $string,
			'wz' => $item
		], 200);
	}

	public function mob($id) {
		$wz = "../resources/Mob.wz/".$id.".json";
		$info = "../resources/String.wz/mob/".$id.".json";

		if (!file_exists($wz)) {
			$wz = "../resources/Mob2.wz/".$id.".json";
			if (!file_exists($wz)) {
				return $this->error("Mob not found.", 404);
			}
		}

		$string = null;
		if (file_exists($info)) {
			$string = json_decode(file_get_contents($info));
		}

		$mob = json_decode(file_get_contents($wz));
		return $this->success([
			'info' => $string,
			'wz' => $mob
		], 200);
	}

	public function showMob($id) {
		$path = "../resources/Mob.wz/".$id;
		if (!file_exists($path.".json")) {
			$path = "../resources/Mob2.wz/".$id;
			if (!file_exists($path.".json")) {
				return $this->error("Mob not found.", 404);
			}
		}
		
		if (!file_exists($path.".png")) {
			$mob = json_decode(file_get_contents($path.".json"));
			$image = imagecreatefromstring(base64_decode($mob->icon));
			$black = imagecolorallocate($image, 0, 0, 0);
			imagecolortransparent($image, $black);
			imagepng($image, $path.".png");
		}
		
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$response = new BinaryFileResponse($path.".png", 200, $headers);
		BinaryFileResponse::trustXSendfileTypeHeader();
		return $response;
	}

	public function showItem($id) {
		$path = "../resources/Item.wz/".$id;
		if (!file_exists($path.".json")) {
		  return $this->error("Error. Item not found.", 404);
		}
		
		if (!file_exists($path.".png")) {
			$map = json_decode(file_get_contents($path.".json"));
			$image = imagecreatefromstring(base64_decode($map->icon));
			$black = imagecolorallocate($image, 0, 0, 0);
			imagecolortransparent($image, $black);
			imagepng($image, $path.".png");
		}
		
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$response = new BinaryFileResponse($path.".png", 200, $headers);
		BinaryFileResponse::trustXSendfileTypeHeader();
		return $response;
	}

	public function showNpc($id) {
		$path = "../resources/Npc.wz/".$id;
		if (!file_exists($path.".json")) {
			return $this->error("Error. Npc not found.", 404);
		}
		
		if (!file_exists($path.".png")) {
			$npc = json_decode(file_get_contents($path.".json"));
			$image = imagecreatefromstring(base64_decode($npc->icon));
			$black = imagecolorallocate($image, 0, 0, 0);
			imagecolortransparent($image, $black);
			imagepng($image, $path.".png");
		}
		
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$response = new BinaryFileResponse($path.".png", 200, $headers);
		BinaryFileResponse::trustXSendfileTypeHeader();
		return $response;
	}

	public function showMap($id) {
		$path = "../resources/Map.wz/".$id;
		if (!file_exists($path.".json")) {
			return $this->error("Error. Map not found.", 404);
		}
		
		if (!file_exists($path.".png")) {
			$map = json_decode(file_get_contents($path.".json"));
			$image = imagecreatefromstring(base64_decode($map->icon));
			$black = imagecolorallocate($image, 0, 0, 0);
			imagecolortransparent($image, $black);
			imagepng($image, $path.".png");
		}
		
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$response = new BinaryFileResponse($path.".png", 200, $headers);
		BinaryFileResponse::trustXSendfileTypeHeader();
		return $response;
	}

	//For this to work you'll have to put strings in DB.
	public function find(Request $request) {
		$this->validateRequest($request);
		$cache = DB::table('wz')->count();
		$results = DB::table('wz')->where([
			['name', 'like', '%'.$request->get('name').'%'],
			['type', $request->get('type')]
			])->limit(5)->get();

		if(count($results) == 0) {
			return $this->error("Nothing found satisfying the search parameters.", 404);
		} else {
			return $this->success($results, 200);
		}
	}

	public function validateRequest(Request $request) {
		$rules = [
			'name' => 'required',
			'type' => 'required'
		];
		$this->validate($request, $rules);
	}
}
