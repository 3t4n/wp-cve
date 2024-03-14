<?php
/**
 * Google Analytics admin controller.
 *
 * @package GoogleAnalytics
 */

/**
 * Admin controller.
 */
class Ga_Admin_Controller extends Ga_Controller_Core {

	const ACTION_SHARETHIS_INVITE = 'ga_action_sharethis_invite';

	/**
	 * Redirects to Google oauth authentication endpoint.
	 */
	public static function ga_action_auth() {
		header( 'Location:' . Ga_Admin::api_client()->create_auth_url() );
	}

	/**
	 * Handle Sharethis invite action
	 */
	public static function ga_action_sharethis_invite() {
		if ( true === self::verify_nonce( self::ACTION_SHARETHIS_INVITE ) ) {
			// Validate email.
			$email = filter_input( INPUT_POST, 'sharethis_invite_email', FILTER_SANITIZE_EMAIL );

			if ( false === empty( $email ) ) {
				$data = array(
					'id'      => get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_ID ),
					'secret'  => get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_SECRET ),
					'product' => 'viral-notifications',
					'role'    => 'admin',
					'email'   => $email,
				);

				Ga_Admin::api_client( Ga_Admin::GA_SHARETHIS_API_ALIAS )
						->call( 'ga_api_sharethis_user_invite', array( $data ) );

				$errors = Ga_Admin::api_client( Ga_Admin::GA_SHARETHIS_API_ALIAS )->get_errors();

				if ( false === empty( $errors ) ) {
					$msg = '';
					foreach ( $errors as $error ) {
						$msg .= $error['message'];
					}
					$msg = Ga_Helper::create_url_msg( $msg, Ga_Admin::NOTICE_ERROR );
				} else {
					$msg = Ga_Helper::create_url_msg(
						__( 'An invite was sent to this email' ),
						Ga_Admin::NOTICE_SUCCESS
					);
				}
			}
		} else {
			$msg = Ga_Helper::create_url_msg(
				__( 'Invalid request.' ),
				Ga_Admin::NOTICE_ERROR
			);
		}
	}

	/**
	 * Sets accept terms option to TRUE.
	 */
	public static function ga_action_update_terms() {
		update_option( Ga_Admin::GA_SHARETHIS_TERMS_OPTION_NAME, true );

		wp_safe_redirect( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ) );
	}

	/**
	 * Enables all features option.
	 */
	public static function ga_action_enable_all_features() {
		Ga_Helper::update_option( Ga_Admin::GA_DISABLE_ALL_FEATURES, false );

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( false === empty( $page ) ) {
			$url = Ga_Helper::create_url( admin_url( 'admin.php' ), compact( 'page' ) );
		} else {
			$url = admin_url( Ga_Helper::create_url( Ga_Helper::GA_SETTINGS_PAGE_URL ) );
		}

		wp_safe_redirect( $url );
	}

	/**
	 * Disables all features option.
	 */
	public static function ga_action_disable_all_features() {
		Ga_Helper::update_option( Ga_Admin::GA_DISABLE_ALL_FEATURES, true );

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		$url = false === empty( $page ) ?
			Ga_Helper::create_url( admin_url( 'admin.php' ), compact( 'page' ) ) :
			admin_url( Ga_Helper::create_url( Ga_Helper::GA_SETTINGS_PAGE_URL ) );

		wp_safe_redirect( $url );
	}

	/**
	 * Validate data change post ajax call.
	 *
	 * @return bool
	 */
	public static function validate_ajax_data_change_post() {
		$error = 0;

		$date_range = filter_input( INPUT_POST, 'date_range', FILTER_SANITIZE_STRING );
		$metric     = filter_input( INPUT_POST, 'metric', FILTER_SANITIZE_STRING );

		if ( true === self::verify_nonce( 'ga_ajax_data_change' ) ) {
			if ( false === empty( $date_range ) ) {
				if ( false === is_string( $date_range ) ) {
					$error ++;
				}
			} else {
				$error ++;
			}

			if ( false === empty( $metric ) ) {
				if ( false === is_string( $metric ) ) {
					$error ++;
				}
			} else {
				$error ++;
			}
		} else {
			$error ++;
		}

		return 0 === $error;
	}
}
