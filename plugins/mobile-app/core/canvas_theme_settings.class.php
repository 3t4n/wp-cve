<?php

defined( 'CANVAS_URL' ) || die();


/*
 * Some of the code for theme switching is a derivative work of the code from the Apppresser plugin,
 * which is licensed GPLv2. This code is also licensed under the terms of the GNU Public License, verison 2.
 */

class CanvasThemeSettings extends Canvas {

	protected $theme_mods = array(); // Safe theme_mods list

	public function __construct() {
		// If theme switching is active and the canvas theme customizer opened
		$theme_different = Canvas::get_option( self::THEME_DIFFERENT );
		if ( ! empty( $theme_different ) && isset( $_GET[ self::$slug_theme ] ) ) {
			// Redirect to current theme if another theme selected
			if ( ! isset( $_GET['theme'] ) || Canvas::get_option( self::THEME_OPTION ) != $_GET['theme'] ) {
				add_action( 'admin_init', array( $this, 'redirect_to_current_canvas_theme' ) );
			}

			// Load theme mods
			$this->theme_mods = array_keys( (array) get_option( 'theme_mods_' . Canvas::get_option( self::THEME_OPTION ) ) );
			if ( empty( $this->theme_mods ) ) {
				$this->theme_mods = array();
			}

			// Save safe theme_mods to list
			add_action( 'customize_register', array( $this, 'do_customize_register' ), self::PRIORITY );

			// Change 'save' button text, add a notice
			add_filter( 'gettext', array( $this, 'do_gettext' ), self::PRIORITY );
			// Change 'back' button url
			add_filter( 'clean_url', array( $this, 'do_clean_url' ), self::PRIORITY );
		}
	}

	/**
	 * Redirect to current Canvas Application theme
	 */
	public function redirect_to_current_canvas_theme() {
		wp_redirect( add_query_arg( 'theme', Canvas::get_option( self::THEME_OPTION ) ) );
	}

	/**
	 * Remove controls that are not specific to the Canvas theme
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 */
	public function do_customize_register( $wp_customize ) {

		$settings = $wp_customize->settings();
		if ( ! is_array( $settings ) || empty( $settings ) ) {
			return;
		}

		foreach ( $wp_customize->settings() as $id => $control ) {
			if ( 'theme_mod' != $control->type ) {
				$wp_customize->remove_control( $id );
			}
		}
	}

	/**
	 * Show warning
	 *
	 * @param string $translated_text
	 * @return string
	 */
	public function do_gettext( $translated_text ) {
		switch ( $translated_text ) {
			case 'Save &amp; Publish':
				return 'Save Canvas App Settings';
			case 'You are previewing %s':
				return '<p>You are previewing the theme for your mobile app:</p><p>%s</p>';
			case 'You are customizing %s':
				return '<p>You are customizing the theme for your mobile app:</p><p>%s</p>';
		}
		return $translated_text;
	}

	/**
	 * Change url for 'Back' button to plugin's page
	 *
	 * @param string $url
	 * @return string
	 */
	public function do_clean_url( $url ) {
		if ( empty( $url ) || ( admin_url( 'themes.php' ) == $url ) ) {
			return esc_url( self::main_settings_url() );
		}
		return $url;
	}
}
