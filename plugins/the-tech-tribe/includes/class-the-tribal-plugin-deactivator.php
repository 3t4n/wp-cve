<?php
use TheTribalPlugin\WPOptions;
use TheTribalPlugin\HealthStatus;
/**
 * Fired during plugin deactivation
 *
 * @link       thetechtribe.com
 * @since      1.0.0
 *
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/includes
 * @author     Nigel Moore <help@thetechtribe.com>
 */
class The_Tribal_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		tttRemoveInDbOptions();
	}

}
