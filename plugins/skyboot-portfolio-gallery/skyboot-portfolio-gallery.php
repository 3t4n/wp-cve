<?php 
/*
Plugin Name: Skyboot Elementor Gallery Plugin - Portfolio Image Gallery Widget - Elementor Image Gallery Plugin
Plugin URI:   http://skybootstrap.com/portfolio-gallery
Description:  The Skyboot Elementor Image Gallery plugin is a portfolio gallery, filterable gallery, elementor gallery plugin, filter gallery elementor, elementor filterable gallery,gallery elementor,elementor masonry gallery,elementor photo gallery,elementor gallery widget, and masonry image gallery widget for Elementor Page Builder that makes it easy to showcase any number of portfolio and allow the user to easily create a image gallery section in a minute.
Version:      1.0.3
Author:       skybootstrap
Author URI:   http://skybootstrap.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  skyboot-pg
*/
if( !defined('ABSPATH') ) exit;

// Skyboot Portfolio Gallery Version
define( 'SKYBOOT_PORTFOLIO_GALLERY_VERSION', '1.0.3' );

// Plugins URL
define( 'SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_URL', plugins_url('', __FILE__) );

// Includes Directory 
define( 'SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY', dirname( __FILE__ ) ); 


// Include File
if ( !file_exists('file-list.php') ){
	include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/file-list.php');
}
