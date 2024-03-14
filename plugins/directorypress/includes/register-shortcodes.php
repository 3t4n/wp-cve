<?php 
/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes
 * @author     Designinvento <developers@designinvento.net>
 */
 
global $directorypress_object, $directorypress_notifications, $directorypress_shortcodes, $directorypress_shortcodes_init;
define('DIRECTORYPRESS_MAIN_SHORTCODE', 'directorypress-main');
define('DIRECTORYPRESS_LISTING_SHORTCODE', 'directorypress-listing');
$directorypress_shortcodes = array(
	DIRECTORYPRESS_MAIN_SHORTCODE => 'directorypress_directory_handler',
	DIRECTORYPRESS_LISTING_SHORTCODE => 'directorypress_directory_handler',
	'directorypress-listing' => 'directorypress_directory_handler',
	'directorypress-listings' => 'directorypress_listings_handler',
	//'directorypress-map' => 'directorypress_map_handler',
	'directorypress-categories' => 'directorypress_categories_handler',
	'directorypress-locations' => 'directorypress_locations_handler',
	'directorypress-search' => 'directorypress_search_handler',
);
$directorypress_shortcodes_init = array(
	DIRECTORYPRESS_MAIN_SHORTCODE => 'directorypress_directory_handler',
	DIRECTORYPRESS_LISTING_SHORTCODE => 'directorypress_directory_handler',
	'directorypress-listing' => 'directorypress_directory_handler',
	'directorypress-listings' => 'directorypress_listings_handler',
);