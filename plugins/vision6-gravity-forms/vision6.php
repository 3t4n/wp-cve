<?php
/**
Plugin Name: Vision6 Gravity Forms Add-On
Plugin URI: https://wordpress.org/plugins/vision6-gravity-forms
Description: Integrates Gravity Forms with Vision6, allowing form submissions to be automatically sent to your Vision6 account
Version: 1.1.2
Author: Vision6
Author URI: https://www.vision6.com.au
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gravityformsvision6
*/

define( 'GF_VISION6_VERSION', '1.1.2' );

// If Gravity Forms is loaded, bootstrap the Vision6 Add-On.
add_action( 'gform_loaded', array( 'GF_Vision6_Bootstrap', 'load' ), 5 );

/**
 * Class GF_Vision6_Bootstrap
 *
 * Handles the loading of the Vision6 Add-On and registers with the Add-On Framework.
 */
class GF_Vision6_Bootstrap {

	/**
	 * If the Feed Add-On Framework exists, Vision6 Add-On is loaded.
	 *
	 * @access public
	 * @static
	 */
	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}

		require_once( 'class-gf-vision6.php' );
		GFAddOn::register( 'GFVision6' );
	}
}

/**
 * Returns an instance of the GFVision6 class
 *
 * @see    GFVision6::get_instance()
 *
 * @return object GFVision6
 */
function gf_vision6() {
	return GFVision6::get_instance();
}