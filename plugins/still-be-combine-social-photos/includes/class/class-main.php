<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Main {


	const PREFIX = SB_CSP_PREFIX;

	private static $instance = null;


	public static function init() {

		if( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	// Constructer
	private function __construct() {

		// Cron Jobs
		Cron::init();

		// Blocks for Gutenberg
		Blocks::init();

		// Add REST API Endpoints
		add_action( 'rest_api_init', [ Rest_API::class, 'register_api' ] );

		// @since 0.2.1   Sets translated strings for Javascript
		// Add Script Translations
		add_action( 'admin_enqueue_scripts', [ $this, 'add_script_translations_admin' ], 99999 );

		// Add settings link to plugin actions
		add_filter( 'plugin_action_links', [ $this, 'add_plugin_action_link' ], 10, 2 );

	}


	public function add_plugin_action_link( $plugin_actions, $plugin_file ) {

		if( basename( STILLBE_CSP_BASE_DIR ). '/still-be-combine-social-photos.php' !== $plugin_file ) {
			return $plugin_actions;
		}

		return array_merge(
			array(
				'sb_csp_link_accounts' => sprintf(
					__( '<a href="%s">Link your Instagram accounts</a>', 'still-be-combine-social-photos' ),
					esc_url( admin_url( 'options-general.php?page='. Setting::PREFIX. 'setting-page&tab=tab_sb-csp-ss-accounts' ) )
				),
			),
			$plugin_actions
		);

	}


	public function add_script_translations_admin() {

		$admin_bandle_to_scripts = apply_filters( 'stillbe_csp_admin_js_translate_handles', [] );

		foreach( $admin_bandle_to_scripts as $bandle_script ) {
			wp_set_script_translations( $bandle_script, 'still-be-combine-social-photos' );
		}

	}


	// Enqueue Common Scripts & styles
	public function admin_enqueue_scripts_common() {

		// CSS
		wp_enqueue_style(
			'stillbe-csp-admin-form-common',
			STILLBE_CSP_BASE_URL. '/asset/css/admin.css',
			array(),
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/css/admin.css' )
		);

		// Javascript
		wp_enqueue_script(
			'stillbe-csp-admin-common',
			STILLBE_CSP_BASE_URL.'/asset/js/admin.js',
			[ 'wp-i18n' ],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/js/admin.js' )
		);
		wp_localize_script(
			'stillbe-csp-admin-common',
			'$stillbe',
			array(
				'admin' => array(
					'ajaxUrl'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'translate' => array(),
					'nonce'     => wp_create_nonce( 'sb-csp-setting-page' ),
				),
				'rest' => array(
					'namespace' => Rest_API::NAMESPACE_REST_API,
					'nonce'     => wp_create_nonce( 'wp_rest' ),
				),
				'combineSocialPhotos' => array(
					'action' => array(
						'setAuthUser'  => Setting::ACTION_SET_AUTH_USER,
						'reauthUser'   => Setting::ACTION_REAUTH_USER,
						'refreshToken' => Setting::ACTION_REFRESH_TOKEN,
						'resetSetting' => Setting::ACTION_RESET_SETTING,
						'unlockDB'     => Setting::ACTION_UNLOCK_DB,
					),
				),
			)
		);

		// Add JS Translate
		Main::add_admin_js_translate_handles( 'stillbe-csp-admin-common' );

	}


	public static function add_admin_js_translate_handles( $add_handles ) {

		add_filter( 'stillbe_csp_admin_js_translate_handles', function( $handles ) use( $add_handles ) {
			return array_merge( $handles, (array) $add_handles );
		} );

	}


}



