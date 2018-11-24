<?php 
namespace App\Http\Controllers;

use App\Rankings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller {
	
	public function __construct() {
		//Can add auth middleware here i guess
	}
	
	//Requires token of client ID 100, which is center.
	public function store(Request $request) {
		
		//if($request->client()->id != 100) {
		//	return $this->error(["Unauthorized"], 404);
		//}
		
		$activation = DB::table('avatar')->truncate();
		$activation = DB::table('avatarequip')->truncate();
		$data = json_decode($request->getContent());
		if (count($data)) {
			foreach($data as $avatar) {
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
		$avatars = DB::table('avatar')->get();
		return $this->success($avatars, 200);
	}
}