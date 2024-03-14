<?php
/**
 *
 * I18n
 * Load text domain and language files.
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

/**
 *
 * Wp_Custom_Cursors_I18n
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_I18n {
	/**
	 * Load text domains
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wpcustom-cursors',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
