<?php
/**
 * GoogleAnalytics Helper.
 *
 * @package GoogleAnalytics
 */

/**
 * Helper class.
 */
class Ga_Helper {

	const ROLE_ID_PREFIX                = 'role-id-';
	const GA_DEFAULT_WEB_ID             = 'UA-0000000-0';
	const GA_STATISTICS_PAGE_URL        = 'admin.php?page=googleanalytics';
	const GA_SETTINGS_PAGE_URL          = 'admin.php?page=googleanalytics/settings';
	const DASHBOARD_PAGE_NAME           = 'admin.php?page=googleanalytics';
	const PHP_VERSION_REQUIRED          = '7.4';
	const GA_WP_MODERN_VERSION          = '4.1';
	const GA_TOOLTIP_TERMS_NOT_ACCEPTED = 'Please accept the terms to use this feature.';
	const GA_TOOLTIP_FEATURES_DISABLED  = 'Click the Enable button at the top to start using this feature.';
	const GA_DEBUG_MODE                 = false;

	/**
	 * Init plugin actions.
	 */
	public static function init() {

		// Displays errors related to required PHP version.
		if ( false === self::is_php_version_valid() ) {
			add_action( 'admin_notices', 'Ga_Admin::admin_notice_googleanalytics_php_version' );

			return false;
		}

		// Displays errors related to required WP version.
		if ( false === self::is_wp_version_valid() ) {
			add_action( 'admin_notices', 'Ga_Admin::admin_notice_googleanalytics_wp_version' );

			return false;
		}

		if ( ! is_admin() ) {
			Ga_Frontend::add_actions();
		}

		if ( is_admin() ) {
			Ga_Admin::add_filters();
			Ga_Admin::add_actions();
			Ga_Admin::init_oauth();

			$admin_controller = new Ga_Admin_Controller();
			$admin_controller->handle_actions();
		}
	}

	/**
	 * Checks if current page is a WordPress dashboard.
	 *
	 * @return integer
	 */
	public static function is_plugin_page() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		$page_split = explode( '/', $page );

		if ( false === empty( $page_split ) && true === isset( $page_split[0] ) ) {
			return GA_NAME === $page_split[0];
		}

