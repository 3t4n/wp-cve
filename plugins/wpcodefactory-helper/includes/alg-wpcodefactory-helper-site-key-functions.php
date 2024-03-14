<?php
/**
 * WPFactory Helper - Admin Site Key Functions
 *
 * @version 1.5.7
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_wpcfh_get_site_key' ) ) {
	/**
	 * alg_wpcfh_get_site_key.
	 *
	 * @version 1.2.2
	 * @since   1.0.0
	 */
	function alg_wpcfh_get_site_key( $item_slug ) {
		$keys = get_option( 'alg_site_keys', array() );
		return ( isset( $keys[ $item_slug ] ) ? trim( $keys[ $item_slug ] ) : '' );
	}
}

if ( ! function_exists( 'alg_wpcfh_update_site_key_status' ) ) {
	/**
	 * alg_wpcfh_update_site_key_status.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function alg_wpcfh_update_site_key_status( $item_slug, $server_response, $client_data = '' ) {
		$statuses = get_option( 'alg_site_keys_statuses', array() );
		if ( in_array( $client_data, array( 'NO_RESPONSE', 'SERVER_ERROR' ) ) && alg_wpcfh_is_site_key_valid( $item_slug ) ) {
			// we don't want to overwrite valid licence response with server errors
			return;
		}
		$statuses[ $item_slug ] = array(
			'server_response' => $server_response,
			'client_data'     => $client_data,
			'time_checked'    => time(),
		);
		update_option( 'alg_site_keys_statuses', $statuses );
	}
}

if ( ! function_exists( 'alg_wpcfh_get_site_key_status' ) ) {
	/**
	 * alg_wpcfh_get_site_key_status.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wpcfh_get_site_key_status( $item_slug ) {
		$statuses = get_option( 'alg_site_keys_statuses', array() );
		return ( isset( $statuses[ $item_slug ] ) ? $statuses[ $item_slug ] : false );
	}
}

if ( ! function_exists( 'alg_wpcfh_is_site_key_valid' ) ) {
	/**
	 * alg_wpcfh_is_site_key_valid.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wpcfh_is_site_key_valid( $item_slug ) {
		if ( false !== ( $site_key_status = alg_wpcfh_get_site_key_status( $item_slug ) ) ) {
			return ( isset( $site_key_status['server_response']->status ) && $site_key_status['server_response']->status );
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'alg_wpcfh_get_site_key_status_message' ) ) {
	/**
	 * alg_wpcfh_get_site_key_status_message.
	 *
	 * @version 1.5.6
	 * @since   1.0.0
	 *
	 * @todo    (dev) `SERVER_ERROR`: not used?
	 * @todo    (dev) No key set: `sprintf( __( 'Key can be set <a href="%s">here</a>.', 'wpcodefactory-helper' ), admin_url( 'options-general.php?page=wpcodefactory-helper&item_slug=' . $item_slug ) )`
	 * @todo    (dev) check `false === $site_key_status && '' == alg_wpcfh_get_site_key( $item_slug )`
	 */
	function alg_wpcfh_get_site_key_status_message( $item_slug ) {
		$site_key_status = alg_wpcfh_get_site_key_status( $item_slug );
		if ( false === $site_key_status && '' == alg_wpcfh_get_site_key( $item_slug ) ) {
			$site_key_status = array();
			$site_key_status['client_data'] = 'EMPTY_SITE_KEY';
		}
		if ( isset( $site_key_status['server_response']->error->message ) ) {
			return $site_key_status['server_response']->error->message;
		} else {
			if ( isset( $site_key_status['client_data'] ) ) {
				switch ( $site_key_status['client_data'] ) {
					case 'EMPTY_SITE_KEY':
						return __( 'No key set.', 'wpcodefactory-helper' ) . ' ' .
							sprintf( __( 'To get the key, please visit <a target="_blank" href="%s">your account page at %s</a>.', 'wpcodefactory-helper' ),
								alg_wpcodefactory_helper()->update_server . '/my-account/downloads/', alg_wpcodefactory_helper()->update_server_text );
					case 'NO_RESPONSE':
						return sprintf( __( 'No response from server. Please <a href="%s">try again</a> later.', 'wpcodefactory-helper' ), add_query_arg( 'alg_check_item_site_key', $item_slug ) );
					case 'SERVER_ERROR':
						return sprintf( __( 'Server error. Please <a href="%s">try again</a> later.', 'wpcodefactory-helper' ), add_query_arg( 'alg_check_item_site_key', $item_slug ) );
				}
			}
			return __( 'Error: Unexpected error.', 'wpcodefactory-helper' );
		}
	}
}

if ( ! function_exists( 'alg_wpcfh_check_site_key' ) ) {
	/**
	 * alg_wpcfh_check_site_key.
	 *
	 * @version 1.5.7
	 * @since   1.0.0
	 */
	function alg_wpcfh_check_site_key( $item_slug ) {
		if ( '' != ( $site_key = alg_wpcfh_get_site_key( $item_slug ) ) ) {
			$url = add_query_arg( array(
				'check_site_key' => $site_key,
				'item_slug'      => $item_slug,
				'site_url'       => alg_wpcodefactory_helper()->site_url,
			), alg_wpcodefactory_helper()->update_server );
			if ( $response = alg_wpcodefactory_helper()->get_response_from_url( $url ) ) {
				$server_response = json_decode( $response );
				$client_data     = '';
			} else {
				$server_response = array();
				$client_data     = 'NO_RESPONSE';
			}
		} else {
			$server_response = array();
			$client_data     = 'EMPTY_SITE_KEY';
		}
		alg_wpcfh_update_site_key_status( $item_slug, $server_response, $client_data );
	}
}
