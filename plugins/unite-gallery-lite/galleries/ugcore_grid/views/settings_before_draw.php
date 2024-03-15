<?php


defined('UNITEGALLERY_INC') or die('Restricted access');


	//set panel position from get
	if(empty($galleryID)){
		$getPanelPos = UniteFunctionsUG::getGetVar("thumbpos","bottom");
		$settingsParams->updateSettingValue("theme_panel_position", $getPanelPos);
	}
	
	HelperGalleryUG::addJsText("changedefauts_confirm", __("Do you sure to set the theme panel position defaults?", "unitegallery"));
	HelperGalleryUG::addJsText("changedefauts_success", __("The default settings has been succssfully changed.", "unitegallery"));
	HelperGalleryUG::addJsText("changedefauts_template", __("Set [pos] Defaults.", "unitegallery"));
	
	$panelPosition = $settingsParams->getSettingValue("theme_panel_position", "bottom");

	$posName = ucfirst($panelPosition);
	$settingsParams->updateSettingValue("theme_button_set_defaults", "Set {$posName} Defaults");
	
	
?>