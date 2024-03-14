<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ipushpull-logger.php';

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Ipushpull
 * @subpackage Ipushpull/includes
 * @author     ipushpull <support@ipushpull.com>
 */
class Ipushpull_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    2.0.0
     */
    public static function deactivate()
    {
        Ipushpull_Logger::log('deactivate');
    }

}
