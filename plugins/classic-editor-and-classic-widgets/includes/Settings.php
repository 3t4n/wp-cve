<?php

namespace GRIM_CEW;

use GRIM_CEW\Vendor\Controller;

class Settings extends Controller {
	protected static $settings = 'cew_settings';

	private static $allowed_settings = array(
		'default_editor',
		'widgets_editor',
		'allow_users',
		'edit_links',
		'enable_frontend',
		'hide_menu_item',
		'acf_support',
		'user_role_enable_gutenberg',
		'remember_editor',
		'whitelist',
		'cpt',
	);

	public static function render_settings_page() {
		if ( isset( $_POST['save_settings'] ) ) {
			if ( ! isset( $_POST['cew_settings_nonce'] ) || ! wp_verify_nonce( $_POST['cew_settings_nonce'], CEW_BASENAME . '-settings' ) ) {
				return;
			}

			self::save_settings( $_POST );
		}

		wp_enqueue_style( 'cew-settings', CEW_URL . 'assets/css/settings.css', array(), CEW_VERSION );
		wp_enqueue_script( 'cew-settings', CEW_URL . 'assets/js/scripts.js', array( 'jquery', 'jquery-ui' ), CEW_VERSION, true );

		wp_localize_script(
			'cew-settings',
			'cew',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		include_once CEW_PATH . '/templates/settings.php';
	}

	public static function filter_settings( $key ): bool {
		return in_array( $key, self::$allowed_settings, true );
	}

	public static function save_settings( $data ) {
		update_option(
			self::$settings,
			array_filter(
				$data,
				array( self::class, 'filter_settings' ),
				ARRAY_FILTER_USE_KEY
			)
		);
	}

	public static function get_settings() {
		return get_option( self::$settings );
	}

	public static function get_option( $option, $sub_item = false ) {
		$settings = self::get_settings();

		if ( $sub_item ) {
			return $settings[ $option ][ $sub_item ] ?? null;
		}

		return $settings[ $option ] ?? null;
	}

	public static function is_classic( $option = 'default_editor' ) {
		return 'gutenberg' !== self::get_option( $option );
	}
}
