<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://iqonic.design/
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.7.2
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.7.2
	 */
	public static function deactivate() {
        marvy_plugin_activation(true);
	}

}
