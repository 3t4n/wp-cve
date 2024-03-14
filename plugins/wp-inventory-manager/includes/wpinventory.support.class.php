<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class WPIMSupport extends WPIMCore {

	private static $version_info;

	public static function admin_support() {
		$email   = self::request( 'email', get_bloginfo( 'admin_email' ), 'email' );
		$message = self::request( 'message', '', 'textarea' );
		$error   = '';

		if ( self::request( 'support_submit' ) ) {
			if ( ! wp_verify_nonce( self::request( '_support_nonce' ), 'wpim_support' ) ) {
				$error = self::__( 'Security Error. Please try again.' );

			}

			if ( ! $error ) {
				self::send_info( $message, $email );
			}
		}

		echo '<div class="wrap inventorywrap">';

		self::header();
		echo '<h3>' . WPIMCore::__( 'WP Inventory Support' ) . '</h3>';

		echo '<div class="error"><p>';
		echo sprintf( self::__( 'Thank you for being a user of WP Inventory!
			<br>Please understand that support is provided to licensed WP Inventory Pro users.  
			<br>You are currently using the free version of WP Inventory, so please consider %supgrading to Pro before requesting support%s.' ),
			'<a target="_blank" href="https://www.wpinventory.com/wp-inventory-license">', '</a>' );
		echo '</p></div>';

		echo '<div>';
		echo '<label for="show_details" class="button button-small button-default">' . self::__( 'Show Installation Details' ) . '</label>';
		echo '</div>';
		echo '<input type="checkbox" name="show_details" id="show_details" class="toggle-switch" />';
		echo '<div class="wp_support_details toggle-container">';
		echo '<h4>' . WPIMCore::__( 'Server Info' ) . '</h4>';
		echo '<ul>';

		$versions = self::get_versions();
		foreach ( $versions AS $version ) {
			echo '<ol>' . esc_attr($version['name']) . ': ' . esc_attr($version['version']) . '</ol>';
		}
		echo '</ul>';

		echo '<h4>' . WPIMCore::__( 'Active Plugins' ) . '</h4>';
		echo '<ol>';

		$plugins = self::get_active_plugins();
		foreach ( $plugins AS $file => $plugin ) {
			echo '<li data-plugin-name="' . $plugin['name'] . '">' . $plugin['name'] . ' - ' . $plugin['version'] . $plugin['url'] . '</li>';
		}

		echo '</ol>';
		echo '</div>';

		echo '<h3>' . self::__( 'Request Support' ) . '</h3>';
		echo '<form id="inventory_support" method="post" action="">';
		echo '<p>' . self::__( 'Complete the details below to submit a support request.' ) . '</p>';
		echo '<p><label for="email">' . self::__( 'Email Address:' ) . '</label>';
		echo '<input type="text" class="widefat" name="email" value="' . esc_attr( $email ) . '"></p>';
		echo '<p><label for="message">' . self::__( 'Please describe your issue or request as completely as possible.' ) . '</label><br>';
		echo '<textarea class="widefat" style="min-height: 150px;" name="message">' . esc_textarea( $message ) . '</textarea>';
		echo '</p>';
		wp_nonce_field( 'wpim_support', '_support_nonce' );

		echo '<p><input type="submit" class="button-primary" name="support_submit" value="' . self::__( 'Send Support Request' ) . '" /></p>';
		echo '</form>';
		echo '</div>';
	}

	private static function get_versions() {
		global $wp_version, $wpdb;

		$versions   = [];
		$versions[] = [
			'name'    => 'PHP',
			'version' => phpversion()
		];

		$versions[] = [
			'name'    => 'MySQL',
			'version' => $wpdb->dbh->server_info
		];

		$versions[] = [
			'name'    => 'WordPress',
			'version' => $wp_version
		];

		$versions[] = [
			'name'    => 'OS',
			'version' => self::get_os()
		];

		return $versions;
	}

	private static function get_active_plugins() {
		$active_plugins = [];
		$plugins        = get_plugins();
		foreach ( $plugins AS $file => $plugin ) {
			if ( is_plugin_active( $file ) ) {
				$url = ( ! empty( $plugin['PluginURI'] ) ) ? ' (' . $plugin['PluginURI'] . ')' : '';

				$active_plugins[] = [
					'name'    => $plugin['Name'],
					'version' => $plugin['Version'],
					'url'     => $url,
					'file'    => $file
				];
			}
		}

		return $active_plugins;
	}

	private static function get_os() {
		$os = PHP_OS;

		if ( FALSE !== stripos( $os, 'darwin' ) ) {
			$os = 'Macintosh';
		} else if ( 0 === strpos( $os, 'WIN' ) ) {
			$os = str_replace( 'WIN', 'Windows ', $os );
		}

		return $os;
	}

	private static function get_additional() {
		$config = self::$config->get_all();
		if ( empty( $config ) ) {
			return [];
		}

		$additional = [];
		$find       = 'bGljZW5zZV9rZXk=';
		foreach ( $config AS $key => $value ) {
			if ( FALSE !== stripos( $key, base64_decode( $find ) ) ) {
				$additional[ $key ] = $value;
			}
		}

		return $additional;
	}

	public static function ajax_send_support() {
		$nonce   = self::request( 'nonce' );
		$email   = self::request( 'email', '', 'email' );
		$message = self::request( 'message', '', 'textarea' );

		$json = [
			'success'  => 0,
			'errors'   => '',
			'messages' => ''
		];

		if ( ! wp_verify_nonce( $nonce, 'wpim_support' ) ) {
			$json['errors'] = 'Security Error.  Please try again.';
		} else {
			$response             = self::send_info( $message, $email );
			$json['version_info'] = self::$version_info;
			if ( FALSE === $response ) {
				$json['errors'] = self::__( 'There was a problem connecting to our server.  If this problem persists, please contact us via e-mail.' );
			} else if ( ! $response ) {
				$json['success']  = 1;
				$json['messages'] = self::__( 'Your support request has been sent.  We will contact you soon!' );
			} else {
				$json['messages'] = $response;
			}
		}

		echo json_encode( $json );
		die();
	}

	private static function send_info( $message = '', $email = '', $type = 'support' ) {

		if ( ! $email ) {
			$email = get_option( 'admin_email' );
		}

		$versions     = self::get_versions();
		$plugins      = self::get_active_plugins();
		$additional   = self::get_additional();

		$url = WPIMAPI::API_URL;

		add_filter( 'http_request_timeout', [ __CLASS__, 'http_request_timeout' ] );
//		$url = 'http://127.0.0.1/wp_inventory';

		$payload = [
			'api_call' => [
				'method'   => 'wpim_support',
				'versions' => $versions,
				'plugins'  => $plugins,
				'keys'     => $additional,
				'licenses' => '',
				'site_url' => get_site_url(),
				'email'    => $email,
				'message'  => $message
			]
		];

		$response = wp_remote_post( $url, [ 'body' => $payload ] );

		if ( is_wp_error( $response ) ) {
			echo '<br>ERROR: ' . $response->get_error_message() . ' (' . $response->get_error_code() . ')';

			return FALSE;
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( '200' != $response_code ) {
			echo '<br>The connection to our server failed with a ' . $response_code . ' response!';

			return FALSE;
		}

		$body = wp_remote_retrieve_body( $response );

		$response = @json_decode( $body );

		$return = '';
		if ( ! empty( $response->errors ) ) {
			foreach ( $response->errors AS $error ) {
				$return .= $error;
			}
		}

		if ( $response->version_info ) {
			self::$version_info = $response->version_info;
		}

		return $return;
	}

	public static function http_request_timeout( $time ) {
		// Default timeout is 5

		return 20;
	}
}
