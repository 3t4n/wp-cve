<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGTilesNestedOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>