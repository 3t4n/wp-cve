<?php

use DP\Wp\Settings;
use MSMoMDP\Wp\AdminPromo;

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/tomas-groulik/
 * @since      1.0.0
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
 * @author     Tomas Groulik <tomas.groulik@gmail.com>
 */
class GG_Monarch_Sidebar_Minimized_On_Mobile_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        
        AdminPromo::reset_promo_states('dp_msmom_basic_options');
	}

}
