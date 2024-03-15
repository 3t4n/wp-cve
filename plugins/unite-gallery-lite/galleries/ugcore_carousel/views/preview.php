<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGCarouselOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>