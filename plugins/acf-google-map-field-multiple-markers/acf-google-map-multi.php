<?php

/*
Plugin Name: ACF: Google Maps Field (Multiple Markers)
Plugin URI: https://wordpress.org/plugins/acf-google-map-field-multiple-markers/
Description: A Google Map field that allows you to add multiple markers to it.
Version: 1.0.5
Author: Rajiv Lodhia
Author URI: https://rajivlodhia.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// Define constants.
if (!defined('GMM_PLUGIN_BASE_NAME')) define( 'GMM_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
if (!defined('GMM_PLUGIN_PATH')) define( 'GMM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('GMM_PLUGIN_URL')) define( 'GMM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if (!defined('GMM_PLUGIN_FILE')) define( 'GMM_PLUGIN_FILE', __FILE__ );
if (!defined('GMM_TEXTDOMAIN')) define( 'GMM_TEXTDOMAIN', 'acf-google-map-multi' );

// check if class already exists
if( !class_exists('gmm_acf_plugin_google_map_multi') ) {

	class gmm_acf_plugin_google_map_multi {

		// vars
		var $settings;


		/*
		*  __construct
		*
		*  This function will setup the class functionality
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	void
		*  @return	void
		*/

		function __construct() {

			// settings
			// - these will be passed into the field class.
			$this->settings = array(
				'version' => '1.0.5',
				'url'     => GMM_PLUGIN_URL,
				'path'    => GMM_PLUGIN_PATH,
			);


			// include field
			add_action( 'acf/include_field_types', array( $this, 'include_field' ) ); // v5
		}


		/*
		*  include_field
		*
		*  This function will include the field type class
		*
		*  @type	function
		*  @date	17/02/2016
		*  @since	1.0.0
		*
		*  @param	$version (int) major ACF version. Defaults to false
		*  @return	void
		*/

		function include_field( $version = false ) {

			// support empty $version
			if ( ! $version ) {
				$version = 5;
			}


			// load textdomain
			load_plugin_textdomain( GMM_TEXTDOMAIN, false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );


			// include
			include_once( 'fields/class-gmm-acf-field-google-map-multi-v' . $version . '.php' );
		}

	}


	// initialize
	new gmm_acf_plugin_google_map_multi();

}
