<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (!function_exists('wpae_get_blocked_integrations') || !in_array('wp_login_form', wpae_get_blocked_integrations())) :

	function wpa_wplogin_add_initiator_field() {
	    echo '<input type="hidden" id="wpa_initiator" class="wpa_initiator" name="wpa_initiator" value="" />';
	}
	add_action( 'login_form', 'wpa_wplogin_add_initiator_field' );
	add_action( 'woocommerce_login_form', 'wpa_wplogin_add_initiator_field' ); // FIX FOR WOOCOMMERCE LOGIN.


	function wpae_wplogin_extra_validation( $user, $username, $password ) {
	    if ( ! empty( $_POST ) ) {
		    if (wpa_check_is_spam($_POST)){
				do_action('wpa_handle_spammers','wplogin', $_POST);
				return new WP_Error( 'error', $GLOBALS['wpa_error_message']);
			}
		}
		//return $user;
	}
	add_filter( 'authenticate', 'wpae_wplogin_extra_validation', 10, 3 );

endif;