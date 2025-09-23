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

	/**
	 * @OA\Get(
	 *     path="/map/{id}",
	 *     summary="Get WZ data for a map",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the map.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="WZ data and string info for the map."
	 *     ),
	 *     @OA\Response(response=404, description="Map not found.")
	 * )
	 */
	public function map($id) {
		$wz = "../resources/wz/Map.wz/".$id.".json";
		$info = "../resources/wz/String.wz/map/".$id.".json";

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

	/**
	 * @OA\Get(
	 *     path="/npc/{id}",
	 *     summary="Get WZ data for an NPC",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the NPC.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="WZ data and string info for the NPC."
	 *     ),
	 *     @OA\Response(response=404, description="NPC not found.")
	 * )
	 */
	public function npc($id) {
		$wz = "../resources/wz/Npc.wz/".$id.".json";
		$info = "../resources/wz/String.wz/npc/".$id.".json";

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

	/**
	 * @OA\Get(
	 *     path="/item/{id}",
	 *     summary="Get WZ data for an item",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the item.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="WZ data and string info for the item."
	 *     ),
	 *     @OA\Response(response=404, description="Item not found.")
	 * )
	 */
	public function item($id) {
		$wz = "../resources/wz/Item.wz/".$id.".json";
		$info = "../resources/wz/String.wz/item/".$id.".json";

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

	/**
	 * @OA\Get(
	 *     path="/mob/{id}",
	 *     summary="Get WZ data for a mob",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the mob.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="WZ data and string info for the mob."
	 *     ),
	 *     @OA\Response(response=404, description="Mob not found.")
	 * )
	 */
	public function mob($id) {
		$wz = "../resources/wz/Mob.wz/".$id.".json";
		$info = "../resources/wz/String.wz/mob/".$id.".json";

		if (!file_exists($wz)) {
			$wz = "../resources/wz/Mob2.wz/".$id.".json";
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

	/**
	 * @OA\Get(
	 *     path="/mob/image/{id}",
	 *     summary="Get the image for a mob",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the mob.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The mob image.",
	 *         @OA\MediaType(mediaType="image/png")
	 *     ),
	 *     @OA\Response(response=404, description="Mob not found.")
	 * )
	 */
	public function showMob($id) {
		$path = "../resources/wz/Mob.wz/".$id;
		if (!file_exists($path.".json")) {
			$path = "../resources/wz/Mob2.wz/".$id;
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

	/**
	 * @OA\Get(
	 *     path="/item/image/{id}",
	 *     summary="Get the image for an item",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the item.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The item image.",
	 *         @OA\MediaType(mediaType="image/png")
	 *     ),
	 *     @OA\Response(response=404, description="Item not found.")
	 * )
	 */
	public function showItem($id) {
		$path = "../resources/wz/Item.wz/".$id;
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

	/**
	 * @OA\Get(
	 *     path="/npc/image/{id}",
	 *     summary="Get the image for an NPC",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the NPC.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The NPC image.",
	 *         @OA\MediaType(mediaType="image/png")
	 *     ),
	 *     @OA\Response(response=404, description="NPC not found.")
	 * )
	 */
	public function showNpc($id) {
		$path = "../resources/wz/Npc.wz/".$id;
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

	/**
	 * @OA\Get(
	 *     path="/map/image/{id}",
	 *     summary="Get the image for a map",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the map.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The map image.",
	 *         @OA\MediaType(mediaType="image/png")
	 *     ),
	 *     @OA\Response(response=404, description="Map not found.")
	 * )
	 */
	public function showMap($id) {
		$path = "../resources/wz/Map.wz/".$id;
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
	/**
	 * @OA\Get(
	 *     path="/search",
	 *     summary="Search for WZ data by name and type",
	 *     tags={"WZ"},
	 *     @OA\Parameter(
	 *         name="name",
	 *         in="query",
	 *         required=true,
	 *         description="The name to search for.",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *         name="type",
	 *         in="query",
	 *         required=true,
	 *         description="The type of WZ data to search (e.g., 'item', 'mob').",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(response=200, description="A list of matching WZ entries."),
	 *     @OA\Response(response=404, description="Nothing found.")
	 * )
	 */
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
