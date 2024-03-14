<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * 
 * @since      1.0.0
 * @package    LLRM
 * @subpackage LLRM/includes
 * @author     Vinod Dalvi <mozillavvd@gmail.com>
 */
class Login_Logout_Register_Menu_Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		$options = get_option( 'llrm' );
		if ( isset( $options['dismiss_admin_notices'] ) ) {
			unset( $options['dismiss_admin_notices'] );
			update_option( 'llrm', $options );
		}
	}

}