		return false;
	}

	/**
	 * Checks if current page is a WordPress dashboard.
	 *
	 * @return number
	 */
	public static function is_dashboard_page() {
		$site = get_current_screen();

		return preg_match( '/' . self::DASHBOARD_PAGE_NAME . '/', $site->base );
	}

	/**
	 * Check whether the plugin is configured.
	 *
	 * @param string $web_id Web ID string.
	 *
	 * @return boolean
	 */
	public static function is_configured( $web_id ) {
		return self::GA_DEFAULT_WEB_ID !== $web_id && false === empty( $web_id );
	}

	/**
	 * Prepare an array of current site's user roles
	 *
	 * @return array
	 */
	public static function get_user_roles() {
		global $wp_roles;
		if ( false === isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles(); // phpcs:ignore
		}

		return $wp_roles->get_names();
	}

	/**
	 * Prepare a role ID.
	 *
	 * The role ID is derived from the role's name and will be used
	 * in its setting name in the additional settings.
	 *
	 * @param string $role_name Role name.
	 *
	 * @return string
	 */
	public static function prepare_role_id( $role_name ) {
		return self::ROLE_ID_PREFIX . strtolower( preg_replace( '/[\W]/', '-', before_last_bar( $role_name ) ) );
	}

	/**
	 * Prepares role id.
	 *
	 * @param string $v Value string.
	 * @param string $k Key string.
	 */
	public static function prepare_role( &$v, $k ) {
		$v = self::prepare_role_id( $v );
	}

	/**
	 * Checks whether user role is excluded from adding UA code.
	 *
	 * @return boolean
	 */
	public static function can_add_ga_code() {
		$current_user  = wp_get_current_user();
		$user_roles    = ! empty( $current_user->roles ) ? $current_user->roles : array();
		$exclude_roles = json_decode( get_option( Ga_Admin::GA_EXCLUDE_ROLES_OPTION_NAME ), true );

		array_walk( $user_roles, 'Ga_Helper::prepare_role' );

		$return = true;
		foreach ( $user_roles as $role ) {
			if ( ! empty( $exclude_roles[ $role ] ) ) {
				$return = false;
				break;
			}
		}

		return $return;
	}

	/**
	 * Adds ga dashboard widget HTML code for a WordPress Dashboard widget hook.
	 */
	public static function add_ga_dashboard_widget() {
		$widget = self::get_ga_dashboard_widget(
			null,
			false,
			false,
			true
		);

		echo wp_kses(
			$widget,
			array(
				'a'      => array(
					'href' => array(),
				),
				'button' => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
				'div'    => array(
					'class' => array(),
					'id'    => array(),
					'style' => array(),
				),
				'select' => array(
					'id'           => array(),
					'autocomplete' => array(),
				),
				'script' => array(
					'type' => array(),
				),
				'option' => array(
					'value'    => array(),
					'selected' => array(),
				),
			)
		);
	}

	/**
	 * Generates dashboard widget HTML code.
	 *
	 * @param string  $date_range      Google Analytics specific date range string.
	 * @param boolean $text_mode       Text mode.
	 * @param boolean $ajax            Ajax.
	 * @param boolean $trigger_request Trigger request.
	 *
	 * @return null | string HTML dashboard widget code.
	 */
	public static function get_ga_dashboard_widget(
		$date_range = null,
		$text_mode = false,
		$ajax = false,
		$trigger_request = false
	) {
		if ( empty( $date_range ) ) {
			$date_range = '30daysAgo';
		}

		if ( false === $trigger_request ) {
			// Get chart and boxes data.
			$data = self::get_dashboard_widget_data( $date_range );

			if ( $text_mode ) {
				return self::get_chart_page(
					'ga-dashboard-widget' . ( $ajax ? '_ajax' : '' ),
					array(
						'chart' => $data['chart'],
						'boxes' => $data['boxes'],
					)
				);
			} else {
				return self::get_chart_page(
					'ga-dashboard-widget' . ( $ajax ? '_ajax' : '' ),
					array(
						'chart'            => $data['chart'],
						'boxes'            => $data['boxes'],
						'more_details_url' => admin_url( self::GA_STATISTICS_PAGE_URL ),
						'ga_nonce'         => wp_create_nonce( 'ga_ajax_data_change' ),
						'ga_nonce_name'    => Ga_Admin_Controller::GA_NONCE_FIELD_NAME,
					)
				);
			}
		} else {
			return self::get_chart_page(
				'ga-dashboard-widget' . ( $ajax ? '_ajax' : '' ),
				array(
					'chart'               => array(),
					'boxes'               => Ga_Stats::get_empty_boxes_structure(),
					'more_details_url'    => admin_url( self::GA_STATISTICS_PAGE_URL ),
					'show_trigger_button' => true,
					'ga_nonce'            => wp_create_nonce( 'ga_ajax_data_change' ),
					'ga_nonce_name'       => Ga_Admin_Controller::GA_NONCE_FIELD_NAME,
				)
			);
		}

		return null;
	}

	/**
	 * Generates JSON data string for AJAX calls.
	 *
	 * @param string  $date_range Date range.
	 * @param string  $metric     Metric string.
	 * @param boolean $text_mode  Text mode.
	 * @param boolean $ajax       Ajax.
	 *
	 * @return string|false Returns JSON data string
	 */
	public static function get_ga_dashboard_widget_data_json(
		$date_range = null, $metric = null, $text_mode = false, $ajax = false
	) {
		if ( empty( $date_range ) ) {
			$date_range = '30daysAgo';
		}

		if ( empty( $metric ) ) {
			$metric = 'pageviews';
		}

		$data = self::get_dashboard_widget_data( $date_range, $metric );

		return wp_json_encode( $data );
	}

	/**
	 * Gets dashboard widget data.
	 *
	 * @param string $date_range Date range string.
	 * @param string $metric     Metric.
	 *
	 * @return array Return chart and boxes data
	 */
	private static function get_dashboard_widget_data( $date_range, $metric = null ) {
		$selected = self::get_selected_account_data( true );
		if ( self::is_authorized() && self::is_account_selected() ) {
			$query_params = Ga_Stats::get_query( 'main_chart', $selected['view_id'], $date_range, $metric, true );
			$stats_data   = Ga_Admin::api_client()->call(
				'ga_api_data',
				array(
					$query_params,
				)
			);

			$boxes_query = Ga_Stats::get_query( 'dashboard_boxes', $selected['view_id'], $date_range, null, true );
			$boxes_data  = Ga_Admin::api_client()->call(
				'ga_api_data',
				array(
					$boxes_query,
				)
			);
		}
		$chart = ! empty( $stats_data ) ? Ga_Stats::get_dashboard_chart( $stats_data->get_data() ) : array();
		$boxes = ! empty( $boxes_data ) ? Ga_Stats::get_dashboard_boxes_data( $boxes_data->get_data() ) : array();

		return array(
			'chart' => $chart,
			'boxes' => $boxes,
		);
	}

	/**
	 * Is account selected?
	 *
	 * @return bool
	 */
	public static function is_account_selected() {
		return false === empty( self::get_selected_account_data() );
	}

	/**
	 * Returns HTML code of the chart page or a notice.
	 *
	 * @param string $view   View string.
	 * @param array  $params Params array.
	 *
	 * @return string Returns HTML code
	 */
	public static function get_chart_page( $view, $params ) {
		$message = sprintf(
		/* translators: %s is the settings page URL. */
			__( 'Statistics can only be seen after you authenticate with your Google account on the <a href="%s">Settings page</a>.' ),
			admin_url( self::GA_SETTINGS_PAGE_URL )
		);
		$ga4_property = get_option('googleanalytics-ga4-property');

		if ( (true === self::is_authorized() && false === self::is_code_manually_enabled() && false === self::is_all_feature_disabled()) || false === empty($ga4_property) ) {
			if ( true === self::is_account_selected() || true === isset($ga4_property)) {
				if ( false === empty( $params ) ) {
					return Ga_View_Core::load( $view, $params, true );
				} else {
					return self::ga_oauth_notice( sprintf( 'Please configure your <a href="%s">Google Analytics settings</a>.', admin_url( self::GA_SETTINGS_PAGE_URL ) ) );
				}
			} else {
				return self::ga_oauth_notice( $message );
			}
		} else {
			return self::ga_oauth_notice( $message );
		}
	}

	/**
	 * Checks whether users is authorized with Google.
	 *
	 * @return boolean
	 */
	public static function is_authorized() {
		return Ga_Admin::api_client()->get_instance()->is_authorized();
	}

	/**
	 * Wrapper for WordPress method get_option
	 *
	 * @param string     $name    Option name.
	 * @param mixed|null $default Default value if fetched option is null.
	 *
	 * @return mixed|null
	 */
	public static function get_option( $name, $default = null ) {
		$opt = get_option( $name, $default );

		return false === empty( $opt ) ? $opt : $default;
	}

	/**
	 * Wrapper for WordPress method update_option
	 *
	 * @param string $name  Option name.
	 * @param mixed  $value Option value.
	 *
	 * @return null|boolean
	 */
	public static function update_option( $name, $value ) {
		$opt = update_option( $name, $value );

		return ! empty( $opt ) ? $opt : null;
	}

	/**
	 * Loads ga notice HTML code with given message included.
	 *
	 * @param string $message Message.
	 *
	 * @return string
	 */
	public static function ga_oauth_notice( $message ) {
		return Ga_View_Core::load(
			'ga-oauth-notice',
			array(
				'msg' => $message,
			),
			true
		);
	}

	/**
	 * Displays notice following the WP style.
	 *
	 * @param string $message        Message string.
	 * @param string $type           Type string.
	 * @param bool   $is_dismissable Whether the notice is dismissable.
	 * @param string $action         Action type.
	 *
	 * @return string
	 */
	public static function ga_wp_notice( $message, $type = '', $is_dismissable = false, $action = array() ) {
		return Ga_View_Core::load(
			'ga-wp-notice',
			array(
				'notice_type'    => empty( $type ) ? Ga_Admin::NOTICE_WARNING : $type,
				'msg'            => $message,
				'is_dismissable' => $is_dismissable,
				'action'         => $action,
			),
			true
		);
	}

	/**
	 * Gets data according to selected GA account.
	 *
	 * @param boolean $assoc Whether the return should be an associative array.
	 *
	 * @return mixed
	 */
	public static function get_selected_account_data( $assoc = false ) {
		$data = json_decode( self::get_option( Ga_Admin::GA_SELECTED_ACCOUNT ) );
		$data = ( false === empty( $data ) && 3 === count( $data ) ) ? $data : false;

		if ( $data ) {
			if ( $assoc ) {
				return array(
					'account_id'      => $data[0],
					'web_property_id' => $data[1],
					'view_id'         => $data[2],
				);
			} else {
				return $data;
			}
		}

		return false;
	}

	/**
	 * Checks whether option for manually UA-code.
	 *
	 * @return boolean
	 */
	public static function is_code_manually_enabled() {
		return boolval( self::get_option( Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, false ) );
	}

	/**
	 * Adds percent sign to the given text.
	 *
	 * @param string $text Text string to format as a percentage.
	 *
	 * @return string
	 */
	public static function format_percent( $text ) {
		$text = self::add_plus( $text );

		return $text . '%';
	}

	/**
	 * Adds plus sign before number.
	 *
	 * @param string $number Number string.
	 *
	 * @return string
	 */
	public static function add_plus( $number ) {
		if ( $number > 0 ) {
			return '+' . $number;
		}

		return $number;
	}

	/**
	 * Check whether current user has administrator privileges.
	 *
	 * @return bool
	 */
	public static function is_administrator() {
		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is this WordPress version valid?
	 *
	 * @return bool|int
	 */
	public static function is_wp_version_valid() {
		$wp_version = get_bloginfo( 'version' );

		return version_compare( $wp_version, Ga_Admin::MIN_WP_VERSION, 'ge' );
	}

	/**
	 * Check if terms are accepted.
	 *
	 * @return bool
	 */
	public static function are_terms_accepted() {
		return boolval( self::get_option( Ga_Admin::GA_SHARETHIS_TERMS_OPTION_NAME ) );
	}

	/**
	 * Check if sharethis scripts enabled.
	 *
	 * @return bool
	 */
	public static function is_sharethis_included() {
		return boolval( GA_SHARETHIS_SCRIPTS_INCLUDED );
	}

	/**
	 * Is this PHP version valid?
	 *
	 * @return mixed
	 */
	public static function is_php_version_valid() {
		$p        = '#(\.0+)+($|-)#';
		$ver1     = preg_replace( $p, '', phpversion() );
		$ver2     = preg_replace( $p, '', self::PHP_VERSION_REQUIRED );
		$operator = 'ge';

		return version_compare( $ver1, $ver2, $operator );
	}

	/**
	 * Get current URL.
	 *
	 * @return mixed
	 */
	public static function get_current_url() {
		return filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
	}

	/**
	 * Create URL.
	 *
	 * @param string $url  URL.
	 * @param array  $data Data array.
	 *
	 * @return mixed|string
	 */
	public static function create_url( $url, $data = array() ) {
		return false === empty( $data ) ?
			( strstr( $url, '?' ) ?
				( $url . '&' ) :
				( $url . '?' ) ) . http_build_query( $data ) :
			$url;
	}

	/**
	 * Create base64 url message
	 *
	 * @param string $msg    Message.
	 * @param string $status Status.
	 *
	 * @return string
	 */
	public static function create_url_msg( $msg, $status ) {
		$msg = array(
			'status'  => $status,
			'message' => $msg,
		);

		return base64_encode( wp_json_encode( $msg ) ); // phpcs:ignore
	}

	/**
	 * Are all features disabled?
	 *
	 * @return bool
	 */
	public static function is_all_feature_disabled() {
		return boolval( self::get_option( Ga_Admin::GA_DISABLE_ALL_FEATURES, false ) );
	}

	/**
	 * Are features enabled?
	 *
	 * @return bool
	 */
	public static function are_features_enabled() {
		return true === self::are_terms_accepted() && false === self::is_all_feature_disabled();
	}

	/**
	 * Are ShareThis properties verified?
	 *
	 * @return bool
	 */
	public static function are_sharethis_properties_verified() {
		return (
			false !== get_option( Ga_Admin::GA_SHARETHIS_VERIFICATION_RESULT )
			&& true === self::are_sharethis_properties_set()
		);
	}

	/**
	 * Are ShareThis properties ready to verify?
	 *
	 * @return bool
	 */
	public static function are_sharethis_properties_ready_to_verify() {
		return (
			true === self::are_sharethis_properties_set()
			&& false === get_option( Ga_Admin::GA_SHARETHIS_VERIFICATION_RESULT )
		);
	}

	/**
	 * Are ShareThis properties set?
	 *
	 * @return bool
	 */
	public static function are_sharethis_properties_set() {
		return (
			false !== get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_ID ) &&
			false !== get_option( Ga_Admin::GA_SHARETHIS_PROPERTY_SECRET )
		);
	}

	/**
	 * Should we create the ShareThis property?
	 *
	 * @return bool
	 */
	public static function should_create_sharethis_property() {
		return true === self::are_features_enabled() && false === self::are_sharethis_properties_set();
	}

	/**
	 * Should we verify the ShareThis installation?
	 *
	 * @return bool
	 */
	public static function should_verify_sharethis_installation() {
		return true === self::are_features_enabled() && true === self::are_sharethis_properties_ready_to_verify();
	}

	/**
	 * Get tooltip.
	 *
	 * @return string
	 */
	public static function get_tooltip() {
		if ( false === self::are_terms_accepted() ) {
			return self::GA_TOOLTIP_TERMS_NOT_ACCEPTED;
		} elseif ( false === self::are_features_enabled() ) {
			return self::GA_TOOLTIP_FEATURES_DISABLED;
		} else {
			return '';
		}
	}

	/**
	 * Is this version of WordPress considered old (< 4.1)?
	 *
	 * @return bool True if old, False if not.
	 */
	public static function is_wp_old() {
		return version_compare( get_bloginfo( 'version' ), self::GA_WP_MODERN_VERSION, 'lt' );
	}

	/**
	 * Should we load GA JavaScript on this property?
	 *
	 * @param string $web_property_id Web property ID.
	 *
	 * @return bool
	 */
	public static function should_load_ga_javascript( $web_property_id ) {
		return true === self::is_configured( $web_property_id )
			   && (
				   true === self::can_add_ga_code()
				   || true === self::is_all_feature_disabled()
			   );
	}

	/**
	 * Get account ID.
	 *
	 * @return string
	 */
	public static function get_account_id() {
		$account_id = json_decode( self::get_option( Ga_Admin::GA_SELECTED_ACCOUNT ) );

		return ! empty( $account_id[0] ) ? $account_id[0] : '';
	}

	/**
	 * Is curl disabled?
	 *
	 * @return bool True if disabled, false if enabled.
	 */
	public static function is_curl_disabled() {
		return ! function_exists( 'curl_version' );
	}

	/**
	 * Get URL with correct protocol.
	 *
	 * @return string URL with correct protocol.
	 */
	public static function get_plugin_url_with_correct_protocol() {
		return GA_PLUGIN_URL;
	}

	/**
	 * Get code to manually label classes.
	 *
	 * @return string
	 */
	public static function get_code_manually_label_classes() {
		$classes = '';
		if ( ! self::are_features_enabled() ) {
			$classes = 'label-grey ga-tooltip';
		} elseif ( self::is_account_selected() ) {
			$classes = 'label-grey';
		}
		return $classes;
	}

	/**
	 * Get Previous Period for Dates (date start and date end).
	 *
	 * @param string $date_start Date string.
	 * @param string $date_end   Date string.
	 *
	 * @return array Array of start and end dates in Y-m-d format.
	 * @since 2.5.2
	 */
	public static function get_previous_period_for_dates( $date_start = '', $date_end = '' ) {
		try {
			// Get distance between dates in days.
			$start = new DateTime( $date_start );
			$end   = new DateTime( $date_end );
		} catch ( \Exception $e ) {
			return array(
				'start' => gmdate( 'Y-m-d', strtotime( '-1 week' ) ),
				'end'   => gmdate( 'Y-m-d' ),
			);
		}

		// Clone $start date into end_previous so we don't modify $start.
		$end_previous = clone $start;

		// Set the period to the difference between the start/end dates in days.
		$period = $end->diff( $start )->days;

		// Subtract 1 day from $end_previous so it's one day before $start.
		$end_previous->modify( '-1 day' );

		// Clone $end_previous so we can subtract $period from it in days.
		$start_previous = clone $end_previous;
		$start_previous->modify( sprintf( '-%d day', $period ) );

		return array(
			'start' => $start_previous->format( 'Y-m-d' ),
			'end'   => $end_previous->format( 'Y-m-d' ),
		);
	}

	/**
	 * Get period between dates in days.
	 *
	 * @param string $date_start Start date string.
	 * @param string $date_end   End date string.
	 *
	 * @return int
	 * @since 2.5.2
	 */
	public static function get_period_in_days( $date_start = '', $date_end = '' ) {
		$date_start = empty( $date_start ) ? gmdate( 'Y-m-d', strtotime( '-1 week' ) ) : $date_start;
		$date_end   = empty( $date_end ) ? gmdate( 'Y-m-d' ) : $date_end;

		try {
			// Get distance between dates in days.
			$start = new DateTime( $date_start );
			$end   = new DateTime( $date_end );
		} catch ( \Exception $e ) {
			return 0;
		}

		// Set the period to the difference between the start/end dates in days.
		return intval( $start->diff( $end )->format( '%r%a' ) );
	}

	/**
	 * Get period in Days as words.
	 *
	 * @param string $date_start Start date string.
	 * @param string $date_end   End date string.
	 *
	 * @return string Words to indicate days.
	 * @since 2.5.2
	 */
	public static function get_period_in_days_words( $date_start = '', $date_end = '' ) {
		$days = self::get_period_in_days( $date_start, $date_end );

		$date_end = empty( $date_end ) ? strtotime( 'now' ) : strtotime( $date_end );

		// If today is the same as the end date.
		if ( gmdate( 'Y-m-d', $date_end ) === gmdate( 'Y-m-d' ) ) {
			if ( 0 === $days ) {
				return __( 'Today', 'googleanalytics' );
			}

			if ( 7 === $days ) {
				return __( 'This Week', 'googleanalytics' );
			}

			return sprintf(
			/* translators: %d stands for the Day or Days. */
				_n( 'Last %d Day', 'Last %d Days', $days, 'googleanalytics' ),
				$days
			);
		}

		return sprintf(
		/* translators: %d stands for the Day or Days. */
			_n( '%d Day', '%d Days', $days, 'googleanalytics' ),
			$days
		);
	}

	/**
	 * Get date range from GET request.
	 *
	 * @return array
	 * @since 2.5.2
	 */
	public static function get_date_range_from_request() {
		$date_range = filter_input_array(
			INPUT_GET,
			array(
				'date_from' => FILTER_SANITIZE_STRING,
				'date_to'   => FILTER_SANITIZE_STRING,
			)
		);

		// If date_from is after date_to, let's reset 'from' to a week before 'to'.
		if ( 0 > self::get_period_in_days( $date_range['date_from'], $date_range['date_to'] ) ) {
			try {
				$date = new DateTime( $date_range['date_to'] );
				$date->modify( '-1 week' );

				$date_from = $date->format( 'Y-m-d' );
			} catch ( \Exception $e ) {
				$date_from = gmdate( 'Y-m-d', strtotime( '-1 week' ) );
			}

			$date_range['date_from'] = $date_from;
		}

		return array(
			'from' => $date_range['date_from'],
			'to'   => $date_range['date_to'],
		);
	}
}
