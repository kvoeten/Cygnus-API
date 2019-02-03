<?php
namespace App\Http\Controllers;

use App\User;
use App\Activation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WzController extends Controller {

	public function __construct() {}

  public function map($id) {
    $path = "../resources/Map.wz/".$id.".json";
    if (!file_exists($path)) {
      return $this->error("Error. Map not found.", 404);
    }
    return $this->success(readfile($path), 200);
  }

  public function npc($id) {
    $path = "../resources/Npc.wz/".$id.".json";
    if (!file_exists($path)) {
      return $this->error("Error. Npc not found.", 404);
    }
    return $this->success(readfile($path), 200);
  }

  public function item($id) {
    $path = "../resources/Item.wz/".$id.".json";
    if (!file_exists($path)) {
      return $this->error("Error. Item not found.", 404);
    }
    return $this->success(readfile($path), 200);
  }

  public function mob($id) {
    $path = "../resources/Mob.wz/".$id.".json";
    if (!file_exists($path)) {
      return $this->error("Error. Mob not found.", 404);
    }
    return $this->success(readfile($path), 200);
  }

  public function showMob($id) {
    $path = "../resources/Mob.wz/".$id;
    if (!file_exists($path.".json")) {
      return $this->error("Error. Mob not found.", 404);
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

	public function find(Request $request) {
		$this->validateRequest($request);
    //TODO: search through list WZ.
	}

	public function validateRequest(Request $request) {
		$rules = [
			'name' => 'required',
      'type' => 'required'
		];
		$this->validate($request, $rules);
	}
}
