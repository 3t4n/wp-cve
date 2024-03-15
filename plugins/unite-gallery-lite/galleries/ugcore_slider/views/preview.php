<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGSliderThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>