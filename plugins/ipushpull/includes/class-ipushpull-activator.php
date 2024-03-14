<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ipushpull-logger.php';

/**
 * Fired during plugin activation
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Ipushpull
 * @subpackage Ipushpull/includes
 * @author     ipushpull <support@ipushpull.com>
 */
class Ipushpull_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    2.0.0
     */
    public static function activate() {
        Ipushpull_Logger::log('activate');
    }

}
