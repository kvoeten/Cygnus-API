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

	public function page($page = 1) {
		$news = News::orderBy('created_at', 'desc')->get();

		if (sizeof($news) < 1) {
			return $this->error("There aren't any news articles.", 404);
		}

		$posts = array_chunk($news->toArray(), 10);
		$pages = sizeof($posts);

		if ($page > $pages) {
			return $this->error("Invalid news page.", 404);
		}

		return response()->json([
			'success' => true,
			'prev' => ($page - 1 > 0) ? ($page - 1) : 1,
			'current' => $page,
			'next' => ($page + 1 < $pages) ? ($page + 1) : $pages,
			'last' => $pages,
			'data' => $posts[$page - 1]
		], 200);
	}

	public function store(Request $request) {
		$this->validateRequest($request);
		
		//Simplified access authorization
		if($request->user()->access_level < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::create([
					'title' => $request->get('title'),
					'content'=> $request->get('content'),
					'type' => $request->get('type'),
					'author' => $request->user()->name
				]);
		
		return $this->success("The post with with id {$post->id} has been created", 201);
	}

	public function show($id) {
		$post = News::find($id);

		if(!$post){
			return $this->error("The news entry with {$id} doesn't exist", 200);
		}

		return $this->success($post, 200);
	}

	public function update(Request $request, $id){
		//Simplified access authorization
		if($request->user()->access_level < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::find($id);

		if(!$post){
			return $this->error("The post with id {$id} doesn't exist", 200);
		}

		$this->validateRequest($request);
		$post->title 		= $request->get('title');
		$post->content 		= $request->get('content');
		$post->type 		= $request->get('type');
		$post->save();
		return $this->success("The post with with id {$post->id} has been updated", 200);
	}

	public function destroy(Request $request, $id) {
		//Simplified access authorization
		if($request->user()->access_level < 2) {
			return $this->error("Unauthorized.", 401);
		}
		
		$post = News::find($id);

		if(!$post){
			return $this->error("The post with id {$id} doesn't exist", 200);
		}

		$post->delete();
		return $this->success("The post with with id {$id} has been deleted.", 200);
	}

	public function validateRequest(Request $request) {
		$rules = [
			'title' => 'required',
			'content' => 'required',
			'type' => 'required',
		];
		$this->validate($request, $rules);
	}
}
