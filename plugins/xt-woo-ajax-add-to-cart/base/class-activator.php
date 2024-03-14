<?php

/**
 * Fired during plugin activation
 *
 * @link       http://xplodedthemes.com
 * @since      1.0.0
 *
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 * @author     XplodedThemes 
 */
class XT_WOOATC_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        do_action('xt_wooatc_activate');
	}

}

XT_WOOATC_Activator::activate();