<?php

/**
 * Tries to do an API call to LinkPizza
 * If the token is invalid which is represented by 404 in the API retrieve new token and try again. Fail after 1 retry.
 *
 * @since 4.7
 * @param string  $url The URL for the API call.
 * @param array   $parameters Parameters for the API call.
 * @param boolean $retries Number of retries for the request.
 * @return array The result of the API call.
 */
function pzz_do_oauth_call_with_refresh_check( $url, $parameters, $retries ) {
	if ( $retries < 2 ) {
		$client            = new OAuth2\Client( PZZ_OIDC_CLIENT_ID, '', OAuth2\Client::AUTH_TYPE_URI, ABSPATH . WPINC . '/certificates/ca-bundle.crt' );
		$access_oidc_token = get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME );
		try {
			$client->setAccessToken( $access_oidc_token );
			$client->setAccessTokenType( 1 );
			$response = $client->fetch( $url, $parameters );
			if ( 200 === $response['code'] ) {
				return $response['result'];
			} else {
				$refresh_oidc_token = get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME );
				if ( ( 401 === $response['code'] || 403 === $response['code'] ) && '' !== $refresh_oidc_token ) {
					$callback_url  = admin_url( 'admin.php?page=linkpizza-manager&pzz_callback=oauth' );
					$params        = array(
						'refresh_token' => $refresh_oidc_token,
						'redirect_uri'  => $callback_url,
					);
					$extra_headers = array( 'Host' => PZZ_OIDC_HOST_HEADER );
					$response      = $client->getAccessToken( PZZ_OIDC_TOKEN_ENDPOINT, 'refresh_token', $params, $extra_headers );
					if ( 200 === $response['code'] && isset( $response['result'] ) && isset( $response['result']['access_token'] ) ) {
						$access_oidc_token  = $response['result']['access_token'];
						$refresh_oidc_token = $response['result']['refresh_token'];

						if ( get_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME ) !== false ) {
							update_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME, $access_oidc_token, 'yes' );
						} else {
							add_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME, $access_oidc_token, '', 'yes' );
						}

						if ( get_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME ) !== false ) {
							update_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME, $refresh_oidc_token, 'yes' );
						} else {
							add_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME, $refresh_oidc_token, '', 'yes' );
						}
						return pzz_do_oauth_call_with_refresh_check( $url, $parameters, $retries + 1 );
					} else {
						update_option( PZZ_OIDC_ACCESS_TOKEN_OPTION_NAME, '', 'yes' );
						update_option( PZZ_OIDC_REFRESH_TOKEN_OPTION_NAME, '', 'yes' );
						pzz_write_log( 'Silently failed to connect to the LinkPizza API: Access token not present' );
					}
				}
			}
		} catch ( \OAuth2\Exception $e ) {
			echo 'Caught exception: ',  esc_html( $e->getMessage() ), "\n";
		}
	} else {
		pzz_write_log( 'Silently failed to connect to the LinkPizza API: kept getting 404s' );
	}
}

/**
 * Utility method to write to the WordPress error log.
 *
 * TODO: Maybe move this to a seperate log utility?
 *
 * @since 4.7
 *
 * @param string $log The message to be logged.
 * @return void
 */
function pzz_write_log( $log ) {
	if ( true === WP_DEBUG ) {
		if ( is_array( $log ) || is_object( $log ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
			error_log( print_r( $log, true ) );
		} else {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( $log );
		}
	}
}
