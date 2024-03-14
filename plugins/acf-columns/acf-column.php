<?php
/*
Plugin Name: ACF Columns
Plugin URI: https://wordpress.org/plugins/acf-columns/
Description: Adds an ACF field to arrange ACF fields into columns.
Version: 1.2.5
Author: Thomas Meyer
Author URI: https://dreihochzwo.de
Text Domain: acf_column
Domain Path: /languages
License: GPLv2 or later
Copyright: Thomas Meyer
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('DHZ_ACF_PLUGIN_COLUMN_FIELD') ) :

class DHZ_ACF_PLUGIN_COLUMN_FIELD {

	public $settings;
	
	function __construct() {

		// vars
		$this->settings = array(
			'plugin'			=> 'ACF Columns',
			'this_acf_version'	=> 0,
			'min_acf_version'	=> '5.4.0',
			'version'			=> '1.2.5',
			'url'				=> plugin_dir_url( __FILE__ ),
			'path'				=> plugin_dir_path( __FILE__ ),
			'plugin_path'		=> 'https://wordpress.org/plugins/acf-columns/'
		);
		
		// set text domain
		load_plugin_textdomain( 'acf_column', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );

		// check for ACF and min version
		add_action( 'admin_init', array($this, 'acf_or_die'), 11);
				
		// include field
		add_action('acf/include_field_types', array($this, 'include_field_types_column'));

	}

	/**
	 * Let's make sure ACF Pro is installed & activated
	 * If not, we give notice and kill the activation of ACF RGBA Color Picker.
	 * Also works if ACF Pro is deactivated.
	 */
	function acf_or_die() {

		if ( !class_exists('acf') ) {
			$this->kill_plugin();
		} else {
			$this->settings['this_acf_version'] = acf()->settings['version'];
			if ( version_compare( $this->settings['this_acf_version'], $this->settings['min_acf_version'], '<' ) ) {
				$this->kill_plugin();
			}
		}
	}

	function kill_plugin() {
		deactivate_plugins( plugin_basename( __FILE__ ) );   
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		add_action( 'admin_notices', array($this, 'acf_dependent_plugin_notice') );
	}

	function acf_dependent_plugin_notice() {
		echo '<div class="error"><p>' . sprintf( __('%1$s requires ACF PRO v%2$s or higher to be installed and activated.', 'acf_column'), $this->settings['plugin'], $this->settings['min_acf_version']) . '</p></div>';
	}

	function include_field_types_column( $version ) {

		if ( version_compare(acf()->settings['version'], '5.7.0', '>' ) ) {
			// echo "<pre style='padding: 20px 0 0 190px'>";
			// print_r('ist größer oder gleich 5.7.0');
			// echo "</pre>";

			include_once('fields/acf-column-v5.7.php');

		} else if ( version_compare(acf()->settings['version'], '5.7.0', '<' ) ) {
			// echo "<pre style='padding: 20px 0 0 190px'>";
			// print_r('ist kleiner als 5.7.0');
			// echo "</pre>";

			include_once('fields/acf-column-v5.php');
		}
			
		// support empty $version
		if( !$version ) $version = 5;
		
		
		// include
		include_once('fields/acf-column-v5.php');
	}
}
// initialize
new DHZ_ACF_PLUGIN_COLUMN_FIELD();

// class_exists check
endif;

?>