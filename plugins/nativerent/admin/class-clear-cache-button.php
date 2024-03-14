<?php

namespace NativeRent\Admin;

use WP_Error;

use function __;
use function add_action;
use function admin_url;
use function defined;
use function filemtime;
use function nativerent_clear_cache;
use function sanitize_text_field;
use function wp_die;
use function wp_enqueue_script;
use function wp_localize_script;
use function wp_send_json_error;
use function wp_unslash;
use function wp_verify_nonce;

use const NATIVERENT_PLUGIN_DIR;
use const NATIVERENT_PLUGIN_URL;

defined( 'ABSPATH' ) || exit;

/**
 * Clear Cache Button
 */
class Clear_Cache_Button {
	const CLEAR_CACHE_ACTION = 'ntrnt_clear_cache';
	const NONCE_ACTION = 'ntrnt-clear-cache-action';
	const SCRIPTS_NAME = 'ntrnt-clear-cache-assets';
	const DISMISS_CACHE_NOTICE = 'ntrnt_dismiss_cache_notice';

	/**
	 * Instance
	 *
	 * @var self|null
	 */
	public static $instance;

	/**
	 * Returns instance of self
	 *
	 * @return self
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Construct
	 */
	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );
		add_action( 'wp_ajax_' . self::CLEAR_CACHE_ACTION, array( $this, 'action_clear_cache' ) );
		add_action( 'wp_ajax_' . self::DISMISS_CACHE_NOTICE, array( $this, 'action_dismiss_cache_notice' ) );
	}

	/**
	 * Add the Button
	 *
	 * @return string
	 */
	public function add_button() {
		return '<span id="ntrnt-clear-cache"><a class="button button-primary" href="#">Сбросить кэш</a></span>';
	}

	/**
	 * JS & CSS
	 */
	public function add_assets() {
		$path_js         = 'admin/js/clear-cache.js';
		$file_js_path    = NATIVERENT_PLUGIN_DIR . $path_js;
		$file_js_version = filemtime( $file_js_path );
		$file_js_url     = NATIVERENT_PLUGIN_URL . $path_js;

		wp_enqueue_script( self::SCRIPTS_NAME, $file_js_url, array(), $file_js_version, true );
		wp_localize_script( self::SCRIPTS_NAME, 'NTRNTData', self::get_js_localization() );
	}

	/**
	 * JS params
	 *
	 * @return array<string, string>
	 */
	public function get_js_localization() {
		$inline_settings = array(
			'ajax_url'        => admin_url( 'admin-ajax.php' ),
			'action'          => self::CLEAR_CACHE_ACTION,
			'dismiss_action'  => self::DISMISS_CACHE_NOTICE,
			'_wpnonce'        => wp_create_nonce( self::NONCE_ACTION ),
			'success_message' => __( 'Кэш очищен', 'nwp-flush-server-cache' ),
			'error_message'   => __( 'Что-то пошло ни так, пожалуйста сбросьте кэш вручную', 'nwp-flush-server-cache' ),
			'wait_message'    => __( 'Очистка кэша уже запущена', 'nwp-flush-server-cache' ),
		);

		return $inline_settings;
	}

	/**
	 * Clear cache
	 */
	public function action_clear_cache() {
		if ( ! isset( $_GET['_wpnonce'] )
			 || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), self::NONCE_ACTION ) ) {
			$error = new WP_Error( '-1', 'Nonce is not verified' );
			wp_send_json_error( $error );
		}

		nativerent_clear_cache();
		wp_die( 1 );
	}

	/**
	 * Action Dismiss Cache notice
	 */
	public static function action_dismiss_cache_notice() {
		Cache_Actions::update_option();
		wp_die( 1 );
	}
}
