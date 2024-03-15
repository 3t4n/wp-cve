<?php

defined('UNITEGALLERY_INC') or die('Restricted access');


	$settingsMain = new UniteGallerySettingsUG();

	$params = array();
	$params["style"] = "height:200px;";
	$params["description"] = "The additional scripts will be added to the javascript gallery output on the page. Special tag [api] will replace gallery API variable";
	
	$settingsMain->addTextArea("ug_additional_scripts", "", __("Additional Scripts <br>( Javascript )", "unitegallery"), $params);
	
	$settingsMain->addHr();
	
	$params["description"] = "The additional styles will be added befor gallery include. Special tag [galleryid] will replace be replaced by current gallery ID.";
	
	$settingsMain->addTextArea("ug_additional_styles", "", __("Additional Styles <br> ( CSS )", "unitegallery"), $params);
	
	
	
?>