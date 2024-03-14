<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.buymeacoffee.com
 * @since      1.0.0
 *
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/includes
 * @author     Buymeacoffee <hello@buymeacoffee.com>
 */
class Buy_Me_A_Coffee_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        update_option('bmc_plugin_activated', 0);
    }
}
