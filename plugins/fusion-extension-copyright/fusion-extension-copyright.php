<?php
/**
 * @package Fusion_Extension_Copyright
 */
/**
 * Plugin Name: Fusion : Extension - Copyright
 * Plugin URI: http://www.agencydominion.com/fusion/
 * Description: Copyright Extension Package for Fusion.
 * Version: 1.1.3
 * Author: Agency Dominion
 * Author URI: http://agencydominion.com
 * Text Domain: fusion-extension-copyright
 * Domain Path: /languages/
 * License: GPL2
 */
 
/**
 * FusionExtensionCopyright class.
 *
 * Class for initializing an instance of the Fusion Copyright Extension.
 *
 * @since 1.0.0
 */


class FusionExtensionCopyright	{ 
	public function __construct() {
						
		// Initialize the language files
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		
	}
	
	/**
	 * Load Textdomain
	 *
	 * @since 1.1.3
	 *
	 */
	 
	public function load_textdomain() {
		load_plugin_textdomain( 'fusion-extension-copyright', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
}

$fsn_extension_copyright = new FusionExtensionCopyright();

//EXTENSIONS

//copyright
require_once('includes/extensions/copyright.php');

?>