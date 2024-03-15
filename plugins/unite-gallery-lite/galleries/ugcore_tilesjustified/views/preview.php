<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGTilesJustifiedOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>