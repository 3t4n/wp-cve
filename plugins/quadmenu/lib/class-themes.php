<?php

namespace QuadLayers\QuadMenu;

use QuadLayers\QuadMenu\Compiler;
use QuadLayers\QuadMenu\Redux;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Themes Class ex QuadMenu_Themes
 */
class Themes {

	private static $instance;

	public function __construct() {

		$this->themes();

		add_filter( 'quadmenu_developer_options', array( $this, 'options' ) );

		add_action( 'wp_ajax_quadmenu_add_theme', array( $this, 'ajax_theme_create' ) );

		add_action( 'wp_ajax_quadmenu_delete_theme', array( $this, 'ajax_theme_delete' ) );

		add_action( 'redux/options/' . QUADMENU_DB_OPTIONS . '/import', array( $this, 'import' ), 10, 2 );

		add_action( 'redux/options/' . QUADMENU_DB_OPTIONS . '/settings/change', array( $this, 'title' ), 10, 2 );

		// add_action('redux/' . QUADMENU_DB_OPTIONS . '/localize/reset', array($this, 'message'));
		add_action( 'wp_ajax_' . QUADMENU_DB_OPTIONS . '_ajax_save', array( $this, 'themes_delete' ) );
	}

	public function themes() {

		global $quadmenu_themes;

		$defaults = array(
			'default_theme' => esc_html( 'Default Theme', 'quadmenu' ),
		);

		$custom = (array) get_option( QUADMENU_DB_THEMES, array() );

		$quadmenu_themes = apply_filters( 'quadmenu_default_themes', wp_parse_args( $custom, $defaults ) );
	}

	public function options( $options ) {

		global $quadmenu_themes;

		if ( $saved_themes = get_option( QUADMENU_DB_THEMES, array() ) ) {
			$options['quadmenu_themes'] = $saved_themes;
		}

		$options['themes'] = $this->less_themes( $quadmenu_themes );

		return $options;
	}

	static function less_themes( $quadmenu_themes ) {

		$themes = array();

		if ( is_array( $quadmenu_themes ) && count( $quadmenu_themes ) ) {

			foreach ( $quadmenu_themes as $key => $theme ) {

				$themes[] = '~"' . $key . '"';
			}

			return implode( ',', array_reverse( $themes ) );
		}

		return '~"' . $quadmenu_themes . '"';
	}

	public function import( $plugin_options = null, $imported_options = null ) {

		if ( ! empty( $imported_options['quadmenu_themes'] ) ) {
			update_option( QUADMENU_DB_THEMES, $imported_options['quadmenu_themes'] );
		}
	}

	public function ajax_theme_create() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		do_action( 'quadmenu_delete_theme' );

		$saved_themes = get_option( QUADMENU_DB_THEMES, array() );

		$next_id = count( $saved_themes ) + 1;

		$next_key = 'custom_theme_' . $next_id;

		$saved_themes[ $next_key ] = sprintf( esc_html__( 'Custom Theme %s', 'quadmenu' ), $next_id );

		if ( update_option( QUADMENU_DB_THEMES, $saved_themes ) ) {

			Compiler::do_compiler( true );

			Redux::add_notification( 'blue', sprintf( esc_html__( 'New theme created. Your options panel will be reloaded to include their options. %s.', 'quadmenu' ), esc_html__( 'Please wait', 'quadmenu' ) ) );

			Plugin::send_json_success( Plugin::taburl( 'quadmenu_theme_' . $next_key ) );
		} else {
			Plugin::send_json_error( esc_html__( 'Can\'t create theme.', 'quadmenu' ) );
		}

		wp_die();
	}

	public function ajax_theme_delete() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		do_action( 'quadmenu_delete_theme' );

		global $quadmenu_themes;

		if ( ! empty( $_REQUEST['current_theme'] ) ) {

			$key = sanitize_text_field( $_REQUEST['current_theme'] );

			$saved_themes = get_option( QUADMENU_DB_THEMES, array() );

			unset( $saved_themes[ $key ] );

			$prev_key = reset( array_keys( $quadmenu_themes ) );

			if ( update_option( QUADMENU_DB_THEMES, $saved_themes ) ) {

				Compiler::do_compiler( true );

				Redux::add_notification( 'blue', sprintf( esc_html__( 'Theme deleted. Your options panel will be reloaded to remove their options. %s.', 'quadmenu' ), esc_html__( 'Please wait', 'quadmenu' ) ) );

				Plugin::send_json_success( Plugin::taburl( 'quadmenu_theme_' . $prev_key ) );
			} else {
				Plugin::send_json_error( esc_html__( 'Can\'t delete theme.', 'quadmenu' ) );
			}
		}

		wp_die();
	}

	function themes_delete() {

		if ( ! empty( $_REQUEST['data'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'redux_ajax_nonce' . QUADMENU_DB_OPTIONS ) ) {

			$redux = \ReduxFrameworkInstances::get_instance( QUADMENU_DB_OPTIONS );

			$values = array();

			$_REQUEST['data'] = stripslashes( $_REQUEST['data'] );

			$values = $redux->redux_parse_str( $_REQUEST['data'] );
			// $values = Redux_Functions_Ex::parse_str( $_REQUEST['data'] );

			$values = $values[ QUADMENU_DB_OPTIONS ];

			// if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			$values = array_map( 'stripslashes_deep', $values );
			// }

			if ( ! empty( $values['defaults'] ) ) {

				delete_option( QUADMENU_DB_THEMES );
			}
		}
	}

	function title( $options = false, $changed = false ) {

		$update = false;

		if ( $saved_themes = get_option( QUADMENU_DB_THEMES, array() ) ) {

			foreach ( $saved_themes as $key => $name ) {

				if ( ! empty( $options[ $key . '_theme_title' ] ) && $options[ $key . '_theme_title' ] != $name ) {

					$update = true;

					$saved_themes[ $key ] = $options[ $key . '_theme_title' ];
				}
			}

			if ( $update && update_option( QUADMENU_DB_THEMES, $saved_themes ) ) {
				Redux::add_notification( 'blue', esc_html__( 'Theme name changed.', 'quadmenu' ) );
			}
		}
	}

	function message() {
		return esc_html__( 'Are you sure? Resetting will lose all custom values and themes.', 'quadmenu' );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
