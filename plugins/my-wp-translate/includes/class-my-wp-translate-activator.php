<?php
/**
 * Fired during plugin activation
 *
 * @link       https://mythemeshop.com/
 * @since      1.0.0
 *
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/includes
 * @author     MyThemeShop <support@mythemeshop.com>
 */
class MY_WP_Translate_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Transfer translated strings from theme options of MyThemeShop themes
		global $wpdb;
		$sql = "SELECT option_name AS name, option_value AS value
				FROM  $wpdb->options
				WHERE option_name LIKE '%mts_translations_%'
				ORDER BY option_name";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $result ) {

			$theme_name = str_replace( 'mts_translations_', '', $result->name );

			// Transfer strings
			$new_strings_option_name = 'mtswpt_theme_' . $theme_name . '_strings';
			if ( false == get_option( $new_strings_option_name ) ) {

				update_option( $new_strings_option_name, maybe_unserialize( $result->value ) );
			}

			// Transfer enabled/disabled state of theme options translation panel
			// Get theme options
			$mts_opt = get_option( $theme_name,  array() );

			// We have some theme folders or textdomains prefixed with "mts_" so we need two options...
			$possible_tab_option_names = array(
				'mtswpt_theme_mts_' . $theme_name,
				'mtswpt_theme_' . $theme_name,
			);
			// Only if enabled
			if ( isset( $mts_opt['translate'] ) ) {

				if ( ! empty( $mts_opt['translate'] ) ) {

					foreach ( $possible_tab_option_names as $option_name ) {

						if ( false == get_option( $option_name ) ) {

							update_option(
								$option_name,
								array(
									'translate' => '1',
									'path' => '',
								)
							);
						}
					}
				}
			}
		}

		// First time using the plugin - set some defaults
		if ( false == get_option( 'mtswpt_translations' ) ) {

			update_option(
				'mtswpt_translations',
				array(
					'themes' => array(),
					'plugins' => array(),
					'strings_per_page' => 20,
				)
			);
		}
	}
}
