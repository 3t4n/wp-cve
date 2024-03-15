<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGTilesColumnsOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>