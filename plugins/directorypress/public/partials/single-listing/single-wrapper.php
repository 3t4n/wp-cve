<?php
global $DIRECTORYPRESS_ADIMN_SETTINGS;

if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] != 'default'){
	$template = 'partials/single-listing/single-'.$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'].'.php';
}else{
	$template = 'partials/single-listing/single.php';
}
echo directorypress_display_template($template, array('public_handler' => $public_handler));