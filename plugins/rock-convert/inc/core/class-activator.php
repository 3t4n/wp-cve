<?php
/**
 * The activator class
 *
 * @package    Rock_Convert\Inc\Core
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Core;

use Rock_Convert\Inc\Admin\Utils;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Activator {

	/**
	 * Activate plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$min_php = '5.6.0';

		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if ( version_compare( PHP_VERSION, $min_php, '<' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'This plugin requires a minmum PHP Version of ' . esc_attr( $min_php ) );
		}

		self::build_table_structure();

		add_option( 'rock_convert_getting_started', true );
	}

	/**
	 * Call table structure script to check if is necessary to
	 * create the plugin tables
	 *
	 * @since 2.0.0
	 */
	public static function build_table_structure() {
		$structure = new Table_Structure();

		if ( ! $structure->is_installed() ) {
			$structure->install();
		} else {
			if ( $structure->is_outdated() ) {
				$structure->migrate();
			}
		}
	}

	/**
	 * Check db structure.
	 *
	 * @return void
	 */
	public function table_structure_db_check() {
		self::build_table_structure();
	}

}
