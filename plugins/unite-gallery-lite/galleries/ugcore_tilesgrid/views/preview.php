<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGTilesGridOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>