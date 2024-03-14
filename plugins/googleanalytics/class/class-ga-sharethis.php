<?php
/**
 * Ga_Sharethis class
 *
 * Preparing request and parsing response from Sharethis Platform Api.
 *
 * @package GoogleAnalytics
 */

/**
 * Sharethis class.
 */
class Ga_Sharethis {

	/**
	 * Get body decoded from JSON.
	 *
	 * @param string $data Data string.
	 *
	 * @return mixed
	 */
	public static function get_body( $data ) {
		$body = $data->getBody();
		return json_decode( $body );
	}

	/**
	 * Create sharethis options.
	 *
	 * @param object $api_client API client.
	 *
	 * @return array
	 */
	public static function create_sharethis_options( $api_client ) {
		$data              = array();
		$parsed_url        = wp_parse_url( get_option( 'siteurl' ) );
		$domain            = $parsed_url['host'] . ( ! empty( $parsed_url['path'] ) ? $parsed_url['path'] : '' );
		$query_params      = array(
			'domain'             => $domain,
			'is_wordpress'       => true,
			'onboarding_product' => 'ga',
		);
		$response          = $api_client->call(
			'ga_api_create_sharethis_property',
			array(
				$query_params,
			)
		);
		$sharethis_options = self::get_sharethis_options( $response );
		if ( ! empty( $sharethis_options['id'] ) ) {
			add_option( Ga_Admin::GA_SHARETHIS_PROPERTY_ID, $sharethis_options['id'] );
		}
		if ( ! empty( $sharethis_options['secret'] ) ) {
			add_option( Ga_Admin::GA_SHARETHIS_PROPERTY_SECRET, $sharethis_options['secret'] );
		}

		return $data;
	}

	/**
	 * Get ShareThis options array.
	 *
	 * @param object $response Response object.
	 *
	 * @return array
	 */
	public static function get_sharethis_options( $response ) {
		$body    = self::get_body( $response );
		$options = array();
		if ( ! empty( $body ) ) {
			foreach ( $body as $key => $value ) {
				if ( '_id' === $key ) {
					$options['id'] = $value;
				} elseif ( 'secret' === $key ) {
					$options['secret'] = $value;
				} elseif ( 'error' === $key ) {
					$options['error'] = $value;
				}
			}
		} else {
			$options['error'] = 'error';
		}
		return $options;
	}

	/**
	 * Installation verification.
	 *
	 * @param object $api_client API Client object.
	 *
	 * @return void
	 */
	public static function sharethis_installation_verification( $api_client ) {
		if ( Ga_Helper::should_verify_sharethis_installation() ) {
			$query_params = array(
				'id'     => get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_ID ),
				'secret' => get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_SECRET ),
			);
			$response     = $api_client->call(
				'ga_api_sharethis_installation_verification',
				array(
					$query_params,
				)
			);
			$result       = self::get_verification_result( $response );
			if ( ! empty( $result ) ) {
				add_option( Ga_Admin::GA_SHARETHIS_VERIFICATION_RESULT, true );
			}
		}
	}

	/**
	 * Get verification result.
	 *
	 * @param object $response Response object.
	 *
	 * @return bool
	 */
	public static function get_verification_result( $response ) {
		$body = self::get_body( $response );
		if ( ! empty( $body->{'status'} ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get alerts.
	 *
	 * @param object $response Response object.
	 *
	 * @return array|mixed|object
	 */
	public static function get_alerts( $response ) {
		$body = self::get_body( $response );
		if ( false === empty( $body ) ) {
			if ( false === empty( $body['error'] ) ) {
				return (object) array( 'error' => $body['error'] );
			}

			return $body;
		} else {
			return array();
		}
	}
}
