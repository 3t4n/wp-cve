<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

		
	$output = new UGVideoThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>