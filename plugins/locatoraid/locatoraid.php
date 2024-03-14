<?php
/*
Plugin Name: Locatoraid
Plugin URI: https://www.locatoraid.com/
Description: Store locator plugin
Version: 3.9.37
Author: plainware.com
Author URI: https://www.locatoraid.com/
Text Domain: locatoraid
Domain Path: /languages/
*/

if( ! defined('LC3_VERSION') ){
	define( 'LC3_VERSION', 3937 );
}

if (! defined('ABSPATH')) exit; // Exit if accessed directly

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>".__('Locatoraid requires PHP 5.3 to function properly. Please upgrade PHP or deactivate Locatoraid.', 'locatoraid') ."</p></div>';" ) );
	return;
}

if( file_exists(dirname(__FILE__) . '/db.php') ){
	$nts_no_db = TRUE;
	include_once( dirname(__FILE__) . '/db.php' );
	$happ_path = NTS_DEVELOPMENT2;
}
else {
	$happ_path = dirname(__FILE__) . '/happ2';
}

include_once( $happ_path . '/lib-wp/hcWpBase6.php' );

class Locatoraid extends hcWpBase6
{
	public function __construct()
	{
		parent::__construct(
			array('locatoraid', 'lc'),	// app
			__FILE__,	// path,
			'',			// hc product,
			'locatoraid',	// slug
			'lctr2'		// db prefix
			);

		add_action(	'init', array($this, '_this_init') );
	}

	public function _this_init()
	{
		// wp_register_script( 'hc2-script-hc-js', plugins_url( 'happ2/assets/js/hc2.js?hcver=' . LC3_VERSION, __FILE__ ), array('jquery') );
		// wp_register_script( 'hc2-script-gmaps-js', plugins_url( 'happ2/modules/maps_google/assets/js/gmaps.js?hcver=' . LC3_VERSION, __FILE__ ), array('jquery') );
		// wp_register_script( 'hc2-script-lc_front-js', plugins_url( 'modules/front/assets/js/front.js?hcver=' . LC3_VERSION, __FILE__ ), array('jquery') );
		// wp_register_script( 'hc2-script-lc-directions-front-js', plugins_url( 'modules/directions.front/assets/js/directions.js?hcver=' . LC3_VERSION, __FILE__ ), array('jquery') );

		$this->hcapp_start();

		$apiUri = apply_filters( 'locatoraid_api_uri', '' );
		if( $apiUri ){
			$uri = $this->hcapp->make( '/http/uri' );
			$uri->set_mode_url( 'api', $apiUri );
		}
	}
}

$hcsl = new Locatoraid();

$widget_file = dirname(__FILE__) . '/modules/widget.wordpress/widget_searchform.php';
if( file_exists($widget_file) ){
	include_once( $widget_file );
	add_action( 'widgets_init', function(){ register_widget("Locatoraid_Searchform_Widget30"); } );
}

$blockFile = dirname(__FILE__) . '/modules/widget.wordpress/blocks.php';
if( file_exists($blockFile) ){
	include_once( $blockFile );
	$blocks = new Locatoraid_Blocks( $hcsl );
}