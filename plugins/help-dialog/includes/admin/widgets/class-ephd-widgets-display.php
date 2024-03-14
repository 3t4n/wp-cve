<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Widgets page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Widgets_Display {

	/**
	 * Displays the Help Dialog Widgets page with top panel
	 */
	public function display_page() {

		$handler = self::get_widgets_page_handler();
		if ( is_wp_error( $handler ) ) {
			EPHD_HTML_Admin::display_config_error_page( $handler );
			return;
		}

		// display the widgets page
		$handler->display_page();
	}

	public static function get_widgets_page_handler( $widgets_config=[] ) {

		// initialize reset configuration handler before retrieve configuration
		EPHD_Delete_HD::reset_config_button_handler();

		// retrieve widgets configuration
		if ( empty( $widgets_config ) ) {

			// retrieve main widgets configs
			$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
			if ( is_wp_error( $widgets_config ) ) {
				return $widgets_config;
			}
		}

		return new EPHD_Widgets_Page( $widgets_config );
	}
}
