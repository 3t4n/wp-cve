<?php
/************************************************************
* Plugin Name:			Events Manager - OpenStreetMaps
* Description:			OpenStreetMap replacement for Events Manager. 0% Google, 100% Open Source.
* Version:				4.2.1
* Author:  				Stonehenge Creations
* Author URI: 			https://www.stonehengecreations.nl/
* Plugin URI: 			https://www.stonehengecreations.nl/creations/stonehenge-em-osm/
* License URI: 			https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: 			stonehenge-em-osm
* Domain Path: 			/languages
* Requires at least: 	5.4
* Tested up to: 		6.0
* Requires PHP:			7.3
* Network:				false
************************************************************/

if( !defined('ABSPATH') ) exit;
include_once(ABSPATH.'wp-admin/includes/plugin.php');


#===============================================
function stonehenge_em_osm() {
	$wp 	= get_plugin_data( __FILE__ );
	$plugin = array(
		'name' 		=> $wp['Name'],
		'short' 	=> 'EM - OpenStreetMap',
		'icon' 		=> '&#x1F5FA;',
		'slug' 		=> 'stonehenge_em_osm',
		'version' 	=> $wp['Version'],
		'text' 		=> $wp['TextDomain'],
		'class' 	=> 'Stonehenge_EM_OSM',
		'base' 		=> plugin_basename(__DIR__),
		'prio' 		=> 40,
	);
	$plugin['url'] 		= admin_url().'admin.php?page='.$plugin['slug'];
	$plugin['options'] 	= get_option( $plugin['slug'] );
	return $plugin;
}


#===============================================
add_action('plugins_loaded', function() {
	if( !function_exists('stonehenge') ) { require_once('stonehenge/init.php'); }

	$plugin = stonehenge_em_osm();
	if( start_stonehenge($plugin) ) {
		include('classes/class-functions.php');
		include('classes/class-admin.php');
		include('classes/class-metabox.php');
		include('classes/class-customize.php');
		include('classes/class-maps.php');
		include('classes/class-init.php');
	}
}, 25);


#===============================================
function osm_exclude_from_autoptimize( $exclude ) {
	return $exclude . ", wp-content/plugins/stonehenge-em-osm/";
}
add_filter('autoptimize_filter_js_exclude', 'osm_exclude_from_autoptimize', 15, 1);
add_filter('autoptimize_filter_css_exclude', 'osm_exclude_from_autoptimize', 15, 1);

