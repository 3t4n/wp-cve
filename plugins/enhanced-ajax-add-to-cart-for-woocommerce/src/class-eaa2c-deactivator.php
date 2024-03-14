<?php

/**
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/includes
 * @author     TheRiteSites <contact@theritesites.com>
 */
class Enhanced_Ajax_Add_To_Cart_Wc_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( 'on' === get_option( 'a2cp_delete_on_deactivation' ) ) {
			delete_option( 'a2cp_out_of_stock' );
			delete_option( 'a2cp_default_text' );
			delete_option( 'a2cp_custom_class' );
			delete_option( 'a2cp_debug' );
			delete_option( 'a2cp_dom_check' );
			delete_option( 'a2cp_button_blocking' );
			delete_option( 'a2cp_image_field' );
			delete_option( 'a2cp_custom_field' );
			delete_option( 'a2cp_short_description' );
			delete_option( 'a2cp_delete_on_deactivation' );
		}
	}

}
