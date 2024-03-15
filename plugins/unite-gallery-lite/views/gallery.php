<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

	$pathViewSettings = HelperGalleryUG::getPathView("settings", false);
	
	if(file_exists($pathViewSettings))	
		require $pathViewSettings;
	else
		require HelperGalleryUG::getPathViewHelper("settings_common");

?>