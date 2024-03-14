<?php
/**
 * Google Analytics admin class.
 *
 * @package GoogleAnalytics
 */

use Google\Client;

/**
 * Admin class.
 */
class Ga_Admin {

	const GA_WEB_PROPERTY_ID_OPTION_NAME                = 'googleanalytics_web_property_id';
	const GA_EXCLUDE_ROLES_OPTION_NAME                  = 'googleanalytics_exclude_roles';
	const GA_SHARETHIS_TERMS_OPTION_NAME                = 'googleanalytics_sharethis_terms';
	const GA_HIDE_TERMS_OPTION_NAME                     = 'googleanalytics_hide_terms';
	const GA_VERSION_OPTION_NAME                        = 'googleanalytics_version';
	const GA_SELECTED_ACCOUNT                           = 'googleanalytics_selected_account';
	const GA_OAUTH_AUTH_CODE_OPTION_NAME                = 'googleanalytics_oauth_auth_code';
	const GA_OAUTH_AUTH_TOKEN_OPTION_NAME               = 'googleanalytics_oauth_auth_token';
	const GA_ACCOUNT_DATA_OPTION_NAME                   = 'googleanalytics_account_data';
	const GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME       = 'googleanalytics_web_property_id_manually';
	const GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME = 'googleanalytics_web_property_id_manually_value';
	const GA_SHARETHIS_PROPERTY_ID                      = 'googleanalytics_sherethis_property_id';
	const GA_SHARETHIS_PROPERTY_SECRET                  = 'googleanalytics_sherethis_property_secret';
	const GA_SHARETHIS_VERIFICATION_RESULT              = 'googleanalytics_sherethis_verification_result';
	const MIN_WP_VERSION                                = '3.8';
	const GA_SHARETHIS_API_ALIAS                        = 'sharethis';
	const GA_DISABLE_ALL_FEATURES                       = 'googleanalytics_disable_all_features';
	const GA_HEARTBEAT_API_CACHE_UPDATE                 = false;
	const NOTICE_SUCCESS                                = 'success';
	const NOTICE_WARNING                                = 'warning';
	const NOTICE_ERROR                                  = 'error';

	/**
	 * Instantiate API client.
	 *
	 * @param string $type Type string.
	 *
	 * @return Ga_Lib_Google_Api_Client|null
	 */
	public static function api_client( $type = '' ) {
		if ( self::GA_SHARETHIS_API_ALIAS === $type ) {
			$instance = Ga_Lib_Sharethis_Api_Client::get_instance();
		} else {
			$instance = Ga_Lib_Google_Api_Client::get_instance();
			$token    = Ga_Helper::get_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
			$token    = true === empty( $token ) ? get_option( 'ga4-token' ) : $token;

			try {
				if ( ! empty( $token ) ) {
					$token = json_decode( $token, true );
					$instance->set_access_token( $token );
				}
			} catch ( Exception $e ) {
				Ga_Helper::ga_oauth_notice( $e->getMessage() );
			}
		}

		return $instance;
	}

