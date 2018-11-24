<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AvatarController extends Controller {
	
	public function __construct() {
		//Can add auth middleware here i guess
	}

	public function show($name) {
		
		$path = "../resources/avatar/characters/".$name.".png";
		$debug = false;
		$Image = new ImageController($debug);
		
		if(!file_exists($path) || $debug) {
			if($character = DB::table('avatar')->where('sCharacterName', $name)->first()) {
				$inventory = DB::table('avatarequip')->where('dwCharacterID', $character->dwCharacterID)->orderBy('nPos')->get();
				
				$variables = array("debug" => (bool)$debug, "gender" => $character->nGender, "job" => $character->nJob, "skin" => $character->nSkin, "hair" => $character->nHair, "face" => $character->nFace);

				foreach($inventory as $item) {
					switch($item->nPos) {
						case 1: case 101:$variables['cap']               = $item->nItemID;break;
						case 2: case 102:$variables['accessory']['face'] = $item->nItemID;break;
						case 3: case 103:$variables['accessory']['eyes'] = $item->nItemID;break;
						case 4: case 104:$variables['accessory']['ears'] = $item->nItemID;break;
						case 5: case 105:$variables['coat']              = $item->nItemID;break;
						case 6: case 106:$variables['pants']             = $item->nItemID;break;
						case 7: case 107:$variables['shoes']             = $item->nItemID;break;
						case 8: case 108:$variables['glove']             = $item->nItemID;break;
						case 9: case 109:$variables['cape']              = $item->nItemID;break;
						case 10:case 110:$variables['shield']            = $item->nItemID;break;
						case 11: $variables['weapon']['base']            = $item->nItemID;break;
						case 111:$variables['weapon']['cash']            = $item->nItemID;break;
					}
				}

				if ($debug) {
					return $Image->setConstants($variables)->debug();	
				}

				$Image->setConstants($variables)
				->lv2('weapon', 'characterEnd')
				->lv2('cape', '0')
				->lv1('cap', 'backHair')
				->lv2('cape', 'backWing')
				->lv1('cap', 'backHairOverCape')
				->lv1('cap', 'capBelowBody')
				->lv1('cap', 'capBelowHead')
				->lv2('weapon', 'weaponOverGloveBelowMailArm')
				->lv2('weapon', 'weaponBelowBody')
				->lv1('cap', 'capeBelowBody')
				->lv1('cap', 'backCap')
				->hair('hairBelowBody')
				->lv2('cape', 'capeBelowBody')
				->lv2('shoes', 'capAccessoryBelowBody')
				->lv1('cap', 'capAccessoryBelowBody')
				->lv1('weapon', 'capAccessoryBelowBody') // Cap
				->lv2('shield', 'shield')
				->lv2('shield', 'shieldBelowBody')
				->skin('body')
				->lv1('cap', 'body')
				->lv2('pants', 'pantsBelowShoes')
				->lv2('coat', 'pantsBelowShoes')
				->lv2('glove', 'gloveOverBody')
				->lv2('glove', 'gloveWristOverBody')
				->lv2('shield', 'gloveOverBody') // Weapon
				->lv2('coat', 'mailChestBelowPants')
				->lv2('shoes', 'shoes')
				->lv2('pants', 'pants')
				->lv2('coat', 'pants')
				->lv2('coat', 'mailArmOverHair')
				->lv2('shoes', 'shoesOverPants')
				->lv2('coat', 'pantsOverShoesBelowMailChest')
				->lv2('shoes', 'shoesTop')
				->lv2('coat', 'backMailChest')
				->lv2('pants', 'pantsOverShoesBelowMailChest')
				->lv2('coat', 'mailChest')
				->lv2('coat', 'mailChestOverPants')
				->lv2('pants', 'pantsOverMailChest')
				->lv2('coat', 'mailChestOverHighest')
				->lv2('shoes', 'pantsOverMailChest')
				->lv2('shoes', 'mailChestTop')
				->lv2('shoes', 'weaponOverBody')
				->lv2('coat', 'capeBelowBody')
				->lv2('coat', 'mailChestTop')
				->lv2('weapon', 'weaponOverArmBelowHead')
				->lv2('shield', 'weaponOverArmBelowHead') // Weapon
				->lv2('weapon', 'weapon')
				->lv2('weapon', 'armBelowHeadOverMailChest')
				->lv2('weapon', 'weaponOverBody')
				->skin('arm')
				->lv2('glove', 'gloveBelowMailArm')
				->lv2('glove', 'glove')
				->lv2('glove', 'gloveWrist')
				->lv2('coat', 'mailArm')
				->lv2('coat', 'capeBelowBody')
				->lv2('weapon', 'emotionOverBody')
				->skin('head')
				->lv2('cape', 'cape')
				->accessory('face', 'accessoryFaceBelowFace')
				->accessory('eyes', 'accessoryEyeBelowFace')
				->lv1('face', 'face')
				->accessory('face', 'accessoryFace')
				->accessory('face', 'accessoryFaceOverFaceBelowCap')
				->lv2('coat', 'accessoryFaceOverFaceBelowCap')
				->accessory('face', 'weaponBelowArm')
				->accessory('face', 'capeOverHead')
				->lv1('cap', 'capBelowAccessory')
				->lv1('cap', 'capAccessoryBelowAccFace')
				->accessory('eyes', 'accessoryEye')
				->accessory('ears', 'accessoryEar')
				->lv1('cap', 'accessoryEar')
				->hair('hair')
				->lv1('cap', 'cap')
				->lv1('weapon', 'cap') // Cap
				->skin('ear')
				->accessory('eyes', 'accessoryEyeOverCap')
				->lv1('cap', 'accessoryEyeOverCap')
				->hair('hairOverHead')
				->lv2('weapon', 'weaponOverArm')
				->lv2('weapon', 'weaponBelowArm')
				->lv2('weapon', 'weaponOverHand')
				->skin('hand')
				->lv2('glove', 'gloveOverHair')
				->lv2('glove', 'gloveWristOverHair')
				->lv2('weapon', 'weaponOverGlove')
				->lv2('weapon', 'weaponWristOverGlove')
				->lv2('cape', 'capeOverHead')
				->accessory('eyes', 'accessoryOverHair')
				->accessory('eyes', 'hairOverHead')
				->accessory('eyes', 'accessoryEarOverHair')
				->lv1('cap', 'capOverHair')
				->lv1('cap', '0')
				->accessory('ears', 'capOverHair')
				->lv2('cape', 'capeOverWepon')
				->lv2('cape', 'capOverHair')
				->createImage($name);
			} else {
				$path = "../resources/avatar/base.png";
			}
		}
			
		$headers = ['Content-Type' => 'image/png', 'Content-Disposition' => 'inline'];
		$response = new BinaryFileResponse($path, 200, $headers);
    	BinaryFileResponse::trustXSendfileTypeHeader();       
		return $response;
	}
	
}