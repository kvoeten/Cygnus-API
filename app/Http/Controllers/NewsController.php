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

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller {

	public function __construct() {}

	/**
	 * @OA\Get(
	 *     path="/news/{page}",
	 *     summary="Get a paginated list of news articles",
	 *     tags={"News"},
	 *     @OA\Parameter(
	 *         name="page",
	 *         in="path",
	 *         required=false,
	 *         description="The page number to retrieve.",
	 *         @OA\Schema(type="integer", default=1)
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="A paginated list of news articles."
	 *     ),
	 *     @OA\Response(response=404, description="No news articles found.")
	 * )
	 */
	public function page($page = 1) {
		// Use database pagination instead of fetching all results.
		// This is much more efficient.
		$paginatedNews = News::orderBy('created_at', 'desc')->paginate(8, ['*'], 'page', $page);

		if ($paginatedNews->isEmpty()) {
			return $this->error("There aren't any news articles.", 404);
		}

		// The paginate() method returns a LengthAwarePaginator instance which is JSON serializable
		// and includes all pagination meta data automatically.
		return $this->success($paginatedNews, 200);
	}

	/**
	 * @OA\Post(
	 *     path="/article",
	 *     summary="Create a new news article",
	 *     tags={"News"},
	 *     security={{"passport": {}}},
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="Data for the new article.",
	 *         @OA\JsonContent(
	 *             required={"title", "description", "category", "content"},
	 *             @OA\Property(property="title", type="string", example="Server Maintenance"),
	 *             @OA\Property(property="description", type="string", example="Scheduled maintenance for next week."),
	 *             @OA\Property(property="category", type="string", example="Updates"),
	 *             @OA\Property(property="content", type="string", example="<p>Full article content here.</p>")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=201,
	 *         description="Article created successfully."
	 *     ),
	 *     @OA\Response(response=401, description="Unauthorized."),
	 *     @OA\Response(response=422, description="Validation error.")
	 * )
	 */
	public function store(Request $request) {
		$this->validateRequest($request);
		
		//Simplified access authorization
		if($request->user()->gradecode < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::create([
			'title' => $request->get('title'),
			'content'=> $request->get('content'),
			'category' => $request->get('category'),
			'description' => $request->get('description'),
			'author' => $request->user()->name
		]);
		
		if ($post) {
			return $this->success("The post with with id {$post->id} has been created", 201);
		} else {
			return $this->error("Unable to save post", 500);
		}
	}

	/**
	 * @OA\Get(
	 *     path="/article/{id}",
	 *     summary="Get a single news article by ID",
	 *     tags={"News"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the news article.",
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="The requested news article."
	 *     ),
	 *     @OA\Response(response=404, description="Article not found.")
	 * )
	 */
	public function show($id) {
		$post = News::find($id);

		if(!$post){
			return $this->error("The news entry with id {$id} doesn't exist", 404);
		}

		return $this->success($post, 200);
	}

	/**
	 * @OA\Put(
	 *     path="/article/{id}",
	 *     summary="Update an existing news article",
	 *     tags={"News"},
	 *     security={{"passport": {}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the article to update.",
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         description="New data for the article.",
	 *         @OA\JsonContent(
	 *             required={"title", "description", "category", "content"},
	 *             @OA\Property(property="title", type="string", example="Updated Title"),
	 *             @OA\Property(property="description", type="string", example="Updated description."),
	 *             @OA\Property(property="category", type="string", example="Events"),
	 *             @OA\Property(property="content", type="string", example="<p>Updated content.</p>")
	 *         )
	 *     ),
	 *     @OA\Response(response=200, description="Article updated successfully."),
	 *     @OA\Response(response=401, description="Unauthorized."),
	 *     @OA\Response(response=404, description="Article not found."),
	 *     @OA\Response(response=422, description="Validation error.")
	 * )
	 */
	public function update(Request $request, $id){
		//Simplified access authorization
		if($request->user()->gradecode < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::find($id);

		if(!$post){
			return $this->error("The post with id {$id} doesn't exist", 404);
		}

		$this->validateRequest($request);
		$post->title 		= $request->get('title');
		$post->content 		= $request->get('content');
		$post->category		= $request->get('category');
		$post->save();
		return $this->success("The post with with id {$post->id} has been updated", 200);
	}

	/**
	 * @OA\Delete(
	 *     path="/article/{id}",
	 *     summary="Delete a news article",
	 *     tags={"News"},
	 *     security={{"passport": {}}},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="The ID of the article to delete.",
	 *         @OA\Schema(type="integer")
	 *     ),
	 *     @OA\Response(response=200, description="Article deleted successfully."),
	 *     @OA\Response(response=401, description="Unauthorized."),
	 *     @OA\Response(response=404, description="Article not found.")
	 * )
	 */
	public function destroy(Request $request, $id) {
		//Simplified access authorization
		if($request->user()->gradecode < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::find($id);

		if(!$post){
			return $this->error("The post with id {$id} doesn't exist", 404);
		}

		$post->delete();
		return $this->success("The post with with id {$id} has been deleted.", 200);
	}

	public function validateRequest(Request $request) {
		$rules = [
			'title' => 'required',
			'description' => 'required',
			'category' => 'required',
			'content' => 'required',
		];
		$this->validate($request, $rules);
	}
}
