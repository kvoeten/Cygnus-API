<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
	
class AssetController extends Controller {
	
	public function show($name) {
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$path = "../resources/images/".$name;
		$response = new BinaryFileResponse($path, 200, $headers);
    	BinaryFileResponse::trustXSendfileTypeHeader();       
		return $response;
	}
	
}