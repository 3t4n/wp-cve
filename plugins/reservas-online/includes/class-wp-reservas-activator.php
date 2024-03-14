<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ticketself.com
 * @since      1.0.0
 *
 * @package    Wp_Reservas
 * @subpackage Wp_Reservas/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Reservas
 * @subpackage Wp_Reservas/includes
 * @author     mg <mikel@ticketself.com>
 */
class Wp_Reservas_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */



	public static function activate() {
		if (empty(get_option('wpReservas-installed'))) {
			$admin_email = get_option('admin_email');
			$blogname = get_option('blogname');
			$options = get_option('wp-reservas');
			$siteurl = get_option('siteurl');
	        // Cleanup
	 		$url = RESERVAS_ONLINE_BASE_URL.'action/integrations/wordpress/activate';
			$fields = array(
			    'name' => $blogname,
			    'email' => $admin_email,
			    'siteurl' => $siteurl
			    );
			$response = wp_remote_post( $url, array(
			    'method' => 'POST',
			    'timeout' => 5,
			    'httpversion' => '1.0',
			    'headers' => array(),
			    'body' => $fields,
			    )
			);
			$response_information = json_decode($response['body'],true);
			if ($response_information['result'] == 'success') {
				//done
				delete_option('wpReservas-email');
    			delete_option('wpReservas-password');
				add_option( 'wpReservas-email', $response_information['email'], '', 'yes' );
				add_option( 'wpReservas-password', $response_information['password'], '', 'yes' );
			}
			else {
				//existing user
			}
			// error_log(json_encode($response));
			// error_log($response['body']);
			// print_r($response);
			// exit;
			if ( is_wp_error( $response ) ) {
			    $error_message = $response->get_error_message();
			    echo "Something went wrong: $error_message";
			} 
		}
	}

}
