<?php
/**
 * Define the internationalization functionality
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_i18n' ) ) {
	class Widget_Box_Lite_i18n {

		/**
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				'widget-box-lite',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		}
	}
}