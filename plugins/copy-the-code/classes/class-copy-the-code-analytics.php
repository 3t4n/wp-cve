<?php
/**
 * Analytics
 *
 * @package Copy the Code
 */

if ( ! class_exists( 'Copy_The_Code_Analytics' ) ) :

	/**
	 * Analytics
	 */
	class Copy_The_Code_Analytics {

		/**
		 * URL to the WooThemes Tracker API endpoint.
		 *
		 * @var string
		 */
		private static $api_url = 'https://surror.com/wp-json/surror/v1/analytics';

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'copy_the_code_tracker_send_event', array( __CLASS__, 'send_tracking_data' ) );
		}

		/**
		 * Send tracking data.
		 */
		public static function send_tracking_data() {
			if ( wp_doing_ajax() ) {
				return;
			}

			// Send a maximum of once per week by default.
			$last_send = self::get_last_send_time();
			if ( $last_send && $last_send > apply_filters( 'copy_the_code_tracker_last_send_interval', strtotime( '-1 week' ) ) ) {
				return;
			}

			// Update time first before sending to ensure it is set.
			update_option( 'copy_the_code_tracker_last_send', time() );

			$params = self::get_tracking_data();
			wp_safe_remote_post(
				self::$api_url,
				array(
					'method'  => 'POST',
					'timeout' => 45,
					'body'    => $params,
					'cookies' => array(),
				)
			);
		}

		/**
		 * Get the last time tracking data was sent.
		 *
		 * @return int|bool
		 */
		private static function get_last_send_time() {
			return apply_filters( 'copy_the_code_tracker_last_send_time', get_option( 'copy_the_code_tracker_last_send', false ) );
		}

		/**
		 * Get all the tracking data.
		 *
		 * @return array
		 */
		public static function get_tracking_data() {
			$data = array();

			// General site info.
			$data['url']             = home_url();
			$data['email']           = apply_filters( 'copy_the_code_tracker_admin_email', get_option( 'admin_email' ) );
			$data['opt_in']          = get_option( 'copy_the_code_opt_in_welcome', 'yes' );
			$data['active_themes']   = self::get_active_themes();
			$data['inactive_themes'] = self::get_inactive_themes();

			// WordPress Info.
			$data['wp'] = self::get_wordpress_info();

			// Server Info.
			$data['server'] = self::get_server_info();

			// Plugin info.
			$all_plugins              = self::get_all_plugins();
			$data['active_plugins']   = $all_plugins['active_plugins'];
			$data['inactive_plugins'] = $all_plugins['inactive_plugins'];

			// Count info.
			$data['users'] = self::get_user_counts();

			$products = array();
			$orders   = array();
			if ( class_exists( 'WC_Tracker' ) ) {
				$woo_data = WC_Tracker::get_tracking_data();
				$products = $woo_data['products'];
				$orders   = $woo_data['orders'];
			}
			$data['wc-products'] = $products;
			$data['wc-orders']   = $orders;

			return apply_filters( 'copy_the_code_tracker_data', $data );
		}

		/**
		 * Get user totals based on user role.
		 *
		 * @return array
		 */
		private static function get_user_counts() {
			$user_count          = array();
			$user_count_data     = count_users();
			$user_count['total'] = $user_count_data['total_users'];

			// Get user count based on user role.
			foreach ( $user_count_data['avail_roles'] as $role => $count ) {
				$user_count[ $role ] = $count;
			}

			return $user_count;
		}

		/**
		 * Get all plugins grouped into activated or not.
		 *
		 * @return array
		 */
		private static function get_all_plugins() {
			// Ensure get_plugins function is loaded.
			if ( ! function_exists( 'get_plugins' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}

			$plugins             = get_plugins();
			$active_plugins_keys = get_option( 'active_plugins', array() );
			$active_plugins      = array();

			foreach ( $plugins as $k => $v ) {
				// Take care of formatting the data how we want it.
				$formatted         = array();
				$formatted['name'] = strip_tags( $v['Name'] );
				if ( isset( $v['Version'] ) ) {
					$formatted['version'] = strip_tags( $v['Version'] );
				}
				if ( isset( $v['Author'] ) ) {
					$formatted['author'] = strip_tags( $v['Author'] );
				}
				if ( isset( $v['Network'] ) ) {
					$formatted['network'] = strip_tags( $v['Network'] );
				}
				if ( isset( $v['PluginURI'] ) ) {
					$formatted['plugin_uri'] = strip_tags( $v['PluginURI'] );
				}
				if ( in_array( $k, $active_plugins_keys ) ) { // phpcs:ignore
					// Remove active plugins from list so we can show active and inactive separately.
					unset( $plugins[ $k ] );
					$active_plugins[ $k ] = $formatted;
				} else {
					$plugins[ $k ] = $formatted;
				}
			}

			return array(
				'active_plugins'   => $active_plugins,
				'inactive_plugins' => $plugins,
			);
		}

		/**
		 * Get server related info.
		 *
		 * @return array
		 */
		private static function get_server_info() {
			$server_data = array();

			if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
				$server_data['software'] = $_SERVER['SERVER_SOFTWARE']; // @phpcs:ignore
			}

			if ( function_exists( 'phpversion' ) ) {
				$server_data['php_version'] = phpversion();
			}

			if ( function_exists( 'ini_get' ) ) {
				$server_data['php_post_max_size']  = size_format( self::let_to_num( ini_get( 'post_max_size' ) ) );
				$server_data['php_time_limt']      = ini_get( 'max_execution_time' );
				$server_data['php_max_input_vars'] = ini_get( 'max_input_vars' );
				$server_data['php_suhosin']        = extension_loaded( 'suhosin' ) ? 'Yes' : 'No';
			}

			$database_version             = self::get_server_database_version();
			$server_data['mysql_version'] = $database_version['number'];

			$server_data['php_max_upload_size']  = size_format( wp_max_upload_size() );
			$server_data['php_default_timezone'] = date_default_timezone_get();
			$server_data['php_soap']             = class_exists( 'SoapClient' ) ? 'Yes' : 'No';
			$server_data['php_fsockopen']        = function_exists( 'fsockopen' ) ? 'Yes' : 'No';
			$server_data['php_curl']             = function_exists( 'curl_init' ) ? 'Yes' : 'No';

			return $server_data;
		}

		/**
		 * Retrieves the MySQL server version. Based on $wpdb.
		 *
		 * @since 3.4.1
		 * @return array Vesion information.
		 */
		public static function get_server_database_version() {
			global $wpdb;

			if ( empty( $wpdb->is_mysql ) ) {
				return array(
					'string' => '',
					'number' => '',
				);
			}

			// phpcs:disable WordPress.DB.RestrictedFunctions, PHPCompatibility.Extensions.RemovedExtensions.mysql_DeprecatedRemoved
			if ( $wpdb->use_mysqli ) {
				$server_info = mysqli_get_server_info( $wpdb->dbh );
			} else {
				$server_info = mysql_get_server_info( $wpdb->dbh ); // phpcs:ignore
			}
			// phpcs:enable WordPress.DB.RestrictedFunctions, PHPCompatibility.Extensions.RemovedExtensions.mysql_DeprecatedRemoved

			return array(
				'string' => $server_info,
				'number' => preg_replace( '/([^\d.]+).*/', '', $server_info ),
			);
		}

		/**
		 * Get WordPress related data.
		 *
		 * @return array
		 */
		private static function get_wordpress_info() {
			$wp_data = array();

			$memory = self::let_to_num( WP_MEMORY_LIMIT );

			if ( function_exists( 'memory_get_usage' ) ) {
				$system_memory = self::let_to_num( @ini_get( 'memory_limit' ) ); // phpcs:ignore
				$memory        = max( $memory, $system_memory );
			}

			// WordPress 5.5+ environment type specification.
			// 'production' is the default in WP, thus using it as a default here, too.
			$environment_type = 'production';
			if ( function_exists( 'wp_get_environment_type' ) ) {
				$environment_type = wp_get_environment_type();
			}

			$wp_data['memory_limit'] = size_format( $memory );
			$wp_data['debug_mode']   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
			$wp_data['locale']       = get_locale();
			$wp_data['version']      = get_bloginfo( 'version' );
			$wp_data['multisite']    = is_multisite() ? 'Yes' : 'No';
			$wp_data['env_type']     = $environment_type;

			return $wp_data;
		}

		/**
		 * Notation to numbers.
		 *
		 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
		 *
		 * @param  string $size Size value.
		 * @return int
		 */
		public static function let_to_num( $size ) {
			$l   = substr( $size, -1 );
			$ret = (int) substr( $size, 0, -1 );
			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
					// No break.
				case 'T':
					$ret *= 1024;
					// No break.
				case 'G':
					$ret *= 1024;
					// No break.
				case 'M':
					$ret *= 1024;
					// No break.
				case 'K':
					$ret *= 1024;
					// No break.
			}
			return $ret;
		}

		/**
		 * Get the current theme info, theme name and version.
		 *
		 * @return array
		 */
		public static function get_active_themes() {
			$theme_data           = wp_get_theme();
			$theme_child_theme    = is_child_theme();
			$theme_wc_support     = current_theme_supports( 'copy_the_code' );
			$theme_is_block_theme = self::current_theme_is_fse();

			$theme_slug = sanitize_title( $theme_data->Name ); // phpcs:ignore

			$data = array();

			$data[ $theme_slug ] = array(
				'name'        => $theme_data->Name, // @phpcs:ignore
				'version'     => $theme_data->Version, // @phpcs:ignore
				'child_theme' => $theme_child_theme,
				'wc_support'  => $theme_wc_support,
				'block_theme' => $theme_is_block_theme,
			);

			return $data;
		}

		/**
		 * Get the current theme info, theme name and version.
		 *
		 * @return array
		 */
		public static function get_inactive_themes() {
			$themes = wp_get_themes();
			if ( empty( $themes ) ) {
				return array();
			}

			$inactive_themes = array();

			foreach ( $themes as $theme_slug => $theme_data ) {
				$inactive_themes[ $theme_slug ] = array(
					'name'        => $theme_data->Name, // @phpcs:ignore
					'version'     => $theme_data->Version, // @phpcs:ignore
					'child_theme' => '',
					'wc_support'  => '',
					'block_theme' => '',
				);
			}

			return $inactive_themes;
		}

		/**
		 * Get the current theme info, theme name and version.
		 */
		public static function current_theme_is_fse() {
			if ( function_exists( 'wp_is_block_theme' ) ) {
				return (bool) wp_is_block_theme();
			}
			if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
				return (bool) gutenberg_is_fse_theme();
			}

			return false;
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Copy_The_Code_Analytics::get_instance();

endif;
