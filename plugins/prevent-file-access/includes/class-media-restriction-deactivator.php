<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://miniorange.com/
 * @since      1.1.1
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.1.1
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 * @author     test <test@test.com>
 */
class Media_Restriction_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.1
	 */
	public static function deactivate() {
		delete_option( 'mo_media_restriction_admin_email' );
		delete_option( 'mo_media_restriction_admin_customer_key' );
		delete_option( 'mo_media_restriction_new_user' );
	}

}
