<?php

/**
 * Handle UTM Grabber
 * By Haktan Suren
 * https://wordpress.org/plugins/handl-utm-grabber/
 */
if ( ! class_exists( 'BWFAN_Compatibility_With_Handle_UTM_Grabber' ) ) {
	class BWFAN_Compatibility_With_Handle_UTM_Grabber {

		public function __construct() {
			add_filter( 'bwfan_ab_default_checkout_nice_names', array( $this, 'bwfan_set_handle_utm_field' ), 9, 1 );
			add_action( 'bwfan_ab_handle_checkout_data_externally', array( $this, 'bwfan_set_handle_utm_cookie' ), 9, 1 );

			/** additional data in case of handle_utm_grabber plugin is active for abandoned cart **/
			add_filter( 'bwfan_ab_change_checkout_data_for_external_use', array( $this, 'bwfan_populate_utm_grabber_data_cart' ), 999, 1 );
		}

		/**
		 * Set handle_utm_grabber key in checkout field nice name
		 *
		 * @param $fields
		 *
		 * @return mixed
		 */
		public function bwfan_set_handle_utm_field( $fields ) {
			$fields['handle_utm_grabber'] = __( 'Handle UTM Grabber' );

			return $fields;
		}

		/**
		 * Set handle UTM data in cookies on cart restore
		 *
		 * @param $checkout_data
		 */
		public function bwfan_set_handle_utm_cookie( $checkout_data ) {
			if ( ! isset( $checkout_data['fields']['handle_utm_grabber'] ) || empty( $checkout_data['fields']['handle_utm_grabber'] ) ) {
				return;
			}
			$handle_utm_grabber = $checkout_data['fields']['handle_utm_grabber'];
			$field_array        = array( 'utm_source', 'utm_campaign', 'utm_term', 'utm_medium', 'utm_content' );
			foreach ( $handle_utm_grabber as $utm_key => $value ) {
				if ( ! in_array( $utm_key, $field_array, true ) ) {
					continue;
				}

				$cookie_field = $handle_utm_grabber[ $utm_key ];
				$domain       = isset( $_SERVER["SERVER_NAME"] ) ? $_SERVER["SERVER_NAME"] : '';
				if ( strtolower( substr( $domain, 0, 4 ) ) == 'www.' ) {
					$domain = substr( $domain, 4 );
				}
				if ( substr( $domain, 0, 1 ) != '.' && $domain != "localhost" && $domain != "handl-sandbox" ) {
					$domain = '.' . $domain;
				}

				setcookie( $utm_key, $cookie_field, time() + 60 * 60 * 24 * 30, '/', $domain );
			}
		}

		/**
		 * passing grabber utm data to cart abandoned
		 */
		public function bwfan_populate_utm_grabber_data_cart( $data ) {
			$utm_keys = [
				'utm_campaign',
				'utm_source',
				'utm_term',
				'utm_medium',
				'utm_content',
				'gclid',
				'handl_original_ref',
				'handl_device',
				'handl_browser',
				'handl_landing_page',
				'handl_ip',
				'handl_ref',
				'handl_url',
			];

			$handle_utm_grabber_data = array();
			foreach ( $utm_keys as $key ) {
				if ( ! isset( $_COOKIE[ $key ] ) || empty( $_COOKIE[ $key ] ) ) {
					continue;
				}
				$handle_utm_grabber_data[ $key ] = $_COOKIE[ $key ];
			}

			$data['handle_utm_grabber'] = $handle_utm_grabber_data;

			return apply_filters( 'bwfan_external_handl_utm_grabber_data', $data );
		}
	}

	if ( function_exists( 'bwfan_is_utm_grabber_active' ) && bwfan_is_utm_grabber_active() ) {
		new BWFAN_Compatibility_With_Handle_UTM_Grabber();
	}
}
