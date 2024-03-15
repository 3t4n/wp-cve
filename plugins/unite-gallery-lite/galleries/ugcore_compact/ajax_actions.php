<?php
defined('UNITEGALLERY_INC') or die('Restricted access');

/**
 * gets $action, $data , and working framework, so globals and helper works
 * response from helperUG
 */

	switch($action){
		case "update_thumbpanel_defaults":
			UGCompactThemeHelper::updateThumbPanelDefaults($data);
			
			$urlRedirect = HelperGalleryUG::getUrlViewCurrentGallery();
			HelperUG::ajaxResponseSuccessRedirect("Position settings updated successfully", $urlRedirect);
		break;
		default:
			HelperUG::ajaxResponseError("wrong ajax action (Compact Theme): <b>$action</b> ");
		break;	
	}

?>