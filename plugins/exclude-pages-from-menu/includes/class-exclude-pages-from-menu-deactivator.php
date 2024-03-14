<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * 
 * @since      1.0
 * @package    EPFM
 * @subpackage EPFM/includes
 * @author     Vinod Dalvi <mozillavvd@gmail.com>
 */
class Exclude_Pages_From_Menu_Deactivator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    1.0
	 */
	public static function deactivate() {

		$options = get_option( 'exclude_pages_from_menu' );

		if ( isset( $options['dismiss_admin_notices'] ) ) {
			unset( $options['dismiss_admin_notices'] );
			update_option( 'exclude_pages_from_menu', $options );
		}
	}

}