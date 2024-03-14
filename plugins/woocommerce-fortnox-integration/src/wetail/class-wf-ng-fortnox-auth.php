<?php

namespace src\Wetail;

if ( !defined( 'ABSPATH' ) ) die();

use src\fortnox\WF_Plugin;

if ( ! class_exists( __NAMESPACE__ . "\WF_NG_Fortnox_auth" ) ):
	class WF_NG_Fortnox_auth {
		static $transient_name = 'fortnox_organisation_auth_secret';
		static $salt = 'super-salt_34928562387rgsdijfhsdfjigzkhnzfciustk4hg';
		static $request_timeout = 4 * 24 * 3600;
		static $auth_endpoint = 'https://api-stage.wetail.io/integrations/fortnox/auth';

		public static function init() {
			if ( isset( $_REQUEST['fortnox_auth_code'] ) ) {
				add_action( 'admin_notices',
					function () {
						$class   = false;
						$message = false;
						switch ( $_REQUEST['fortnox_auth_code'] ) {
							case '1':
								$class   = 'notice notice-error';
								$message = __( "Fortnox auth link expired, please try again.", WF_Plugin::TEXTDOMAIN );
								break;
							case '2':
								$class   = 'notice notice-success';
								$message = __( "Successful fortnox auth", WF_Plugin::TEXTDOMAIN );
								break;
						}
						if ( $message && $class ) {
							printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
						}
					} );
			}
		}

		public static function request_for_auth( $org_number, $redirect ) {
			$callback_url = get_home_url() . '/wp-json/woocommerce_fortnox/organisation_callback';
			$secret       = md5( /*time() . */ self::$salt . $callback_url . $org_number . $redirect );
			fortnox_write_log( "Requesting endpoint, args: " . json_encode( func_get_args() ) );
			set_transient( self::$transient_name, $secret, self::$request_timeout );
			delete_option( 'fortnox_organization_number_auth_result' );
			fortnox_write_log( "Requesting endpoint, secret: " . json_encode( $secret ) );
			$result = wp_remote_post( self::$auth_endpoint,
				[

					'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
					'body'        => json_encode( [
						'secret'     => $secret,
						'org_number' => $org_number,
						'callback'   => $callback_url,
						'redirect'   => $redirect,
						'email'      => get_bloginfo('admin_email'),
					] ),
					'method'      => 'POST',
					'data_format' => 'body',
				]
			);
			fortnox_write_log( "Requesting endpoint, result: " . json_encode( $result ) );

			return ( isset( $result['response'] ) && isset( $result['response']['code'] ) && 201 == $result['response']['code'] );
		}
	}
endif;
