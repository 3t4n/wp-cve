<?php

/**
 * Fired during plugin activation
 *
 * @link       https://zeitwesentech.com
 * @since      1.0.0
 *
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/includes
 * @author     zeitwesentech <sayhi@zeitwesentech.com>
 */
class Zwt_wp_linkpreviewer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $instance = new Zwt_wp_linkpreviewer_Db();
        $instance->init_db();
	}

}
