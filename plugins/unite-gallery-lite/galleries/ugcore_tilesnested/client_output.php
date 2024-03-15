<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

/**
 * passed $arrOptions to this file
 */
	
	$output = new UGTilesNestedOutput();
	
	$uniteGalleryOutput = $output->putGallery(GlobalsUGGallery::$galleryID, $arrOptions);
?>