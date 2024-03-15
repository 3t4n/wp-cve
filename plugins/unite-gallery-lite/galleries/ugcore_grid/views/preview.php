<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGGridThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>