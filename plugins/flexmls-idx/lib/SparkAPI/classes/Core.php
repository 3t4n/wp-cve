<?php
namespace SparkAPI;

class Core {

	public static $api_headers = array(
		'Accept-Encoding' => 'gzip,deflate',
		'Content-Type' => 'application/json',
		'User-Agent' => "FlexMLS WordPress Plugin/FMC_PLUGIN_VERSION",
		'X-SparkApi-User-Agent' => "flexmls-WordPress-Plugin/FMC_PLUGIN_VERSION"
	);

	public static function clear_cache( $force = false ){
		global $wpdb;
		/*----------------------------------------------------------------------
		  VERSION 3.5.9
		  New caching system implemented using only WordPress transients so we
		  need to delete all old options from previous versions that were
		  clogging up the database. This first query deletes all old options
		  using the fmc_ transient & caching system.
		----------------------------------------------------------------------*/
		$delete_query = "DELETE FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s";
		$wpdb->query( $wpdb->prepare(
			$delete_query,
			'_transient_fmc%',
			'_transient_timeout_fmc%'
		) );

		if( true === $force ){
			/*----------------------------------------------------------------------
			  The user has requested that ALL FlexMLS caches be purged so
			  we will bulk delete all newly created FlexMLS caches
			----------------------------------------------------------------------*/
			$wpdb->query( $wpdb->prepare(
				$delete_query,
				'_transient_flexmls_query_%',
				'_transient_timeout_flexmls_query%'
			) );
			delete_option( 'fmc_db_cache_key' );
		} else {
			/*----------------------------------------------------------------------
			  Just delete expired FlexMLS transients but leave current ones
			  in tact. This is just regular clean-up, not a forced cache clear.
			----------------------------------------------------------------------*/
			$time = time();
			$sql = "DELETE a, b FROM $wpdb->options a, $wpdb->options b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
				AND b.option_value < %d";
			$wpdb->query( $wpdb->prepare(
				$sql, $wpdb->esc_like( '_transient_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_' ) . '%',
				$time
			) );
		}
		return true;
	}

	public static function generate_auth_token( $retry = true ){
		if( false === ( $auth_token = get_transient( 'flexmls_auth_token' ) ) ){
			$options = get_option( 'fmc_settings' );
			if( !isset( $options[ 'api_key' ] ) || !isset( $options[ 'api_secret' ] ) ){
				return false;
			}
			$security_string = md5( $options[ 'api_secret' ] . 'ApiKey' . $options[ 'api_key' ] );
			$params = array(
				'ApiKey' => $options[ 'api_key' ],
				'ApiSig' => $security_string
			);
			$url = 'https://' . FMC_API_BASE . '/' . FMC_API_VERSION . '/session?' . build_query( $params );
			$args = array(
				'headers' => self::$api_headers
			);
			$response = wp_remote_post( $url, $args );
			if( is_wp_error( $response ) ){
				if( false === $retry ){
					//add_action( 'admin_notices', array( Core::class, 'admin_notices_api_connection_error' ) );
					\FlexMLS_IDX::write_log( $response, 'API Error in \SparkAPI\Core::auth_token, retried once - Response Was' );
				} else {
					\FlexMLS_IDX::write_log( $response, 'API Error in \SparkAPI\Core::auth_token, retrying - Response Was' );
					$auth_token = self::generate_auth_token( false );
				}
			} else {
				$json = json_decode( wp_remote_retrieve_body( $response ), true );
				if( array_key_exists( 'D', $json ) && true == $json[ 'D' ][ 'Success' ] ){
					set_transient( 'flexmls_auth_token', $json, 55 * MINUTE_IN_SECONDS );
					$auth_token = $json;
				}
			}
		}
		return $auth_token;
	}

}