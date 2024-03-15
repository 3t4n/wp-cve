<?php


defined('UNITEGALLERY_INC') or die('Restricted access');


$settings = new UniteGallerySettingsUG();
$settings->loadXMLFile(GlobalsUG::$pathHelpersSettings."thumbnail.xml");

$settings->updateSelectToEasing("thumb_transition_easing");

//add thumbnail size select

	if(method_exists("UniteProviderFunctionsUG", "getThumbSizesSmall")){
		$arrSizesSmall = UniteProviderFunctionsUG::getThumbSizesSmall($settings);
		
		$params = array();
		$params["description"] = __("Choose system resolution for the thumbs, note that some of it can be cropped", "unitegallery");
		$params[UniteSettingsUG::PARAM_ADD_SETTING_AFTER] = "thumb_height";
		
		$settings->addSelect("thumb_resolution", $arrSizesSmall,  __("Thumb Resolution", "unitegallery"),"medium", $params);
		
	}

	
	
?>