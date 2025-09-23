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

	/**
	 * @OA\Get(
	 *     path="/ranking/top",
	 *     summary="Get top 3 overall ranked characters",
	 *     tags={"Ranking"},
	 *     @OA\Response(
	 *         response=200,
	 *         description="An array of the top 3 characters."
	 *     )
	 * )
	 */
	public function top() {
		$avatars = AvatarData::orderBy('nOverallRank', 'asc')->limit(3)->get();
		if (sizeof($avatars) < 1) {
			return $this->error("No characters found.", 404);
		}
		foreach ($avatars as $avatar) {
			$avatar->CharacterStat; // Load stats onto avatar object
		}
		return $this->success($avatars, 200);
	}

	/**
	 * @OA\Get(
	 *     path="/ranking",
	 *     summary="Get character rankings with filters",
	 *     tags={"Ranking"},
	 *     @OA\Parameter(
	 *         name="page",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(type="integer", default=1)
	 *     ),
	 *     @OA\Parameter(
	 *         name="order",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
	 *     ),
	 *      @OA\Parameter(
	 *         name="category",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(type="string", default="any", description="Job category or 'any' for overall.")
	 *     ),
	 *      @OA\Parameter(
	 *         name="world",
	 *         in="query",
	 *         required=true,
	 *         @OA\Schema(type="integer", default=0)
	 *     ),
	 *     @OA\Response(response=200, description="A paginated list of characters based on ranking."),
	 *     @OA\Response(response=422, description="Validation error.")
	 * )
	 */
	public function find(Request $request) {
		$this->validateRequest($request);
		
		$world = $request->get('world');
		$category = $request->get('category');
		$order = $request->get('order');

		if (!in_array(strtolower($order), ['asc', 'desc'])) {
			$order = 'asc';
		}
		
		/*
		if ($category != 'any') {
			$avatars = AvatarData::where('nWorld', $world)->where('sCategory', $category)->orderBy('nRank', $order)->get();
		} else {
			$avatars = AvatarData::where('nWorld', $world)->orderBy('nOverallRank', $order)->get();
		}

		if (sizeof($avatars) < 1) {
			return $this->error("No characters found matching provided query.", 404);
		}
	
		$page = $request->get('page');
		$entries = array_chunk($avatars->toArray(), 5);
		$pages = sizeof($entries);
		*/

		$query = AvatarData::with('CharacterStat')->where('nWorld', $world);

        if ($category !== 'any') {
            $query->where('sCategory', $category)->orderBy('nRank', $order);
        } else {
            $query->orderBy('nOverallRank', $order);
        }

		// Use database pagination. This avoids N+1 issues and in-memory processing.
        $paginatedAvatars = $query->paginate(5, ['*'], 'page', $request->get('page'));

		return $this->success($paginatedAvatars, 200);
	}

    /**
     * @OA\Post(
     *     path="/ranking",
     *     summary="Update ranking data (from game server)",
     *     description="This endpoint is used by the game server to push updated ranking information.",
     *     tags={"Ranking"},
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         description="Ranking data to update.",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             description="The ranking data structure."
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ranking updated successfully."),
     *     @OA\Response(response=401, description="Unauthorized.")
     * )
     */
    public function store(Request $request)
    {
        if (!$request->user() || $request->user()->access_level < 5) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        // TODO: Implement the logic to process and store ranking data.
        return $this->success("Ranking data received.", 200);
    }
}
