<?php
/**
 * WPFactory Helper - Main Class
 *
 * @version 1.5.8
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WPCodeFactory_Helper' ) ) :

final class Alg_WPCodeFactory_Helper {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WPCODEFACTORY_HELPER_VERSION;

	/**
	 * update_server.
	 *
	 * @since 1.5.4
	 */
	public $update_server;

	/**
	 * update_server_text.
	 *
	 * @since 1.5.4
	 */
	public $update_server_text;

	/**
	 * site_url.
	 *
	 * @since 1.5.4
	 */
	public $site_url;

	/**
	 * plugins_updater.
	 *
	 * @since 1.5.4
	 */
	public $plugins_updater;

	/**
	 * @var   Alg_WPCodeFactory_Helper The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WPCodeFactory_Helper Instance
	 *
	 * Ensures only one instance of Alg_WPCodeFactory_Helper is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WPCodeFactory_Helper - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WPCodeFactory_Helper Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @access  public
	 *
	 * @todo    (dev) do not overwrite old check value on "server error"
	 * @todo    (dev) add "recheck licence now" (e.g. on "server error")
	 * @todo    (dev) `update_server_text` as constant
	 * @todo    (dev) wp-update-server - json_encode unicode issue
	 * @todo    (dev) check http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
	 */
	function __construct() {

		// Core properties
		$this->update_server      = ALG_WPCODEFACTORY_HELPER_UPDATE_SERVER;
		$this->update_server_text = 'WPFactory.com';
		$this->site_url           = str_replace( array( 'http://', 'https://' ), '', site_url() );

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Version update
		if ( is_admin() && $this->version !== get_option( 'alg_wpcodefactory_helper_version', '' ) ) {
			update_option( 'alg_wpcodefactory_helper_version', $this->version );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			add_filter( 'plugin_action_links_' . plugin_basename( ALG_WPCODEFACTORY_HELPER_FILE ), array( $this, 'action_links' ) );
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.4.0
	 * @since   1.3.1
	 */
	function localize() {
		load_plugin_textdomain( 'wpcodefactory-helper', false, dirname( plugin_basename( ALG_WPCODEFACTORY_HELPER_FILE ) ) . '/langs/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.5.8
	 * @since   1.0.0
	 */
	function includes() {
		require_once( 'alg-wpcodefactory-helper-site-key-functions.php' );
		$this->plugins_updater = require_once( 'class-alg-wpcodefactory-helper-plugins-updater.php' );
		require_once( 'class-alg-wpcodefactory-helper-site-key-manager.php' );
		require_once( 'class-alg-wpcodefactory-helper-crons.php' );
		// API access method option.
		require_once( 'class-alg-wpcodefactory-helper-api-access-method-option.php' );
		$class = new Alg_WPCodeFactory_Helper_API_Access_Method_Option();
		$class->init();
	}

	/**
	 * get_response_from_url.
	 *
	 * @version 1.5.8
	 * @since   1.5.1
	 *
	 * @param $url
	 *
	 * @return bool|mixed|string
	 */
	function get_response_from_url( $url ) {
		$url                     = html_entity_decode( $url );
		$first_api_access_method = get_option( 'alg_wpcodefactory_helper_api_access_method', 'file_get_contents' );
		$api_access_methods      = array( 'file_get_contents', 'curl' );
		if ( 'curl' === $first_api_access_method ) {
			$api_access_methods = array( 'curl', 'file_get_contents' );
		}
		$response = false;
		foreach ( $api_access_methods as $method ) {
			if ( false === $response ) {
				$response = call_user_func( array( $this, "get_response_from_url_using_{$method}" ), $url );
			} else {
				break;
			}
		}

		return $response;
	}

	/**
	 * get_response_from_url_using_curl.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $url
	 *
	 * @return bool|string
	 */
	function get_response_from_url_using_curl( $url ) {
		$response = false;
		if ( extension_loaded( 'curl' ) ) {
			$c = curl_init();
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $c, CURLOPT_URL, $url );
			$response = curl_exec( $c );
			curl_close( $c );
		}

		return $response;
	}

	/**
	 * get_response_from_url_using_file_get_contents.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $url
	 *
	 * @return false|string
	 */
	function get_response_from_url_using_file_get_contents( $url ) {
		$response = false;
		if ( filter_var( ini_get( 'allow_url_fopen' ), FILTER_VALIDATE_BOOLEAN ) ) {
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $agent = $_SERVER['HTTP_USER_AGENT'] ) ) {
				$options  = array(
					'http' => array(
						'method' => "GET",
						'header' => "Accept-language: en\r\n" .
						            'User-Agent: ' . $agent . "\r\n"
					)
				);
				$context  = stream_context_create( $options );
				$response = file_get_contents( $url, false, $context );
			} else {
				$response = file_get_contents( $url );
			}
		}

		return $response;
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=wpcodefactory-helper' ) . '">' . __( 'Settings', 'wpcodefactory-helper' ) . '</a>';
		return $links;
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WPCODEFACTORY_HELPER_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WPCODEFACTORY_HELPER_FILE ) );
	}

	/**
	 * Get the plugin file.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function plugin_file() {
		return ALG_WPCODEFACTORY_HELPER_FILE;
	}

}

endif;
