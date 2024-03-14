<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Activation {

	/**
	 * All Task for plugin activation
	 * @return void
	 */
	public static function register_activation() {
		// Active all widgets when activate the plugin
		self::activationWidgetsSettingsMap();
		// Load Font Awesome 4 Support
		update_option( 'elementor_load_fa4_shim','yes' );	
	}
	private static function activationWidgetsSettingsMap() {
		$widgets = \Enteraddons\Inc\Widgets_List::getAllWidgets();
		$checkExistData = get_option(ENTERADDONS_OPTION_KEY);

		if( empty( $checkExistData['widgets'] ) ) {
			$c = array_column( $widgets, 'name' );
			$w['widgets'] = $c;
			update_option( ENTERADDONS_OPTION_KEY, $w );
		}

	}
	
} // END CLASS