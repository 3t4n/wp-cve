<?php

/**
 * Fired during plugin activation
 *
 * @link       nakunakifi.com
 * @since      4.0.0
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      4.0.0
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/includes
 * @author     Ian Kennerley <iankennerley@gmail.com>
 */
class Google_Photos_Albums_Gallery_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    4.0.0
	 */
	public static function activate() {
		// delete_option( 'cws_gpp_token_expires' ); // don't think this is needed in v4.0.0
		delete_option( 'cws_gpp_code' );
		// delete_option( 'cws_gpp_access_token' ); // don't think this is needed in v4.0.0

		$cws_gpp_options = array(
					'num_album_results' => 9,
					'num_image_results' => 4,
					'album_thumb_size' => 500,
					'thumb_size' => 500,
					'private_albums' => "All",
					'show_album_title' => 1,
					'show_album_details' => 0,
					'show_image_title' => 1,
					'imgmax' =>800,
					'results_page' => '',
					'hide_albums' => '',
					'theme' => '',
					'lightbox_image_size' => '800',
					'enable_cache' => '',
					'row_height' => '250',
				);

		// Check to see if we already have some options
		$existing_options = get_option( 'cws_gpp_options' );

		if( is_array( $existing_options ) ) {
			$result = array_merge( 	$cws_gpp_options, $existing_options );
			update_option( 'cws_gpp_options', $result ); 
		} else {
			update_option( 'cws_gpp_options', $cws_gpp_options );
		}


		// delete dismiss upgrade notice
		// $current_user = getCurrentUser();
		// delete_user_meta( $current_user->ID, 'cws_gpp_ignore_upgrade' );
	}

}
