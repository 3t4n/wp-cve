<?php
/**
 * License handler relevant to the activation/deactivation process
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class If_So_License {

    private static $plans = array(
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

	private static function _query_ifso_api($edd_action, $license, $item_id) {
        require_once plugin_dir_path( __FILE__ ) . 'class-if-so.php';


			// data to send in our API request
			$api_payload = array(
				'edd_action' => $edd_action, //'activate_license',
				'license'    => $license,
				'item_id'  => $item_id, // the name of our product in EDD
				'url'        => home_url()
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

			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			}

			if (!$license_data) return $message;
			return $license_data;
	}

    public static function edd_api_deactivate_item($license, $item_id) {
        $license_data = NULL;
        if ( !empty($item_id) ) {
            $license_data = self::deactivate_license($license, $item_id);
        }
        if ( isset($license_data->success) && $license_data->success )
        {
            return $license_data;
        }
        foreach (self::$plans as $key => $plan_id) {
            $license_data = self::deactivate_license($license, $plan_id);
            if ($license_data instanceof \stdClass &&
                $license_data->success)
            {
                return $license_data;
            }
        }

        return $license_data;
    }

    public static function try_to_activate_license($license, $item_id) {
        $license_data = NULL;

        if ( $item_id ) {
            $license_data = self::activate_license( $license, $item_id );
            if ( !self::is_item_id_invalid_or_mismatch($license_data) ) {
                return $license_data;
            }
        }

        foreach (self::$plans as $key => $plan_id) {
            if ($plan_id != $item_id) {
                $license_data = self::activate_license( $license, $plan_id );
                if ( !self::is_item_id_invalid_or_mismatch($license_data) ) {
                    update_option( 'edd_ifso_license_item_id', $plan_id );
                    return $license_data;
                }
            }
        }

        return $license_data;
    }

    private static function is_item_id_invalid_or_mismatch($license_data) {
        if (!isset($license_data->license)) return true;
        if (!isset($license_data->error)) return false;
        return ( $license_data->error == 'item_name_mismatch' ||
            $license_data->error == 'invalid_item_id' );
    }

	public static function deactivate_license($license, $item_id) {
		return self::_query_ifso_api('deactivate_license', $license, $item_id);
	}

	public static function activate_license($license, $item_id) {
		return self::_query_ifso_api('activate_license', $license, $item_id);
	}

}
