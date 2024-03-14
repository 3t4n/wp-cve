<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://anssilaitila.fi
 * @since      1.0.0
 *
 * @package    Contact_List
 * @subpackage Contact_List/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Contact_List
 * @subpackage Contact_List/includes
 * @author     Anssi Laitila <anssi.laitila@gmail.com>
 */
class Contact_List_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

    contact_list_fs()->add_action('after_uninstall', 'contact_list_fs_uninstall_cleanup');

	}

}
