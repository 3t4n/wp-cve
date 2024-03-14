<?php

namespace IfSo\Services\LicenseService;

class LicenseService {
	private static $instance;
	
	private $plans;
	private $num_of_retries_to_check_license;
	private $interval_valid_license_check;
	private $interval_invalid_license_check;
	private $geo_array = array(9132, 9129, 8261, 6530, 4872638);

	private function __construct() {
		$this->init_plans();
		$this->num_of_retries_to_check_license = 8;
		$this->interval_valid_license_check = (60 * 60 * 12);
		$this->interval_invalid_license_check = (60 * 60 * 6);
	}

	private function init_plans() {
		$this->plans = array(
			2473,
			5965, 
			8261,
			9129,
			9132,
			9134,
			9136,
			9029,
			6530,
            35211,
            35215,
            35418,
            68212,
            68218,
            79168,
            79170,
            79171,
            79172,
            79173,
            79278,
		);		
	}

	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new LicenseService();

		return self::$instance;
	}

	// Helper function that sends request to the license endpoint
	// with the given `action` (e.g `action` might be `check_license`)
	private function query_ifso_api($edd_action, $license, $item_id) {
			// data to send in our API request
			$api_payload = array(
				'edd_action' => $edd_action, //'activate_license',
				'license'    => $license,
				'item_id'  => $item_id, // the name of our product in EDD
				'url'        => home_url(),
                'license_type' => 'product'
			);

			$message = false;
			$license_data = false;
			// Call the custom API.
			$response = wp_remote_post( EDD_IFSO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_payload ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}
			}
			else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			}

			if (!$license_data) 
				return $message;

			return $license_data;
	}

	// Helper function that returns the proper messages to the client
	// according to the result received from the API (resides in $license_data)
	private function edd_api_get_error_message($license_data) {
		$message = false;
        if(!is_object($license_data)) return false;
		if ( false === $license_data->success ) {
			if ( isset($license_data->error_message) &&
				 !empty($license_data->error_message) ) {
				return $license_data->error_message;
			}
			switch( $license_data->error ) {
                //Replace HTML links with !!!LINKSTART!!!//!!!LINKEND!!! formatting, since we're filtering the payload to prevent XSS
				case 'expired' :
					$message = sprintf(
						__( 'Your license key has expired. !!!LINKSTART!!!https://www.if-so.com/plans?utm_source=Plugin&utm_medium=direct&utm_campaign=licenseExpired&utm_term=LicensePage&utm_content=a!!!LINKEND!!!!!!LINKTEXT!!!Click here to get a new license!!!LINKTEXTEND!!!' ),
						date_i18n( strtotime( $license_data->expires, get_option( 'date_format' ), current_time( 'timestamp' ) ) )
					);
					break;
				case 'revoked' :
                case 'disabled' :
					$message = __( 'The license key has been disabled.' );
					break;
				case 'missing' :
					$message = __( 'Invalid license key.' );
					break;
				case 'invalid' :
				case 'site_inactive' :
					$message = __( 'Your license is not active for this URL.' ); 
					break;
				case 'item_name_mismatch' :
					$message = __( 'This appears to be an invalid license key for the selected item.' ); 
					break;
				case 'invalid_item_id':
					// $message = __( 'This appears to be an invalid license key for `Free Tiral` product.' );
					$message = __( 'The license key is invalid for this version of the plugin. Make sure you have updated the plugin. If the problem persists, please contact us at support@if-so.com.' );
					break;
				case 'no_activations_left':
					$message = __( 'This license key is currently active in another domain. !!!LINKSTART!!!http://www.if-so.com/plans?ifso=pro&utm_source=Plugin&utm_medium=LicenseErrors&utm_campaign=LicenseAlreadyActive!!!LINKEND!!!!!!LINKTEXT!!!Click here to get a new license!!!LINKTEXTEND!!!' );
					
					break;
				case 'domain_already_has_key':
					$message = __( 'A free trial license has already been used for this domain. !!!LINKSTART!!!http://www.if-so.com/plans?ifso=pro&utm_source=Plugin&utm_medium=TrialEnded&utm_campaign=LicensePage!!!LINKEND!!!!!!LINKTEXT!!!Click here to get a pro license.!!!LINKTEXTEND!!!' );
					break;
				default :
					break;
			}
		}
		return $message;
	}

	private function edd_api_activate_item($license, $item_id) {
		return $this->query_ifso_api('activate_license', $license, $item_id);
	}

	public function deactivate_license_request($license, $item_id) {
		return $this->query_ifso_api('deactivate_license', $license, $item_id);
	}

	// tries to deactivate $license with every plan, 
	// starting from $item_id plan.
	private function edd_api_deactivate_item($license, $item_id) {
		$license_data = NULL;
		if ( !empty($item_id) ) {
			$license_data = $this->deactivate_license_request($license, $item_id);
		}
		if ( isset($license_data->success) && $license_data->success )
		{
			return $license_data;
		}
		foreach ($this->plans as $key => $plan_id) {
			$license_data = $this->deactivate_license_request($license, $plan_id);
			if ($license_data instanceof \stdClass &&
				$license_data->success) 
			{
				return $license_data;
			}
		}
		
		return $license_data;
	}

	private function try_to_activate_license($license, $item_id) {
		$license_data = NULL;

		if ( $item_id ) {
			$license_data = $this->edd_api_activate_item( $license, $item_id );
			if ( !$this->is_item_id_invalid_or_mismatch($license_data) ) {
				return $license_data;
			}
		}

		foreach ($this->plans as $key => $plan_id) {
			if ($plan_id != $item_id) {
				$license_data = $this->edd_api_activate_item( $license, $plan_id );
				if ( !$this->is_item_id_invalid_or_mismatch($license_data) ) {
					update_option( 'edd_ifso_license_item_id', $plan_id );
					return $license_data;
				}
			}
		}

		return $license_data;
	}

	private function is_item_id_invalid_or_mismatch($license_data) {
		if (!isset($license_data->license)) return true;
		if (!isset($license_data->error)) return false;
		return ( $license_data->error == 'item_name_mismatch' || 
			     $license_data->error == 'invalid_item_id' );
	}

	private function check_license_request($license, $item_id) {
		$license_data = NULL;
		if ( $item_id ) {
			$license_data = $this->send_check_license_request($license, $item_id);
		}
		if ( !$this->is_item_id_invalid_or_mismatch($license_data) ) {
			return $license_data;
		}
		foreach ($this->plans as $key => $plan_id) {
			if ($plan_id != $item_id) {
				$license_data = $this->send_check_license_request($license, $plan_id);
				if ( !$this->is_item_id_invalid_or_mismatch($license_data) ) {
					update_option( 'edd_ifso_license_item_id', $plan_id );
					return $license_data;
				}
			}
		}
		return $license_data;
	}

	private function send_check_license_request($license, $item_id) {
		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_id' => $item_id,
			'url'       => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( EDD_IFSO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		if ( is_wp_error( $response ) )
			return false;
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		return $license_data;
	}

	private function edd_ifso_if_license_valid_from_license_data($license_data) {
		// print_r($license_data);
		if( $license_data->license == 'valid' ) {
			return true;
			// echo 'valid'; exit;
			// this license is still valid
		} else {
			return false;
			// echo 'invalid'; exit;
			// this license is no longer valid
		}
	}

	private function edd_ifso_is_license_inactive($license_data) {
		// print_r($license_data);
		if( $license_data->license == 'inactive' ) {
			return true;
			// echo 'valid'; exit;
			// this license is still valid
		} else {
			return false;
			// echo 'invalid'; exit;
			// this license is no longer valid
		}
	}

	/*
	 *	Runs when the user clicks on "Activate License" button
	 *	registered via 'admin_init'
	 */
	public function edd_ifso_activate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['edd_ifso_license_activate'] ) ) {
			// run a quick security check
		 	if( ! check_admin_referer( 'edd_ifso_nonce', 'edd_ifso_nonce' ) )
				return; // get out if we didn't click the Activate button
			// retrieve the license from the database
			$db_license = trim( get_option('edd_ifso_license_key') );
			$license = !empty($_POST['for_deactivation']) && !$this->edd_api_get_error_message($_POST['for_deactivation']) && substr(trim( $_POST["edd_ifso_license_key"] ), -5) ==  substr(trim( $_POST['for_deactivation'] ), -5) ? $_POST['for_deactivation'] : trim( $_POST["edd_ifso_license_key"] );
            $old_license_key = get_option('edd_ifso_license_key');
			if ($db_license != $license)
				delete_option('edd_ifso_license_status');
			// save the license in the database
			update_option('edd_ifso_license_key', $license);
			// Iterating over each plan and trying to activate it
			// $last_plan = NULL;
			// foreach ($this->plans as $key => $plan) {
			// 	$last_plan = $plan;
			// 	$license_data = $this->edd_api_activate_item($license, $plan);
			// 	if ($license_data instanceof stdClass &&
			// 		$license_data->error == 'item_name_mismatch') {
			// 		// Ok, keep going. it's another item
			// 		continue;
			// 	}
			// 	break;
			// }
			$license_data = $this->try_to_activate_license($license, NULL);
			$message = '';
			if ($license_data instanceof \stdClass)
				$message = $this->edd_api_get_error_message($license_data);

			//check if user entered a license of  a wrong type(geo)
			$passed_license_id = get_option('edd_ifso_license_item_id');
            $wrongLicenseGoto='false';
			if(empty($message) && in_array($passed_license_id, $this->geo_array)){
                $message .= __("The license key you tried to activate is a geolocation license key. Activate the license in the designated “geolocation” license field below.",'if-so');
                $wrongLicenseGoto = 'geo';
            }
			$base_url = admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_LICENSE_PAGE);

			if ( ! empty( $message ) ) {
			    //If an error message was generated earlier, redirect back to the page, showing the relevant error messages
			    update_option('edd_ifso_license_key',$old_license_key);
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ), 'license_type'=>'pro',
					'method' => 'license','wrongLicenseGoto'=>$wrongLicenseGoto ), $base_url );
				wp_redirect( $redirect );
				exit();
			}
			// die($this->plans[$last_plan_indx]);
			// update_option('edd_ifso_license_item_id', $this->primary_product_id);
			// $license_data->license will be either "valid" or "invalid"
			update_option( 'edd_ifso_license_expires', $license_data->expires );
			update_option( 'edd_ifso_license_status', $license_data->license );
			update_option( 'edd_ifso_license_item_name', $license_data->item_name );
			update_option( 'edd_ifso_had_license', true );
			update_option( 'edd_ifso_has_lifetime_license', ($license_data->expires == 'lifetime') );
			delete_option( 'edd_ifso_user_deactivated_license' );
			$redirect = add_query_arg( array( 'method' => 'license' ), $base_url );
			wp_redirect( $redirect );
			exit();
		}
	}

	public function edd_ifso_deactivate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['edd_ifso_license_deactivate'] ) ) {
			// run a quick security check
		 	if( ! check_admin_referer( 'edd_ifso_nonce', 'edd_ifso_nonce' ) )
				return; // get out if we didn't click the Activate button
			// retrieve the license from the database
			//$license = trim( get_option( 'edd_ifso_license_key' ) );
			$license = trim( $_POST['for_deactivation'] );
			$item_id = get_option( 'edd_ifso_license_item_id' );
			$license_data = $this->edd_api_deactivate_item($license, $item_id);
			$base_url = admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_LICENSE_PAGE );

			if ($license_data->success) { //לבדוק מה מוחזר אם הרשיון לא קיים באד. לדוגמא, נמחק ידנית דרך אד.

				if( $license_data->license == 'deactivated' ) {

					delete_option( 'edd_ifso_license_status' );
					delete_option( 'edd_ifso_license_item_id' );
					delete_option( 'edd_ifso_has_lifetime_license' );
					delete_transient( 'ifso_transient_license_validation' );
					//if ( $license_data->expires == 'lifetime' ) {

						update_option( 'edd_ifso_user_deactivated_license', true );
					//}

				}
				$redirect = add_query_arg( array( 'method' => 'license' ), $base_url );
				wp_redirect( $redirect );
				exit();
			}
			$message = 'Something went wrong. Please try again or contact support at support@if-so.com';
			if (!($license_data instanceof \stdClass))
				$message = $license_data;
            if(isset($license_data->expires) && $license_data->expires < time()){  //Allow license deactivation if the key has expired
                delete_option( 'edd_ifso_license_status' );              //(other cases where deactivation has to happen despite an error?)
                delete_option( 'edd_ifso_license_item_id' );
                delete_option( 'edd_ifso_has_lifetime_license' );
                delete_transient( 'ifso_transient_license_validation' );

                update_option( 'edd_ifso_user_deactivated_license', true );
                $message = 'Expired license has been deactivated';
            }
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ),'license_type'=>'pro',
				'method' => 'license' ), $base_url );
			wp_redirect( $redirect );
			exit();
		}
	}

	public function activate_license($license, $item_id) {
		return $this->query_ifso_api('activate_license', $license, $item_id);
	}

	private function handle_invalid_license($reason) {
		update_option( 'edd_ifso_license_num_of_checks', 0 );
		update_option('edd_ifso_license_deactivation_reason', $reason);
		
		delete_option( 'edd_ifso_license_status' );
		delete_option( 'edd_ifso_license_item_id' );
		// set inetrval as invalid license
		set_transient( 'ifso_transient_license_validation',
					   true,
					   $this->interval_invalid_license_check );
	}

	/* Responsible to check if the license is still valid */
	public function edd_ifso_is_license_valid() {
		
		if ( !get_transient( 'ifso_transient_license_validation' ) ) {
			if ( get_option( 'edd_ifso_has_lifetime_license' ) == 1 ) { //לבדוק אם זו הסיבה
				set_transient( 'ifso_transient_license_validation',
							   true,
							   60 * 60 * 24 * 7 );
				return;
			}
			$license = get_option( 'edd_ifso_license_key' );
			$item_id = get_option( 'edd_ifso_license_item_id' ); // $this->primary_product_id;
			$is_license_valid = (get_option( 'edd_ifso_license_status' ) === 'valid');
			// Validation
			if ( $license == false || $item_id == false ) {
				// the option is not set yet
				// set inetrval as valid license
				set_transient( 'ifso_transient_license_validation',
							   true,
							   $this->interval_valid_license_check );
				return; // exit the function
			}
			// send request to IfSo server to check for license validy
			$license_data = $this->check_license_request($license, $item_id);
			// handle license status
			if ( $license_data && 
				 $license_data->license == 'valid' ) {
				// the license is valid
				update_option( 'edd_ifso_license_num_of_checks', 0 );
				if ( !$is_license_valid ) {
					// it was not activated
					// thus we active it now
					$license_data = $this->try_to_activate_license($license, $item_id);
					if ( $license_data ) {
						// update everything
						// update_option( 'edd_ifso_license_item_id', $this->primary_product_id );
						update_option( 'edd_ifso_license_status', $license_data->license );
						update_option( 'edd_ifso_license_expires', $license_data->expires );
					}
				} else {
					update_option( 'edd_ifso_license_expires', $license_data->expires );
				}
				// set inetrval as valid license
				set_transient( 'ifso_transient_license_validation',
							   true,
  			 				   $this->interval_valid_license_check );
			} else if ( $license_data &&
						$license_data->license == 'inactive' ) {
				// the license is inactive. so we try to activate it
				update_option( 'edd_ifso_license_num_of_checks', 0 );
				
				$license_data = $this->try_to_activate_license( $license, $item_id );
				if ( $license_data &&
					 $license_data == 'valid') {
					// update_option( 'edd_ifso_license_item_id', $this->primary_product_id );
					update_option( 'edd_ifso_license_status', $license_data->license );
					update_option( 'edd_ifso_license_expires', $license_data->expires );
				}
				// set inetrval as valid license
				set_transient( 'ifso_transient_license_validation',
							   true,
							   $this->interval_valid_license_check );
			} else if ( $license_data &&
						$license_data->license == 'expired' ) {
				// the license is expired
				$this->handle_invalid_license("License expired with: " . json_encode( $license_data ));
			} else {
				// something else? if it happens X times in Y interval
				// then we deactivate
				// how many times did we check for validy?
				$num_of_checks = get_option( 'edd_ifso_license_num_of_checks' );
				if ( $num_of_checks == false ) { // first time check
					update_option( 'edd_ifso_license_num_of_checks', 1 );
					// set inetrval as valid license
					set_transient( 'ifso_transient_license_validation',
								   true,
								   $this->interval_valid_license_check );
				} else if ( $num_of_checks >= $this->num_of_retries_to_check_license ) {
					// we tested for validation for enough time
					// thus deactivating
					$this->handle_invalid_license("Exceeded num of checks: $num_of_checks = " . $num_of_checks);
				} else {
					// we didn't pass the num of retries we have
					// so we add one and proceed as "valid" license
					update_option( 'edd_ifso_license_num_of_checks', ($num_of_checks+1) );
					// set inetrval as valid license
					set_transient( 'ifso_transient_license_validation',
								   true,
								   $this->interval_valid_license_check );
				}
			}
		}
	}

	public function edd_ifso_clear_license(){
        if(!empty($_REQUEST['edd_ifso_license_clear']) || !empty($_REQUEST['edd_ifso_geo_license_clear'])){
            if( ! check_admin_referer( 'edd_ifso_nonce', 'edd_ifso_nonce' ) ) return;
            if(!empty($_REQUEST['edd_ifso_license_clear'])) {
                $license_key = get_option( 'edd_ifso_license_key' );
                $item_id = get_option( 'edd_ifso_license_item_id' );
                if(!empty($license_key) && !empty($item_id))
                    $this->deactivate_license_request($license_key,$item_id);
                delete_option('edd_ifso_license_status');
                delete_option('edd_ifso_license_item_id');
                delete_option('edd_ifso_has_lifetime_license');
                delete_option('edd_ifso_license_key');
                delete_option('edd_ifso_license_expires');
                delete_transient('ifso_transient_license_validation');
            }
            if(!empty($_REQUEST['edd_ifso_geo_license_clear'])){
                \IfSo\Services\GeoLicenseService\GeoLicenseService::get_instance()->clear_license();
            }
            $exploded_uri = explode('&', $_SERVER['REQUEST_URI']);
            $exploded_uri = array_slice($exploded_uri,0,-2);
            wp_redirect(implode('&', $exploded_uri));
        }
    }

	public function is_license_valid() {
		$status  = get_option( 'edd_ifso_license_status' );
		return ( $status !== false && $status == 'valid' );
	}

	public function get_license() {
		return get_option( 'edd_ifso_license_key' );
	}
}
