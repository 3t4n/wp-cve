<?php


defined('UNITEGALLERY_INC') or die('Restricted access');

	
	$output = new UGCompactThemeOutput();
	echo $output->putGallery(GlobalsUGGallery::$galleryID);

?>