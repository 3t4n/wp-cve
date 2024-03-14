<?php
/**
 * Contains the extending functionality
 * @since 1.7.4
 */

if ( ! function_exists( 'gglnltcs_get_client' ) ) {
	function gglnltcs_get_client() {
		global $gglnltcs_options;
        if ( ! empty( $_POST['gglnltcs_client_id'] ) ) {
            $gglnltcs_options['client_id'] = sanitize_text_field( $_POST['gglnltcs_client_id'] );
        }
        $client_id      = array_key_exists( 'client_id', $gglnltcs_options ) ? $gglnltcs_options['client_id'] : '';
        $client_secret  = array_key_exists( 'client_secret', $gglnltcs_options ) ? $gglnltcs_options['client_secret'] : '';
        $api_key        = array_key_exists( 'api_key', $gglnltcs_options ) ? $gglnltcs_options['api_key'] : '';

		require_once plugin_dir_path( __FILE__ ) .  '../google-api/autoload.php';
		$client = new Google_Client();
        $client->setApplicationName( 'Analytics by BestWebSoft' );
        $client->setClientId( $client_id );
        $client->setClientSecret( $client_secret );
        $client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );
        $client->setDeveloperKey( $api_key );
        $client->setScopes( array( 'https://www.googleapis.com/auth/analytics.readonly' ) );
        $client->setAccessType( 'offline' );
		if ( ! empty( $gglnltcs_options['token'] ) ) {
			$client->setAccessToken( $gglnltcs_options['token'] );
		}

		return $client;
	}
}

if ( ! function_exists( 'gglnltcs_get_analytics' ) ) {
	function gglnltcs_get_analytics() {
		global $gglnltcs_options;

		if ( ! isset( $gglnltcs_options['token'] ) ) {
			return;
		}

		$client = gglnltcs_get_client();

		/* Create Analytics Object */
		$analytics = new Google_Service_Analytics( $client );
		return $analytics;
	}
}