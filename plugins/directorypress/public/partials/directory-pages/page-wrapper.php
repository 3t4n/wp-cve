<?php
global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
if ($directorypress_object->action == 'search') {
			
	if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] != 1){
		$template = 'partials/directory-pages/search-page-'. $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] .'.php';
	}else{
		$template = 'partials/directory-pages/search-page.php';
	}
} elseif (get_query_var('category-directorypress')) {
			
	if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] != 1){
		$template = 'partials/directory-pages/category-page-'. $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] .'.php';
	}else{
		$template = 'partials/directory-pages/category-page.php';
	}
} elseif (get_query_var('location-directorypress')) {
	if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] != 1){
		$template = 'partials/directory-pages/location-page-'. $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] .'.php';
	}else{
		$template = 'partials/directory-pages/location-page.php';
	}
} elseif (get_query_var('tag-directorypress')) {
	if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] != 1){
		$template = 'partials/directory-pages/tags-page-'. $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] .'.php';
	}else{
		$template = 'partials/directory-pages/tags-page.php';
	}
} elseif ($directorypress_object->action == 'myfavourites') {
	
	$template = 'partials/directory-pages/favourites-page.php';
	
} elseif (!$directorypress_object->action) {
	if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style']) && $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] != 1){
		$template = 'partials/directory-pages/index-page-'. $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] .'.php';
	}else{
		$template = 'partials/directory-pages/index-page.php';
	}
}

echo directorypress_display_template($template, array('public_handler' => $public_handler));