<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://xplodedthemes.com
 * @since      1.0.0
 *
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    XT_WOOATC
 * @subpackage XT_WOOATC/includes
 * @author     XplodedThemes 
 */
class XT_WOOATC_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        do_action('xt_wooatc_deactivate');
	}


}

XT_WOOATC_Deactivator::deactivate();