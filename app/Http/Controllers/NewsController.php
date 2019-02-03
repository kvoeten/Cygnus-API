<?php
namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller {

	public function __construct() {}

	public function index() {
		$posts = News::all();
		return $this->success($posts, 200);
	}

	public function page($page = 1) {
		$news = News::all();

		if (sizeof($news) < 1) {
			return $this->error("There aren't any news articles.", 404);
		}

		$posts = array_chunk($news->toArray(), 5);
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
		$post = Post::create([
					'title' => $request->get('title'),
					'content'=> $request->get('content'),
					'author' => $this->getUserId()
				]);
		return $this->success("The post with with id {$post->id} has been created", 201);
	}

	public function show($id) {
		$post = News::find($id);

		if(!$post){
			return $this->error("The news entry with {$id} doesn't exist", 404);
		}

		return $this->success($post, 200);
	}

	public function update(Request $request, $id){
		$post = Post::find($id);

		if(!$post){
			return $this->error("The post with {$id} doesn't exist", 404);
		}

		$this->validateRequest($request);
		$post->title 		  = $request->get('title');
		$post->content 		= $request->get('content');
		$post->author 		= $request->get('author');
		$post->save();
		return $this->success("The post with with id {$post->id} has been updated", 200);
	}

	public function destroy($id) {
		$post = Post::find($id);

		if(!$post){
			return $this->error("The post with {$id} doesn't exist", 404);
		}

		$post->delete();
		return $this->success("The post with with id {$id} has been deleted.", 200);
	}

	public function validateRequest(Request $request) {
		$rules = [
			'title' => 'required',
			'content' => 'required',
			'author' => 'required',
		];
		$this->validate($request, $rules);
	}

	public function isAuthorized(Request $request) {
		$resource = "posts";
		$post     = News::find($this->getArgs($request)["post_id"]);
		return $this->authorizeUser($request, $resource, $post);
	}
}
