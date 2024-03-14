<?php

#[AllowDynamicProperties]
class WooFunnels_License_Controller {

	/**
	 * @var WooFunnels_License_check[]
	 */
	public static $plugins = [];

	/**
	 * @var WP_Http
	 */
	public static $http;
	private static $server_point = 'https://license.funnelkit.com/';
	private static $software_end_point = '';
	private static $request_args = array(
		'timeout'   => 30,
		'sslverify' => false,
	);
	private static $plugin_update_check_data;
	private $license_data = array();
	private $request_body = false;

	public static function register_plugin( $hash, $object ) {
		if ( ! isset( self::$plugins[ $hash ] ) ) {
			self::$plugins[ $hash ] = $object;
		}
	}


	public static function get_all_plugins() {
		return self::$plugins;
	}

	/**
	 * Run a batch request to check license status of all the plugin
	 * Then trigger handle license check response of child class so that they can process their data.
	 */
	public static function license_check() {
		$parse_data            = [];
		$parse_data['plugins'] = [];
		$plugins_to_send       = [];
		foreach ( self::$plugins as $hash => $plugin ) {
			$data = $plugin->get_data();
			if ( empty( $data['plugin_slug'] ) ) {
				continue;
			}

			$parse_url = wp_parse_url( $data['domain'] );

			/**
			 * prevent license check when domain is IP, it will help reducing domain mismatches
			 */
			if ( is_array( $parse_url ) && ! empty( $parse_url['host'] ) && ! empty( ip2long( $parse_url['host'] ) ) ) {
				continue;
			}
			$parse_data['plugins'][ $hash ] = $plugins_to_send[ $hash ] = $data;
		}

		$request = 'status_request_all';

		$output = self::build_output( self::http()->post( self::get_software_endpoint( $request ), array_merge( [ 'body' => $parse_data ], self::$request_args ) ) );
		if ( $output && is_array( $output ) && count( $output ) > 0 ) {
			foreach ( $output as $hash => $output_plugin ) {
				if ( isset( $plugins_to_send[ $hash ] ) ) {

					self::$plugins[ $hash ]->handle_license_check_response( $output_plugin );
				}
			}
		}
		WooFunnels_Process::get_instance()->maybe_clear_plugin_update_transients();

	}

	private static function build_output( $response, $is_searilize = false ) {
		if ( ! is_wp_error( $response ) ) {
			$body = $response['body'];
			if ( '' !== $body ) {
				if ( false === $is_searilize ) {
					$body = json_decode( $body, true );
					if ( $body ) {
						return $body;
					}
				} else {
					$object = maybe_unserialize( $body );
					if ( is_object( $object ) && count( get_object_vars( $object ) ) > 0 ) {
						return $object;
					}

					return false;
				}
			}
		}

		return false;
	}

	public static function http() {
		if ( is_null( self::$http ) ) {
			self::$http = new WP_Http();
		}

		return self::$http;
	}

	public static function get_software_endpoint( $request = '' ) {
		return add_query_arg( array(
			'wc-api'  => 'am-software-api',
			'request' => $request,
		), self::$server_point );
	}

	public static function get_plugin_update_check( $hash ) {
		$get_data = self::license_update_check();

		if ( ! isset( $get_data[ $hash ] ) ) {
			return false;
		}

		return $get_data[ $hash ];

	}

	public static function license_update_check() {
		if ( ! is_null( self::$plugin_update_check_data ) ) {
			return self::$plugin_update_check_data;
		}
		$parse_data            = [];
		$parse_data['plugins'] = [];
		$plugins_to_send       = [];
		$prepare_dummy         = [];
		foreach ( self::$plugins as $hash => $plugin ) {
			$data = $plugin->get_data();
			if ( empty( $data['plugin_slug'] ) ) {
				continue;
			}
			$parse_data['plugins'][ $hash ] = $plugins_to_send[ $hash ] = $data;
			$prepare_dummy[ $hash ]         = [];
		}

		$request = 'pluginupdatecheckall';

		$output = self::build_output( self::http()->post( self::get_update_endpoint( $request ), array_merge( [ 'body' => $parse_data ], self::$request_args ) ) );

		if ( $output ) {
			self::$plugin_update_check_data = $output;
		} else {
			$output                         = $prepare_dummy;
			self::$plugin_update_check_data = $output;
		}

		return $output;
	}

	public static function get_update_endpoint( $request = '' ) {
		return add_query_arg( array(
			'wc-api'  => 'upgrade-api',
			'request' => $request,
		), self::$server_point );
	}

	public static function get_plugins() {
		if ( is_multisite() ) {
			$plugin_config = WooFunnel_Loader::get_the_latest();

			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			if ( is_array( $active_plugins ) && is_array( $plugin_config ) && count( $plugin_config ) > 0 && ( in_array( $plugin_config['basename'], apply_filters( 'active_plugins', $active_plugins ), true ) || array_key_exists( $plugin_config['basename'], apply_filters( 'active_plugins', $active_plugins ) ) ) ) {
				return get_blog_option( get_network()->site_id, 'woofunnels_plugins_info', [] );
			} else {
				return is_array( get_option( 'woofunnels_plugins_info', [] ) ) ? get_option( 'woofunnels_plugins_info', [] ) : [];
			}

		}

		return is_array( get_option( 'woofunnels_plugins_info', [] ) ) ? get_option( 'woofunnels_plugins_info', [] ) : [];
	}

	public static function update_plugins( $data ) {
		update_option( 'woofunnels_plugins_info', $data, 'yes' );
	}

	public static function get_basename_by_key( $hash ) {
		if ( ! isset( self::$plugins[ $hash ] ) ) {
			return __return_empty_string();
		}
		$data = self::$plugins[ $hash ]->get_data();

		return $data['plugin_slug'];
	}

	/**
	 * Callback method to run license check manually
	 * @return void
	 */
	public static function license_check_api_call_init() {
		try {
			$check_transient = get_transient( 'fk_license_check_api_call_init' );

			if ( ! empty( $check_transient ) ) {
				wp_send_json( array( 'status' => 'failure', 'message' => 'lock exists' ) );
			}

			self::license_check();
			set_transient( 'fk_license_check_api_call_init', 1, HOUR_IN_SECONDS );
			$plugins = self::get_all_plugins();
			wp_send_json( array( 'status' => 'success', 'message' => 'License check initiated', 'is_pro' => count( $plugins ) > 0 ? true : false ) );

		} catch ( Exception|Error $e ) {
			wp_send_json( array( 'status' => 'failure', 'message' => $e->getMessage() ) );
		}
	}


}