	/**
	 * Initializes plugin's options during plugin activation process.
	 */
	public static function activate_googleanalytics() {
		add_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, Ga_Helper::GA_DEFAULT_WEB_ID );
		add_option( self::GA_EXCLUDE_ROLES_OPTION_NAME, wp_json_encode( array() ) );
		add_option( self::GA_VERSION_OPTION_NAME );
		add_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		add_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		add_option( self::GA_ACCOUNT_DATA_OPTION_NAME );
		add_option( self::GA_SELECTED_ACCOUNT );
		add_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, '0' );
		add_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
		add_option( self::GA_DISABLE_ALL_FEATURES );
		update_option( self::GA_SHARETHIS_TERMS_OPTION_NAME, true );
		update_option( 'googleanalytics_fresh', 'true' );
		Ga_Cache::add_cache_options();
	}

	/**
	 * Deletes plugin's options during plugin activation process.
	 */
	public static function deactivate_googleanalytics() {
		delete_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		delete_option( self::GA_EXCLUDE_ROLES_OPTION_NAME );
		delete_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		delete_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		delete_option( self::GA_ACCOUNT_DATA_OPTION_NAME );
		delete_option( self::GA_SELECTED_ACCOUNT );
		delete_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
		delete_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
		delete_option( self::GA_DISABLE_ALL_FEATURES );
		delete_option( Ga_SupportLogger::LOG_OPTION );
		delete_option( 'googleanalytics_version' );
		delete_option( 'googleanalytics_optimize_code' );
		delete_option( 'googleanalytics_ip_anonymization' );
		delete_option( 'googleanalytics-hide-review' );
		delete_option( 'googleanalytics_enable_debug_mode' );
		delete_option( 'googleanalytics_gdpr_config' );
		delete_option( 'googleanalytics_demographic' );
		delete_option( 'googleanalytics_demo_data' );
		delete_option( 'googleanalytics_demo_date' );
		delete_option( 'googleanalytics_send_data' );
		delete_option( 'googleanalytics_hide_terms' );
		delete_option( 'googleanalytics_sharethis_terms' );
		delete_option( 'googleanalytics_sherethis_property_id' );
		delete_option( 'googleanalytics_sherethis_property_secret' );

		delete_option( 'googleanalytics-ga4-gdpr' );
		delete_option( 'googleanalytics-ga4-ip-anon' );
		delete_option( 'googleanalytics-ga4-demo' );
		delete_option( 'googleanalytics-ga4-exclude-roles' );
		delete_option( 'googleanalytics-ga4-optimize' );
		delete_option( 'ga4-token' );
		delete_option( 'googleanalytics-ga4-property' );
		delete_option( self::GA_SHARETHIS_TERMS_OPTION_NAME );
		Ga_Cache::delete_cache_options();
	}

	/**
	 * Deletes plugin's options during plugin uninstallation process.
	 */
	public static function uninstall_googleanalytics() {
		delete_option( self::GA_SHARETHIS_TERMS_OPTION_NAME );
		delete_option( self::GA_HIDE_TERMS_OPTION_NAME );
		delete_option( self::GA_VERSION_OPTION_NAME );
		delete_option( self::GA_SHARETHIS_PROPERTY_ID );
		delete_option( self::GA_SHARETHIS_PROPERTY_SECRET );
		delete_option( self::GA_SHARETHIS_VERIFICATION_RESULT );
	}

	/**
	 * Do actions during plugin load.
	 */
	public static function loaded_googleanalytics() {
		self::update_googleanalytics();
	}

	/**
	 * Update hook fires when plugin is being loaded.
	 */
	public static function update_googleanalytics() {
		$version            = get_option( self::GA_VERSION_OPTION_NAME );
		$installed_version  = get_option( self::GA_VERSION_OPTION_NAME, '2.4.0' );
		$old_property_value = Ga_Helper::get_option( 'web_property_id' );

		if ( version_compare( $installed_version, GOOGLEANALYTICS_VERSION, 'eq' ) ) {
			return;
		}

		if ( version_compare( $installed_version, GOOGLEANALYTICS_VERSION, 'lt' ) ) {

			if ( ! empty( $old_property_value ) ) {
				Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME, $old_property_value );
				Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, 1 );
				delete_option( 'web_property_id' );
			}
		}

		update_option( self::GA_VERSION_OPTION_NAME, GOOGLEANALYTICS_VERSION );
	}

	/**
	 * Preupdate excluding rules.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return false|string
	 */
	public static function preupdate_exclude_roles( $new_value, $old_value ) {
		if ( false === Ga_Helper::are_features_enabled() ) {
			return '';
		}

		return wp_json_encode( $new_value );
	}

	/**
	 * Pre-update hook for preparing JSON structure.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return mixed
	 */
	public static function preupdate_selected_account( $new_value, $old_value ) {
		$data = null;
		if ( ! empty( $new_value ) ) {
			$data = explode( '_', $new_value );

			if ( ! empty( $data[1] ) ) {
				Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, $data[1] );
			}
		}

		return wp_json_encode( $data );
	}

	/**
	 * Pre-update disable all features.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return mixed
	 */
	public static function preupdate_disable_all_features( $new_value, $old_value ) {
		if ( 'on' === $old_value ) {
			Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, false );
		}

		return $new_value;
	}

	/**
	 * Pre-update optimize code.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return mixed|string
	 */
	public static function preupdate_optimize_code( $new_value, $old_value ) {
		if ( ! empty( $new_value ) ) {
			$new_value = sanitize_text_field( wp_unslash( $new_value ) );
		}

		return $new_value;
	}

	/**
	 * Pre-update IP Anonymization.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return mixed
	 */
	public static function preupdate_ip_anonymization( $new_value, $old_value ) {
		return $new_value;
	}

	/**
	 * Pre-update Enable GA Debugging.
	 *
	 * @param string $new_value New value.
	 * @param string $old_value Old value.
	 *
	 * @return mixed
	 */
	public static function preupdate_enable_debug_mode( $new_value, $old_value ) {
		return $new_value;
	}

	/**
	 * Registers plugin's settings.
	 */
	public static function admin_init_googleanalytics() {
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		register_setting( GA_NAME, self::GA_EXCLUDE_ROLES_OPTION_NAME );
		register_setting( GA_NAME, self::GA_SELECTED_ACCOUNT );
		register_setting( GA_NAME, self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
		register_setting( GA_NAME, self::GA_DISABLE_ALL_FEATURES );
		register_setting( GA_NAME, 'googleanalytics_optimize_code' );
		register_setting( GA_NAME, 'googleanalytics_ip_anonymization' );
		register_setting( GA_NAME, 'googleanalytics_enable_debug_mode' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-property' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-optimize' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-exclude-roles' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-demo' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-ip-anon' );
		register_setting( GA_NAME . 'ga4', 'googleanalytics-ga4-gdpr' );
		add_filter( 'pre_update_option_' . self::GA_EXCLUDE_ROLES_OPTION_NAME, 'Ga_Admin::preupdate_exclude_roles', 1, 2 );
		add_filter( 'pre_update_option_' . self::GA_SELECTED_ACCOUNT, 'GA_Admin::preupdate_selected_account', 1, 2 );
		add_filter( 'pre_update_option_googleanalytics_optimize_code', 'Ga_Admin::preupdate_optimize_code', 1, 2 );
		add_filter( 'pre_update_option_googleanalytics_ip_anonymization', 'Ga_Admin::preupdate_ip_anonymization', 1, 2 );
		add_filter( 'pre_update_option_googleanalytics_enable_debug_mode', 'Ga_Admin::preupdate_enable_debug_mode', 1, 2 );
	}

	/**
	 * Builds plugin's menu structure.
	 */
	public static function admin_menu_googleanalytics() {
		$gdpr = get_option( 'googleanalytics_gdpr_config' );

		if ( current_user_can( 'manage_options' ) ) {
			add_menu_page( 'Google Analytics', 'Google Analytics', 'manage_options', 'googleanalytics', 'Ga_Admin::statistics_page_googleanalytics', 'dashicons-chart-line', 1000 );
			add_submenu_page( 'googleanalytics', 'Google Analytics', __( 'Dashboard' ), 'manage_options', 'googleanalytics', 'Ga_Admin::statistics_page_googleanalytics' );
			add_submenu_page( 'googleanalytics', 'Google Analytics', __( 'Settings' ), 'manage_options', 'googleanalytics/settings', 'Ga_Admin::options_page_googleanalytics' );

			if ( ! empty( $gdpr ) ) {
				add_submenu_page(
					'googleanalytics',
					'Google Analytics',
					__( 'GDPR' ),
					'manage_options',
					'googleanalytics/gdpr',
					'Ga_Admin::gdpr_page_googleanalytics'
				);
			}
		}
	}

	/**
	 * Prepares and displays plugin's stats page.
	 */
	public static function statistics_page_googleanalytics() {

		if ( ! Ga_Helper::is_wp_version_valid() || ! Ga_Helper::is_php_version_valid() ) {
			return false;
		}

		$data = self::get_stats_page();
		Ga_View_Core::load(
			'statistics',
			array(
				'data' => $data,
			)
		);

		self::display_api_errors();
	}

	/**
	 * Prepares and displays plugin's settings page.
	 */
	public static function options_page_googleanalytics() {
		if ( false === Ga_Helper::is_wp_version_valid() || false === Ga_Helper::is_php_version_valid() ) {
			return false;
		}

		if ( true === Ga_Helper::are_features_enabled() && true === Ga_Helper::is_curl_disabled() ) {
			echo wp_kses_post(
				Ga_Helper::ga_wp_notice(
					__(
						'Looks like cURL is not configured on your server. In order to authenticate your Google Analytics account and display statistics, cURL is required. Please contact your server administrator to enable it, or manually enter your Tracking ID.'
					),
					'warning'
				)
			);
		}
		/**
		 * Keeps data to be extracted as variables in the view.
		 *
		 * @var array $data
		 */
		$data = array();

		$data[ self::GA_WEB_PROPERTY_ID_OPTION_NAME ]                = get_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		$data[ self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME ] = get_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME, '' );
		$data[ self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ]       = get_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, '0' );
		$data[ self::GA_DISABLE_ALL_FEATURES ]                       = get_option( self::GA_DISABLE_ALL_FEATURES );

		$roles = Ga_Helper::get_user_roles();
		$saved = json_decode( get_option( self::GA_EXCLUDE_ROLES_OPTION_NAME ), true );

		$tmp = array();
		if ( false === empty( $roles ) ) {
			foreach ( $roles as $role ) {
				$role_id = Ga_Helper::prepare_role_id( $role );
				$tmp[]   = array(
					'name'    => $role,
					'id'      => $role_id,
					'checked' => ( ! empty( $saved[ $role_id ] ) && 'on' === $saved[ $role_id ] ),
				);
			}
		}
		$data['roles'] = $tmp;

		if ( Ga_Helper::is_authorized() ) {
			$data['ga_accounts_selector'] = self::get_accounts_selector();
			$data['auth_button']          = self::get_auth_button( 'reauth' );
		} else {
			$data['popup_url']   = self::get_auth_popup_url();
			$data['auth_button'] = self::get_auth_button( 'auth' );
		}

		$data['debug_modal'] = self::get_debug_modal();
		$data['debug_info']  = Ga_SupportLogger::$debug_info;

		// Sanitize error.
		$error = filter_input( INPUT_GET, 'err', FILTER_SANITIZE_STRING );

		if ( false === empty( $error ) ) {
			switch ( $error ) {
				case 1:
					$data['error_message'] = Ga_Helper::ga_oauth_notice( 'There was a problem with Google Oauth2 authentication process. Please verify your site has a valid SSL Certificate in place and is using the HTTPS protocol.' );
					break;
				case 2:
					$data['error_message'] = Ga_Helper::ga_wp_notice(
						'Authentication code is incorrect.',
						'error',
						true
					);
					break;
			}
		}

		$universal = '' !== get_option( 'googleanalytics_oauth_auth_token', '' );

		if ( false !== $universal ) {
			Ga_View_Core::load(
				'old-page',
				array(
					'data'    => $data,
					'tooltip' => Ga_Helper::get_tooltip(),
				)
			);
		} else {
			Ga_View_Core::load(
				'page',
				array(
					'data'    => $data,
					'tooltip' => Ga_Helper::get_tooltip(),
				)
			);
		}

		self::display_api_errors();
	}

	/**
	 * Prepares and displays plugin's gdpr page.
	 */
	public static function gdpr_page_googleanalytics() {

		if ( false === Ga_Helper::is_wp_version_valid() || false === Ga_Helper::is_php_version_valid() ) {
			return false;
		}
		if ( true === Ga_Helper::are_features_enabled() && true === Ga_Helper::is_curl_disabled() ) {
			echo wp_kses_post( Ga_Helper::ga_wp_notice( __( 'Looks like cURL is not configured on your server. In order to authenticate your Google Analytics account and display statistics, cURL is required. Please contact your server administrator to enable it, or manually enter your Tracking ID.' ), 'warning' ) );
		}

		$vendor_data = self::get_vendors();
		$vendors     = $vendor_data['vendors'];
		$purposes    = array_column( $vendor_data['purposes'], 'name', 'id' );

		include plugin_dir_path( __FILE__ ) . '../view/templates/gdpr-config.php';
	}

	/**
	 * Prepares and returns a plugin's URL to be opened in a popup window
	 * during Google authentication process.
	 *
	 * @return mixed
	 */
	public static function get_auth_popup_url() {
		return admin_url( Ga_Helper::create_url( Ga_Helper::GA_SETTINGS_PAGE_URL, array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_auth' ) ) );
	}

	/**
	 * Prepares and returns Google Account's dropdown code.
	 *
	 * @return string
	 */
	public static function get_accounts_selector() {
		$selected = Ga_Helper::get_selected_account_data();
		$selector = json_decode( get_option( self::GA_ACCOUNT_DATA_OPTION_NAME ), true );

		if ( false === Ga_Helper::is_code_manually_enabled() && true === empty( $selector ) ) {
			echo wp_kses_post( Ga_Helper::ga_wp_notice( "Hi there! It seems like we weren't able to locate a Google Analytics account attached to your email account. Can you please register for Google Analytics and then deactivate and reactivate the plugin?", 'warning' ) );
		}

		return Ga_View_Core::load(
			'ga-accounts-selector',
			array(
				'selector'             => $selector,
				'selected'             => $selected ? implode( '_', $selected ) : null,
				'add_manually_enabled' => Ga_Helper::is_code_manually_enabled() || Ga_Helper::is_all_feature_disabled(),
			),
			true
		);
	}

	/**
	 * Adds JS scripts for the settings page.
	 */
	public static function enqueue_ga_scripts() {
		$property_id = get_option( 'googleanalytics_sherethis_property_id', true );
		$secret      = get_option( 'googleanalytics_sherethis_property_secret', true );
		$config      = wp_json_encode( get_option( 'googleanalytics_gdpr_config' ) );

		wp_register_script(
			GA_NAME . '-page-js',
			Ga_Helper::get_plugin_url_with_correct_protocol() . '/js/' . GA_NAME . '_page.js',
			array( 'jquery' ),
			time(),
			false
		);

		wp_enqueue_script( GA_NAME . '-page-js' );
		wp_localize_script(
			GA_NAME . '-page-js',
			'googleAnalyticsPage',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'ga_page_nonce' ),
				'settingsURL' => admin_url( 'admin.php?page=googleanalytics/settings' ),
			)
		);

		// @deprecated - Phase this out in favor of wp_localize_script.
		wp_add_inline_script(
			GA_NAME . '-page-js',
			'var siteAdminUrl = \'' .
			admin_url() .
			'\'; var gaGdprConfig = \'' .
			$config .
			'\'; var ga_demo_nonce = "' .
			wp_create_nonce( 'ga_demo_nonce' ) .
			'"; var ga_property_id = "' . $property_id .
			'"; var ga_secret_id = "' .
			$secret .
			'";'
		);
	}

	/**
	 * Adds CSS plugin's scripts.
	 */
	public static function enqueue_ga_css() {
		wp_enqueue_style(
			GA_NAME . '-css',
			Ga_Helper::get_plugin_url_with_correct_protocol() . '/css/' . GA_NAME . '.css',
			false,
			time(),
			'all'
		);

		wp_enqueue_style(
			GA_NAME . '-additional-css',
			Ga_Helper::get_plugin_url_with_correct_protocol() . '/css/ga_additional.css',
			false,
			GOOGLEANALYTICS_VERSION,
			'all'
		);

		if ( true === Ga_Helper::is_wp_old() ) {
			wp_enqueue_style(
				GA_NAME . '-old-wp-support-css',
				Ga_Helper::get_plugin_url_with_correct_protocol() . '/css/ga_old_wp_support.css',
				false,
				GOOGLEANALYTICS_VERSION,
				'all'
			);
		}

		wp_enqueue_style(
			GA_NAME . '-modal-css',
			Ga_Helper::get_plugin_url_with_correct_protocol() . '/css/ga_modal.css',
			false,
			GOOGLEANALYTICS_VERSION,
			'all'
		);
	}

	/**
	 * Enqueues dashboard JS scripts.
	 */
	private static function enqueue_dashboard_scripts() {
		wp_enqueue_script(
			GA_NAME . '-dashboard-js',
			Ga_Helper::get_plugin_url_with_correct_protocol() . '/js/' . GA_NAME . '_dashboard.js',
			array( 'jquery' ),
			GOOGLEANALYTICS_VERSION,
			false
		);
	}

	/**
	 * Enqueues plugin's JS and CSS scripts.
	 */
	public static function enqueue_scripts() {
		$domain    = str_replace( 'http://', '', str_replace( 'https://', '', str_replace( '/wp-admin/', '', admin_url() ) ) );
		$st_prop   = get_option( self::GA_SHARETHIS_PROPERTY_ID );
		$st_secret = get_option( self::GA_SHARETHIS_PROPERTY_SECRET );

		if ( Ga_Helper::is_dashboard_page() || Ga_Helper::is_plugin_page() ) {
			wp_register_script(
				GA_NAME . '-js',
				Ga_Helper::get_plugin_url_with_correct_protocol() . '/js/' . GA_NAME . '.js',
				array( 'jquery' ),
				GOOGLEANALYTICS_VERSION,
				false
			);
			wp_enqueue_script( GA_NAME . '-js' );

			wp_register_script( 'googlecharts', 'https://www.gstatic.com/charts/loader.js', null, 1, false );
			wp_enqueue_script( 'googlecharts' );
			wp_add_inline_script( GA_NAME . '-js', 'var ga_demo_nonce = "' . wp_create_nonce( 'ga_demo_nonce' ) . '";' );

			if ( empty( $st_prop ) || empty( $st_secret ) ) {
				wp_register_script( 'googlecreateprop', Ga_Helper::get_plugin_url_with_correct_protocol() . '/js/googleanalytics_createprop.js', array( 'jquery', 'wp-util' ), time(), false );
				wp_enqueue_script( 'googlecreateprop' );
				wp_add_inline_script(
					'googlecreateprop',
					'
					var gaNonce = "' . wp_create_nonce( 'googleanalyticsnonce' ) . '";
					var gasiteURL = "' . $domain . '";
					var gaAdminEmail = "' . get_option( 'admin_email' ) . '";
					var gaFresh = "' . get_option( 'googleanalytics_fresh', 'false' ) . '";'
				);
			}

			self::enqueue_ga_css();
		}

		if ( Ga_Helper::is_dashboard_page() ) {
			self::enqueue_dashboard_scripts();
		}

		if ( Ga_Helper::is_plugin_page() ) {
			self::enqueue_ga_scripts();
		}
	}

	/**
	 * Prepares plugin's statistics page and return HTML code.
	 *
	 * @return string HTML code
	 */
	public static function get_stats_page() {
		$age_chart    = null;
		$boxes        = null;
		$chart        = null;
		$device_chart = null;
		$gender_chart = null;
		$labels       = null;
		$sources      = null;

		if ( false === Ga_Helper::is_all_feature_disabled() ) {
			[ $chart, $age_chart, $device_chart, $gender_chart, $boxes, $labels, $sources ] = self::generate_stats_data();
		}

		return Ga_Helper::get_chart_page(
			'stats',
			compact(
				'age_chart',
				'boxes',
				'chart',
				'device_chart',
				'gender_chart',
				'labels',
				'sources'
			)
		);
	}

	/**
	 * Prepare required PHP version warning.
	 */
	public static function admin_notice_googleanalytics_php_version() {
		echo wp_kses_post( Ga_Helper::ga_wp_notice( 'Cannot use Google Analytics plugin. PHP version ' . phpversion() . ' is to low. Required PHP version: ' . Ga_Helper::PHP_VERSION_REQUIRED, 'error' ) );
	}

	/**
	 * Shows plugin's notice on the admin area.
	 */
	public static function admin_notice_googleanalytics() {
		if ( ( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && true === Ga_Helper::is_plugin_page() ) ||
			( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && ! get_option( self::GA_HIDE_TERMS_OPTION_NAME ) ) ) {
			$current_url = Ga_Helper::get_current_url();
			$url         = ( strstr(
				$current_url,
				'?'
			) ? $current_url . '&' : $current_url . '?' ) . http_build_query( array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_update_terms' ) );
			Ga_View_Core::load(
				'ga-notice',
				array(
					'url' => $url,
				)
			);
		}

		$settings_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING );

		if ( false === empty( $settings_updated ) && Ga_Helper::is_plugin_page() ) {
			echo wp_kses_post( Ga_Helper::ga_wp_notice( __( 'Settings saved' ), self::NOTICE_SUCCESS ) );
		}

		if ( true === boolval( Ga_Helper::get_option( self::GA_DISABLE_ALL_FEATURES ) ) ) {
			echo wp_kses(
				Ga_Helper::ga_wp_notice(
					__( 'You have disabled all extra features, click here to enable Dashboards, Viral Alerts and Google API.' ),
					'warning',
					false,
					array(
						'url'   => admin_url(
							Ga_Helper::create_url(
								Ga_Helper::GA_SETTINGS_PAGE_URL,
								array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_enable_all_features' )
							)
						),
						'label' => __( 'Enable' ),
					)
				),
				array(
					'button' => array(
						'class'   => array(),
						'onclick' => array(),
					),
					'div'    => array(
						'class' => array(),
					),
					'p'      => array(),
				)
			);
		}
	}

	/**
	 * Prepare required WP version warning
	 */
	public static function admin_notice_googleanalytics_wp_version() {
		echo wp_kses(
			Ga_Helper::ga_wp_notice(
				'Google Analytics plugin requires at least WordPress version ' . self::MIN_WP_VERSION,
				'error'
			),
			array(
				'button' => array(
					'class'   => array(),
					'onclick' => array(),
				),
				'div'    => array(
					'class' => array(),
				),
				'p'      => array(),
			)
		);
	}

	/**
	 * Hides plugin's notice
	 */
	public static function admin_notice_hide_googleanalytics() {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		update_option( self::GA_HIDE_TERMS_OPTION_NAME, true );
	}

	/**
	 * Adds GA dashboard widget only for administrators.
	 */
	public static function add_dashboard_device_widget() {
		if (true === Ga_Helper::is_administrator() && true === Ga_Helper::is_dashboard_page()) {
			wp_add_dashboard_widget(
				'ga-dashboard-widget',
				__( 'Google Analytics Dashboard' ),
				'Ga_Helper::add_ga_dashboard_widget'
			);
		}
	}

	/**
	 * Adds plugin's actions
	 */
	public static function add_actions() {
		add_action( 'admin_init', 'Ga_Admin::admin_init_googleanalytics' );
		add_action( 'admin_menu', 'Ga_Admin::admin_menu_googleanalytics' );
		add_action( 'admin_enqueue_scripts', 'Ga_Admin::enqueue_scripts' );
		add_action( 'wp_dashboard_setup', 'Ga_Admin::add_dashboard_device_widget' );
		add_action( 'wp_ajax_ga_ajax_data_change', 'Ga_Admin::ga_ajax_data_change' );
		add_action( 'wp_ajax_ga_ajax_hide_review', 'Ga_Admin::ga_ajax_hide_review' );
		add_action( 'wp_ajax_save_ga4_property_selection', 'Ga_Admin::save_ga4_property_selection' );
		add_action( 'wp_ajax_save_ga4_final_setup', 'Ga_Admin::save_ga4_final_setup' );
		add_action( 'wp_ajax_save_view_id', 'Ga_Admin::save_view_id' );
		add_action( 'wp_ajax_ga_ajax_enable_gdpr', 'Ga_Admin::ga_ajax_gdpr_enable' );
		add_action( 'wp_ajax_ga_ajax_enable_demographic', 'Ga_Admin::ga_ajax_enable_demo' );
		add_action( 'wp_ajax_ga_ajax_sign_out', 'Ga_Admin::ga_ajax_sign_out' );
		add_action( 'wp_ajax_ga4_ajax_sign_out', 'Ga_Admin::ga4_ajax_sign_out' );
		add_action( 'admin_notices', 'Ga_Admin::admin_notice_googleanalytics' );
		add_action( 'heartbeat_tick', 'Ga_Admin::run_heartbeat_jobs' );
		add_action( 'wp_ajax_googleanalytics_send_debug_email', 'Ga_SupportLogger::send_email' );
		add_action( 'wp_ajax_set_ga_credentials', 'Ga_Admin::create_ga_property' );

		if ( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && ! get_option( self::GA_HIDE_TERMS_OPTION_NAME ) ) {
			add_action( 'wp_ajax_googleanalytics_hide_terms', 'Ga_Admin::admin_notice_hide_googleanalytics' );
		}
	}

	/**
	 * Runs jobs
	 *
	 * @param string $response  Response string.
	 * @param string $screen_id Screen ID string.
	 */
	public static function run_heartbeat_jobs( $response, $screen_id = '' ) {

		if ( self::GA_HEARTBEAT_API_CACHE_UPDATE ) {
			// Disable cache for ajax request.
			self::api_client()->set_disable_cache( true );

			// Try to regenerate cache if needed.
			self::generate_stats_data();
		}
	}

	/**
	 * Adds plugin's filters
	 */
	public static function add_filters() {
		add_filter( 'plugin_action_links', 'Ga_Admin::ga_action_links', 10, 5 );
	}

	/**
	 * Adds new action links on the plugin list.
	 *
	 * @param array  $actions     Actions array.
	 * @param string $plugin_file Plugin file path string.
	 *
	 * @return mixed
	 */
	public static function ga_action_links( $actions, $plugin_file ) {
		if ( basename( $plugin_file ) === GA_NAME . '.php' ) {
			array_unshift( $actions, '<a href="' . esc_url( get_admin_url( null, Ga_Helper::GA_SETTINGS_PAGE_URL ) ) . '">' . __( 'Settings' ) . '</a>' );
		}

		return $actions;
	}

	/**
	 * Init OAuth.
	 *
	 * @return false|void
	 */
	public static function init_oauth() {
		$ua_add = filter_input( INPUT_GET, 'ua', FILTER_SANITIZE_STRING );

		if ( 't' !== $ua_add ) {
			return false;
		}

		$token = get_option( 'ga4-token' );

		if ( false === empty( $token ) ) {
			self::api_client()->set_access_token( json_decode( $token, true ) );
			// Get accounts data.
			$account_summaries = self::api_client()->call( 'ga_api_account_summaries' );
			self::save_ga_account_summaries( $account_summaries->get_data() );
			update_option( self::GA_SELECTED_ACCOUNT, '' );

			wp_safe_redirect( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ) );
		}
	}

	/**
	 * Save access token.
	 *
	 * @param Ga_Lib_Api_Response $response      API response.
	 * @param string              $refresh_token Refresh token string.
	 *
	 * @return boolean
	 */
	public static function save_access_token( $response, $refresh_token = '' ) {
		$access_token = $response->get_data();
		if ( ! empty( $access_token ) ) {
			$access_token['created'] = time();
		} else {
			return false;
		}

		if ( ! empty( $refresh_token ) ) {
			$access_token['refresh_token'] = $refresh_token;
		}

		return update_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME, wp_json_encode( $access_token ) );
	}

	/**
	 * Saves Google Analytics account data.
	 *
	 * @param array $data Data array.
	 *
	 * @return array
	 */
	public static function save_ga_account_summaries( $data ) {
		$return = array();
		if ( ! empty( $data['items'] ) ) {
			foreach ( $data['items'] as $item ) {
				$tmp         = array();
				$tmp['id']   = $item['id'];
				$tmp['name'] = $item['name'];
				if ( is_array( $item['webProperties'] ) ) {
					foreach ( $item['webProperties'] as $property ) {
						$profiles = array();
						if ( is_array( $property['profiles'] ) ) {
							foreach ( $property['profiles'] as $profile ) {
								$profiles[] = array(
									'id'   => $profile['id'],
									'name' => $profile['name'],
								);
							}
						}

						$tmp['webProperties'][] = array(
							'internalWebPropertyId' => $property['internalWebPropertyId'],
							'webPropertyId'         => $property['id'],
							'name'                  => $property['name'],
							'profiles'              => $profiles,
						);
					}
				}

				$return[] = $tmp;
			}

			update_option( self::GA_ACCOUNT_DATA_OPTION_NAME, wp_json_encode( $return ) );
		} else {
			update_option( self::GA_ACCOUNT_DATA_OPTION_NAME, '' );
		}
		update_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, '' );

		return $return;
	}

	/**
	 * Handle AJAX data for the GA dashboard widget.
	 */
	public static function ga_ajax_data_change() {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		if ( Ga_Admin_Controller::validate_ajax_data_change_post() ) {
			$date_range = filter_input( INPUT_POST, 'date_range', FILTER_SANITIZE_STRING );
			$metric     = filter_input( INPUT_POST, 'metric', FILTER_SANITIZE_STRING );

			echo wp_kses_post( Ga_Helper::get_ga_dashboard_widget_data_json( $date_range, $metric, false, true ) );
		} else {
			echo wp_json_encode( array( 'error' => __( 'Invalid request.' ) ) );
		}

		wp_die();
	}

	/**
	 * Displays API error messages.
	 *
	 * @param string $alias Alias string.
	 */
	public static function display_api_errors( $alias = '' ) {
		$errors = self::api_client( $alias )->get_errors();

		if ( false === empty( get_option( 'ga4-token' ) ) && true === empty( get_option( 'googleanalytics-view-id' ) ) ) {
			return;
		};

		if ( ! empty( $errors ) ) {
			foreach ( $errors as $error ) {
				echo wp_kses_post( Ga_Notice::get_message( $error ) );
			}
		}
	}

	/**
	 * Gets dashboard data.
	 *
	 * @return array
	 */
	public static function generate_stats_data() {
		$selected    = Ga_Helper::get_selected_account_data( true );

		if ( true === empty( $selected ) ) {
			$view_id = get_option( 'googleanalytics-view-id', '' );

			$selected = array( 'view_id' => $view_id );
		}

		$update_data = self::check_data_date();

		$current_period = Ga_Helper::get_date_range_from_request();

		$current_period['start'] = false === empty( $current_period['from'] ) ?
			$current_period['from'] : gmdate( 'Y-m-d', strtotime( '-1 week' ) );
		$current_period['end']   = false === empty( $current_period['to'] ) ?
			$current_period['to'] : gmdate( 'Y-m-d', strtotime( 'now' ) );

		$previous_period = Ga_Helper::get_previous_period_for_dates( $current_period['start'], $current_period['end'] );

		$period_in_days = Ga_Helper::get_period_in_days( $current_period['start'], $current_period['end'] );

		$date_ranges = Ga_Stats::set_date_ranges(
			$current_period['start'],
			$current_period['end'],
			$previous_period['start'],
			$previous_period['end']
		);

		$query_params  = Ga_Stats::get_query( 'main_chart', $selected['view_id'], $date_ranges );

		$stats_data    = self::api_client()->call(
			'ga_api_data',
			array( $query_params )
		);

		$gender_params = Ga_Stats::get_query( 'gender', $selected['view_id'], $date_ranges );
		$gender_data   = self::api_client()->call(
			'ga_api_data',
			array( $gender_params )
		);
		$age_params    = Ga_Stats::get_query( 'age', $selected['view_id'], $date_ranges );
		$age_data      = self::api_client()->call(
			'ga_api_data',
			array( $age_params )
		);
		$device_params = Ga_Stats::get_query( 'device', $selected['view_id'], $date_ranges );
		$device_data   = self::api_client()->call(
			'ga_api_data',
			array( $device_params )
		);

		$boxes_data   = self::api_client()->call(
			'ga_api_data',
			array( Ga_Stats::get_query( 'boxes', $selected['view_id'] ) )
		);
		$sources_data = self::api_client()->call(
			'ga_api_data',
			array( Ga_Stats::get_query( 'sources', $selected['view_id'] ) )
		);

		$chart           = ! empty( $stats_data ) ? Ga_Stats::get_chart( $stats_data->get_data(), $period_in_days ) : array();
		$device_chart    = false === empty( $device_data ) ? Ga_Stats::get_device_chart( $device_data->get_data() ) : array();
		$gender_chart    = ! empty( $gender_data ) ? Ga_Stats::get_gender_chart( $gender_data->get_data() ) : array();
		$age_chart       = ! empty( $age_data ) ? Ga_Stats::get_age_chart( $age_data->get_data() ) : array();
		$boxes           = ! empty( $boxes_data ) ? Ga_Stats::get_boxes( $boxes_data->get_data() ) : array();
		$last_chart_date = ! empty( $chart ) ? $chart['date'] : strtotime( 'now' );

		unset( $chart['date'] );
		$labels  = array(
			'thisWeek'  => gmdate( 'M d, Y', strtotime( '-6 day', $last_chart_date ) ) . ' - ' . gmdate( 'M d, Y', $last_chart_date ),
			'thisMonth' => gmdate( 'M d, Y', strtotime( '-29 day', $last_chart_date ) ) . ' - ' . gmdate( 'M d, Y', $last_chart_date ),
		);
		$sources = ! empty( $sources_data ) ? Ga_Stats::get_sources( $sources_data->get_data() ) : array();

		// Add gender/age data for default period (1 week ago vs 2 weeks ago).
		if ( $update_data ) {
			$gender_params = Ga_Stats::get_query( 'gender', $selected['view_id'] );
			$gender_data   = self::api_client()->call(
				'ga_api_data',
				array( $gender_params )
			);
			$age_params    = Ga_Stats::get_query( 'age', $selected['view_id'] );
			$age_data      = self::api_client()->call(
				'ga_api_data',
				array( $age_params )
			);

			$gender_chart = ! empty( $gender_data ) ? Ga_Stats::get_gender_chart( $gender_data->get_data() ) : array();
			$age_chart    = ! empty( $age_data ) ? Ga_Stats::get_age_chart( $age_data->get_data() ) : array();

			self::update_demo_data(
				$gender_chart,
				$age_chart
			);
		}

		return array( $chart, $age_chart, $device_chart, $gender_chart, $boxes, $labels, $sources );
	}

	/**
	 * Update demo data.
	 *
	 * @param array|bool $gender_response Gender response array.
	 * @param array|bool $age_response    Age response array.
	 *
	 * @return void
	 */
	private static function update_demo_data( $gender_response = false, $age_response = false ) {
		$x = 0;

		$demo_send_data = array();
		if ( $gender_response && $age_response ) {
			foreach ( $age_response as $type => $amount ) {
				$demo_send_data['age'][ $type ] = intval( $amount );
				$x ++;
			}
			foreach ( $gender_response as $type => $amount ) {
				$demo_send_data['gender'][ ucfirst( $type ) ] = intval( $amount );
				$x ++;
			}
		}

		// Add data for send.
		update_option( 'googleanalytics_demo_data', wp_json_encode( $demo_send_data ) );

		// Trigger send.
		update_option( 'googleanalytics_send_data', 'true' );
	}

	/**
	 * Check if we should send batch of demo data.
	 *
	 * @return bool
	 */
	private static function check_data_date() {
		$demo_enabled = get_option( 'googleanalytics_demographic' );
		$demo_date    = get_option( 'googleanalytics_demo_date' );
		$demo_date    = ! empty( $demo_date ) ? strtotime( $demo_date ) : '';
		$current_date = gmdate( 'Y-m-d' );
		$thirty_date  = '' !== $demo_date ? gmdate( 'Y-m-d', strtotime( '+1 month', $demo_date ) ) : '';

		if ( empty( $demo_enabled ) || ! $demo_enabled ) {
			return false;
		}

		if ( '' !== $demo_date && $thirty_date <= $current_date ) {
			return true;
		} elseif ( '' === $demo_date ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns auth or re-auth button
	 *
	 * @param string $button_type Button type.
	 *
	 * @return string
	 */
	public static function get_auth_button( $button_type ) {
		return Ga_View_Core::load(
			'ga-auth-button',
			array(
				'label'       => 'auth' === $button_type ? 'Authenticate with Google' : 'Re-authenticate with Google',
				'button_type' => $button_type,
				'url'         => self::get_auth_popup_url(),
				'manually_id' => get_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ),
			),
			true
		);
	}

	/**
	 * Returns debug modal
	 *
	 * @return string
	 */
	public static function get_debug_modal() {
		return Ga_View_Core::load(
			'ga-debug-modal',
			array(
				'debug_info'         => Ga_SupportLogger::$debug_info,
				'debug_help_message' => Ga_SupportLogger::$debug_help_message,
			),
			true
		);
	}

	/**
	 * Ajax hide review.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return void
	 */
	public static function ga_ajax_hide_review( $post ) {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		if ( true === Ga_Controller_Core::verify_nonce( 'ga_ajax_data_change' ) ) {
			update_option( 'googleanalytics-hide-review', true );
		}

		wp_send_json_success( 'hidden' );
	}

	/**
	 * Ajax property selection for ga4.
	 *
	 * @return void
	 */
	public static function save_ga4_property_selection() {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		$property = filter_input( INPUT_POST, 'property', FILTER_SANITIZE_STRING );
		$view_id  = filter_input( INPUT_POST, 'view_id', FILTER_SANITIZE_STRING );

		if ( false === empty( $view_id ) ) {
			update_option( 'googleanalytics-view-id', $view_id );
		}

		if ( false === empty( $property ) ) {
			update_option( 'googleanalytics-ga4-property', $property );

			wp_send_json_success( $property );
		}

		wp_send_json_error( 'property not saved' );
	}

	/**
	 * Ajax final ga4 setup.
	 *
	 * @return void
	 */
	public static function save_ga4_final_setup()
	{
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		$optimize      = 'on' === filter_input(INPUT_POST, 'optimize', FILTER_SANITIZE_STRING);
		$exclude_roles = filter_input(INPUT_POST, 'exclude_roles', FILTER_SANITIZE_STRING);
		$enable_demo   = 'on' === filter_input(INPUT_POST, 'enable_demo', FILTER_SANITIZE_STRING);
		$ip_anon       = 'on' === filter_input(INPUT_POST, 'ip_anon', FILTER_SANITIZE_STRING);
		$enable_gdpr   = 'on' === filter_input(INPUT_POST, 'enable_gdpr', FILTER_SANITIZE_STRING);
		$worked        = '';

		if (false === empty($optimize)) {
			update_option('googleanalytics-ga4-optimize', $optimize);
			$worked .= 'optimize worked : ';
		}


		if (false === empty($exclude_roles)) {
			$exclude_roles = explode(',', $exclude_roles);
			$exclude_roles_array = [];

			foreach($exclude_roles as $exclude_role) {
				$exclude_roles_array[$exclude_role] = 'on';
			}
			update_option('googleanalytics-ga4-exclude-roles', $exclude_roles_array);
			$worked .= 'exclude roles worked : ';
		} else {
			update_option('googleanalytics-ga4-exclude-roles', []);
		}

		if (false === empty($enable_demo)) {
			update_option('googleanalytics-ga4-demo', 'on');
			$worked .= 'enable demo worked : ';
		} else {
			update_option('googleanalytics-ga4-demo', '');
		}

		if (false === empty($ip_anon)) {
			update_option('googleanalytics-ga4-ip-anon', 'on');
			$worked .= 'ip anon worked : ';
		} else {
			update_option('googleanalytics-ga4-ip-anon', '');
		}

		if (false === empty($enable_gdpr)) {
			update_option('googleanalytics-ga4-gdpr', 'on');
			$worked .= 'gdpr worked';
		} else {
			update_option('googleanalytics-ga4-gdpr', '');
		}

		if (false === empty($worked)) {
			wp_send_json_success($worked);
		}

		wp_send_json_error('final setup not saved');
	}

	/**
	 * Ajax save viewID.
	 *
	 * @return void
	 */
	public static function save_view_id() {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		}

		$view_id = filter_input( INPUT_POST, 'view_id', FILTER_SANITIZE_STRING );

		if ( false === empty( $view_id ) ) {
			update_option( 'googleanalytics-view-id', $view_id );
		} else {
			update_option( 'googleanalytics-view-id', '' );
		}
	}

	/**
	 * GA: Ajax callback for GDPR Enable.
	 *
	 * @param object $post Post object.
	 *
	 * @return void
	 */
	public static function ga_ajax_gdpr_enable( $post ) {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		$post = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

		array_walk_recursive(
			$post['config'],
			function ( &$value ) {
				$value = filter_var( trim( $value ), FILTER_SANITIZE_STRING );
			}
		);

		if ( true === empty( $post['config'] ) ) {
			wp_send_json_error( 'No config found.' );
		}

		update_option( 'googleanalytics_gdpr_config', $post['config'] );

		wp_send_json_success( 'gdpr_on' );
	}

	/**
	 * GA: Ajax callback for Demo Enable.
	 *
	 * @param object $post Post object.
	 *
	 * @return void
	 */
	public static function ga_ajax_enable_demo( $post ) {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		check_ajax_referer( 'ga_demo_nonce', 'nonce' );

		$enabled = 'true' === filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING );

		update_option( 'googleanalytics_demographic', $enabled );
		update_option( 'googleanalytics-ga4-demo', 'on' );

		wp_send_json_success( 'demo_on' );
	}

	/**
	 * GA: Ajax callback for signing out of GA UA.
	 *
	 * @param object $post Post object.
	 *
	 * @return void
	 */
	public static function ga_ajax_sign_out( $post ) {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		check_ajax_referer( 'ga_page_nonce', 'nonce' );

		update_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME, '' );
		update_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME, '' );

		wp_send_json_success();
	}

	/**
	 * GA: Ajax callback for signing out of GA4.
	 *
	 * @param object $post Post object.
	 *
	 * @return void
	 */
	public static function ga4_ajax_sign_out( $post ) {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		check_ajax_referer( 'ga_page_nonce', 'nonce' );

		delete_option( 'googleanalytics-ga4-gdpr' );
		delete_option( 'googleanalytics-ga4-ip-anon' );
		delete_option( 'googleanalytics-ga4-demo' );
		delete_option( 'googleanalytics-ga4-exclude-roles' );
		delete_option( 'googleanalytics-ga4-optimize' );
		delete_option( 'ga4-token' );
		delete_option( 'googleanalytics-ga4-property' );

		wp_send_json_success();
	}

	/**
	 * New property creation method.
	 */
	public static function create_ga_property() {
		if ( false === current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'user not authorized' );
		};

		check_ajax_referer( 'googleanalyticsnonce', 'nonce' );

		$property_id = filter_input( INPUT_POST, 'propid', FILTER_SANITIZE_STRING );

		$secret = filter_input( INPUT_POST, 'secret', FILTER_SANITIZE_STRING );

		if ( true === empty( $property_id ) || true === empty( $secret ) ) {
			wp_send_json_error( 'Set credentials failed.' );
		}

		$secret      = sanitize_text_field( wp_unslash( $secret ) );
		$property_id = sanitize_text_field( wp_unslash( $property_id ) );

		update_option( self::GA_SHARETHIS_PROPERTY_ID, $property_id );
		update_option( self::GA_SHARETHIS_PROPERTY_SECRET, $secret );
	}

	/**
	 * Helper function to get vendors.
	 *
	 * @return array Array of vendors.
	 */
	private static function get_vendors() {
		$response = wp_remote_get( 'https://vendor-list.consensu.org/v2/vendor-list.json' );

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Get the ga4 client info.
	 *
	 * @return Client
	 * @throws \Google\Exception Exception thrown.
	 */
	public function getGa4Client(): Client {
		$client = new Client();
		$client->setApplicationName('Google Analytics Plugin');
		$client->setScopes(
			array( 'https://www.googleapis.com/auth/analytics.readonly' )
		);
		$client->setAuthConfig(GOOGLE_APPLICATION_CREDENTIALS);
		$client->setAccessType('offline');
		$redirect_uri = 'https://sharethis.com/google-analytics-setup/';
		$client->setRedirectUri($redirect_uri);
		$client->setPrompt('consent');
		$token_info = get_option('ga4-token');

		if (false === empty($token_info)) {
			$access_token = json_decode($token_info, true);
			$client->setAccessToken($access_token);
		}

		// If there is no previous token or it's expired.
		if ( $client->isAccessTokenExpired() ) {
			// Refresh the token if possible, else fetch a new one.
			if ( $client->getRefreshToken() ) {
				$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );

				update_option( 'ga4-token', wp_json_encode( $client->getAccessToken() ) );

				$this->token = $client->getAccessToken();
			} else {
				// Request authorization from the user.
				$auth_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

				// Exchange authorization code for an access token.
				if (false === empty($auth_code)) {
					$access_token = $client->fetchAccessTokenWithAuthCode($auth_code);
					$client->setAccessToken($access_token);

					// Check to see if there was an error.
					if (array_key_exists('error', $access_token)) {
						throw new Exception(join(', ', $access_token));
					}

					update_option('ga4-token', json_encode($client->getAccessToken()));
				}
			}
		}

		return $client;
	}

	/**
	 * Get the authentication info for ga4 setup.
	 *
	 * @return array
	 * @throws \Google\Exception Throws exception.
	 */
	public function getGa4AuthInfo() {
		$client         = $this->getGa4Client();
		$token_response = $client->getAccessToken();
		$properties     = array();

		if (isset($token_response['access_token'])) {
			$args = [
				'headers' => [
					'Authorization' => 'Bearer ' . $token_response['access_token']
				],
			];

			$account = wp_remote_get('https://analytics.googleapis.com/analytics/v3/management/accounts', $args);
			$accounts = json_decode(wp_remote_retrieve_body($account), true);
			$accounts = false === empty($accounts['items']) ? $accounts['items'] : [];

			foreach($accounts as $account) {
				if (false === empty($account['id'])) {
					$ua_url       = 'https://www.googleapis.com/analytics/v3/management/accounts/' . $account['id'] . '/webproperties/';
					$response_url = 'https://analyticsadmin.googleapis.com/v1alpha/properties/?filter=parent%3Aaccounts%2F' . $account['id'] . '&pageSize=1';
					$response     = wp_remote_get( $response_url, $args );
					$response_ua  = wp_remote_get( $ua_url, $args );

					if ( false === is_array( $response ) || true === is_wp_error( $response ) ) {
						continue;
					}

					$response_array    = json_decode( wp_remote_retrieve_body( $response ), true );
					$ua_response_array = json_decode( wp_remote_retrieve_body( $response_ua ), true );

					if ( false === empty( $ua_response_array ) && true === isset( $ua_response_array['items'] ) ) {
						$properties_array = $ua_response_array['items'];
					}

					if ( false === empty( $response_array ) && true === isset( $response_array['properties'] ) ) {
						$properties_array = $response_array['properties'];
					}

					if ( false === empty( $response_array ) && true === isset( $response_array['properties'] ) &&
						false === empty( $ua_response_array ) && true === isset( $ua_response_array['items'] )
					) {
						$properties_array = array_merge( $response_array['properties'], $ua_response_array['items'] );
					}

					$properties[ $account['name'] ] = false === empty( $properties_array ) ? $properties_array : '';
				}
			}
		}

		return [
			'properties' => $properties,
			'auth_url'   => $client->createAuthUrl()
		];
	}
}
