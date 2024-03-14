<?php

namespace FloatingButton\Update;

defined( 'ABSPATH' ) || exit;

use FloatingButton\Dashboard\DashboardHelper;
use FloatingButton\Dashboard\Link;
use FloatingButton\WOW_Plugin;

class Checker {
	public function __construct() {
		add_filter( WOW_Plugin::PREFIX . '_admin_filter_file', [ $this, 'filter_file' ], 10, 2 );
		add_action( 'admin_init', array( $this, 'plugin_updater' ), 0 );
		add_action( 'admin_init', array( $this, 'register_option' ) );
		add_action( 'admin_init', array( $this, 'activate_license' ) );

		add_action( 'admin_init', array( $this, 'deactivate_license' ) );

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );

		register_deactivation_hook( WOW_Plugin::file(), array( $this, 'deactivate_plugin' ) );
	}


	public function filter_file( $file, $current ) {

		if ( ( $current === 'list' || $current === 'settings' ) && ! self::key() ) {
			return DashboardHelper::get_file( 'license', 'pages' );
		}

		return $file;
	}

	public static function key(): bool {

		$license = get_option( 'wow_license_key_' . WOW_Plugin::PREFIX );
		$status  = get_option( 'wow_license_status_' . WOW_Plugin::PREFIX );

		return ! empty( $license ) && $status === 'valid';
	}

	public function plugin_updater(): void {

		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		$license_key = trim( get_option( 'wow_license_key_' . WOW_Plugin::PREFIX ) );

		$edd_updater = new Plugin_Updater(
			WOW_Plugin::info('store'),
			WOW_Plugin::file(),
			array(
				'version' => WOW_Plugin::info('version'),
				'license' => $license_key,
				'item_id' => WOW_Plugin::info('item_id'),
				'author'  => WOW_Plugin::info('author'),
				'beta'    => false,
			)
		);


	}


	public function register_option(): void {
		register_setting( 'wow_license_' . WOW_Plugin::PREFIX, 'wow_license_key_' . WOW_Plugin::PREFIX, [
			$this,
			'sanitize_license'
		] );
	}

	public function sanitize_license( $new ) {
		$old = get_option( 'wow_license_key_' . WOW_Plugin::PREFIX );
		if ( $old && $old !== $new ) {
			delete_option( 'wow_license_status_' . WOW_Plugin::PREFIX );
		}

		return $new;
	}


	public function activate_license() {

		$action = WOW_Plugin::PREFIX . '_license_activation';

		if ( ! isset( $_POST[ $action ] ) ) {
			return false;
		}

		if ( ! check_admin_referer( WOW_Plugin::PREFIX . '_nonce', $action ) ) {
			return false;
		}


		$license = ! empty( $_POST[ 'wow_license_key_' . WOW_Plugin::PREFIX ] ) ? sanitize_text_field( $_POST[ 'wow_license_key_' . WOW_Plugin::PREFIX ] ) : '';

		if ( ! $license ) {
			return;
		}

		$license = trim( $license );


		$api_params = array(
			'edd_action'  => 'activate_license',
			'license'     => $license,
			'item_id'     => WOW_Plugin::info('item_id'),
			'item_name'   => rawurlencode( WOW_Plugin::info('name') ), // the name of our product in EDD
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		$response = wp_remote_post( WOW_Plugin::info('store'),
			[
				'timeout'   => 60,
				'sslverify' => false,
				'body'      => $api_params,
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'floating-button' );
			}
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch ( $license_data->error ) {

					case 'expired':
						$message = sprintf(
						/* translators: the license key expiration date */
							__( 'Your license key expired on %s.', 'floating-button' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'floating-button' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'floating-button' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'floating-button' );
						break;

					case 'item_name_mismatch':
						/* translators: the plugin name */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'floating-button' ), WOW_Plugin::info('name') );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'floating-button' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'floating-button' );
						break;
				}
			}
		}


		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$redirect = Link::create( [
				'tab'           => 'license',
				'sl_activation' => 'false',
				'prefix'        => WOW_Plugin::PREFIX,
				'messanger'     => rawurlencode( $message )
			] );


			wp_safe_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"
		if ( 'valid' === $license_data->license ) {
			update_option( 'wow_license_key_' . WOW_Plugin::PREFIX, $license );
		}
		update_option( 'wow_license_status_' . WOW_Plugin::PREFIX, $license_data->license );
		wp_safe_redirect( Link::create( [ 'tab' => 'list' ] ) );
		exit();

	}

	public function deactivate_license() {
		$action = WOW_Plugin::PREFIX . '_license_deactivated';

		if ( ! isset( $_POST[ $action ] ) ) {
			return false;
		}

		if ( ! check_admin_referer( WOW_Plugin::PREFIX . '_nonce', $action ) ) {
			return false;
		}

		$license = ! empty( $_POST[ 'wow_license_key_' . WOW_Plugin::PREFIX ] ) ? sanitize_text_field( $_POST[ 'wow_license_key_' . WOW_Plugin::PREFIX ] ) : '';


		if ( ! $license ) {
			return;
		}

		$license = trim( $license );

		$api_params = array(
			'edd_action'  => 'deactivate_license',
			'license'     => $license,
			'item_id'     => WOW_Plugin::info('item_id'),
			'item_name'   => rawurlencode( WOW_Plugin::info('name') ),
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		$response = wp_remote_post(
			WOW_Plugin::info('store'),
			array(
				'timeout'   => 60,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);


		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'floating-button' );
			}

			$redirect = Link::create( [
				'tab'           => 'license',
				'sl_activation' => 'false',
				'prefix'        => WOW_Plugin::PREFIX,
				'messanger'     => rawurlencode( $message )
			] );

			wp_safe_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( 'deactivated' === $license_data->license || 'failed' === $license_data->license ) {
			delete_option( 'wow_license_status_' . WOW_Plugin::PREFIX, );
		}

		wp_safe_redirect( Link::create( [ 'tab' => 'license' ] ) );
		exit();

	}

	public function deactivate_plugin(): void {

		$license = trim( get_option( 'wow_license_key_' . WOW_Plugin::PREFIX ) );

		$api_params = array(
			'edd_action'  => 'deactivate_license',
			'license'     => $license,
			'item_id'     => WOW_Plugin::info('item_id'),
			'item_name'   => rawurlencode( WOW_Plugin::info('name') ),
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		$response = wp_remote_post(
			WOW_Plugin::info('store'),
			array(
				'timeout'   => 60,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);


		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' || 'failed' === $license_data->license ) {
			delete_option( 'wow_license_status_' . WOW_Plugin::PREFIX, );
		}
	}

	public function admin_notices(): void {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['messanger'] ) && WOW_Plugin::PREFIX === $_GET['prefix'] ) {

			switch ( $_GET['sl_activation'] ) {

				case 'false':
					$message = urldecode( $_GET['messanger'] );
					?>
                    <div class="error">
                        <p><?php echo wp_kses_post( $message ); ?></p>
                    </div>
					<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;

			}
		}
	}


}