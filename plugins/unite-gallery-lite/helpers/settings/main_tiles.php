<?php

defined('UNITEGALLERY_INC') or die('Restricted access');


	if(!isset($isNewGallery))
		$isNewGallery = false;

	$settingsMain = new UniteGallerySettingsUG();
	$settingsMain->addTextBox("title", "",__("Gallery Title","unitegallery"),array("description"=>__("The title of the gallery. Example: Gallery1","unitegallery"),"required"=>"true"));
	$settingsMain->addTextBox("alias", "",__("Gallery Alias","unitegallery"),array("description"=>__("The alias that will be used for embedding the gallery. Example: gallery1","unitegallery"),"required"=>"true", "class"=>"input-alias"));
	
	if($isNewGallery == false){
		$settingsMain = UniteProviderFunctionsUG::addPositionToMainSettings($settingsMain);
	
		//add gallery theme settings
		$textChangeTheme = __("Change","unitegallery");
		$params = array("class"=>"small","readonly"=>"true",
				UniteGallerySettingsUG::PARAM_ADDTEXT=>"<a id='ug_button_change_theme' href='javascript:void(0)' class='unite-button-secondary'>$textChangeTheme</a>");
		
		$params[UniteSettingsUG::PARAM_NOSETVAL] = true;
		
		$galleryTypeTitle = GlobalsUGGallery::$gallery->getTypeTitle();
		
		$settingsMain->addTextbox("gallery_theme_info", $galleryTypeTitle, "Gallery Theme",$params);
		
	}
	
	$settingsMain->addHr();
	
	$settingsMain->addItemsCategorySelect("category", null, $isNewGallery);
	
	if ( $isNewGallery == false ) {
		$settingsMain->addHr();
		$settingsMain->addRadioBoolean("enable_category_tabs", __("Enable Category Tabs", "unitegallery"), false);
		
		$settingsMain->addControl("enable_category_tabs", "category", "enable", "false");
	}
	
	$settingsMain->addHr();
	
	$params = array("class"=>"input-number","unit"=>"px");
	
	$settingsMain->addRadioBoolean("full_width", "Full Width", true);
	
	
	$settingsMain->addTextbox("gallery_width", "1000", __("Width", "unitegallery"),$params);	
	
	$settingsMain->addControl("full_width", "gallery_width", "hide", "true");
	
	//in case of existing gallery
	if($isNewGallery == false){
		$settingsMain->addHr();
		
		$settingsMain->addTextbox("gallery_min_width", "150", __("Min. Width", "unitegallery"),$params);
	}


?>