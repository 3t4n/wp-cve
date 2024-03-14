<?php
namespace PargoWp\Includes;

/**
 * Fired during plugin deactivation
 *
 * @link       pargo.co.za
 * @since      1.0.0
 *
 * @package    Pargo
 * @subpackage Pargo/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pargo
 * @subpackage Pargo/includes
 * @author     Pargo <support@pargo.co.za>
 */
class Pargo_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        Analytics::submit('client', 'click', 'plugin_disable' );
	}

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function uninstall() {

        Analytics::submit('client', 'click', 'uninstall' );
    }

}
