<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGDefaultThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>