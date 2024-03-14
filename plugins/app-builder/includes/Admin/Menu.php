<?php

namespace AppBuilder\Admin;

use AppBuilder\Api\Base;

/**
 * @package    AppBuilder
 * @author     Appcheap <ngocdt@rnlab.io>
 */
class Menu extends Base {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * API url
	 *
	 * @var string
	 */
	private string $api;

	public function __construct() {

		$this->plugin_name = constant( 'APP_BUILDER_REST_BASE' );
		$this->version     = constant( 'APP_BUILDER_JS_VERSION' );
		$this->api         = constant( 'APP_BUILDER_API' );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add plugin action link point to settings page
		add_filter( 'plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php', array(
			$this,
			'add_plugin_action_links'
		) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * Load style for App builder
		 */
		wp_enqueue_style(
			$this->plugin_name,
			APP_BUILDER_CDN_JS . $this->version . '/static/css/main.css',
			array(),
			$this->version
		);

		/**
		 * Load Awesome icons
		 */
		wp_enqueue_style(
			$this->plugin_name . "_font_awesome",
			'https://pro.fontawesome.com/releases/v5.15.4/css/all.css',
			array(),
			$this->version
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Preload scripts, styles, settings, and templates necessary to use
		 */
		wp_enqueue_media();
		wp_enqueue_editor();

		/**
		 * Load feather icons
		 */
		// wp_enqueue_script( $this->plugin_name . '_feather_fonts', 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '_feather_fonts', APP_BUILDER_CDN_JS . 'jslibs/feather.min.js', array(), $this->version, true );

		/**
		 * Load Javascript App builder
		 */
		wp_enqueue_script( $this->plugin_name, APP_BUILDER_CDN_JS . $this->version . '/static/js/main.js', array(
			'jquery',
			'media-upload',
			'wp-tinymce'
		), $this->version, true );

		/**
		 * Get license info
		 */
		$license = get_option( 'app_builder_license', '' );
		$app     = get_option( 'app_builder_app', '' );

		/**
		 * Get image sizes
		 */
		$sizes = wp_get_registered_image_subsizes();

		/**
		 * Get curren user login
		 */
		$user = wp_get_current_user();

		/**
		 * Addition data need for app builder
		 */
		$addition_data = array(
			'version'            => APP_BUILDER_VERSION,
			'cirilla_version'    => APP_BUILDER_CIRILLA_VERSION,
			'api_nonce'          => wp_create_nonce( 'wp_rest' ),
			'api_url'            => rest_url( '' ),
			'plugin_name'        => $this->plugin_name,
			'vendor'             => [],
			'app'                => $app,
			'license'            => $license,
			'sizes'              => $sizes,
			'roles'              => $user->roles,
			'template_active_id' => (int) get_option( 'app_builder_template_active_id', 0 ),
			'preview_url'        => site_url( '/wp-content/uploads/app-builder/' . get_option( 'app_builder_cirilla_version', APP_BUILDER_CIRILLA_VERSION ) ),
			'api'                => $this->api,
		);

		/**
		 * Filter data before inline to javascript
		 */
		$filter_data = apply_filters( 'app_builder_prepare_settings_data', $addition_data );

		/**
		 * Inline app builder data
		 */
		$data = 'window.app_builder = ' . json_encode( $filter_data );
		wp_add_inline_script( $this->plugin_name, $data, 'before' );


		/**
		 * Inline awesome fonts
		 */
		$fonts = file_get_contents( APP_BUILDER_ABSPATH . 'assets/fonts/awesome_icons.json' );
		wp_add_inline_script( $this->plugin_name, "window.app_builder_awesome_icons = $fonts", 'before' );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 */

		$hook_suffix = add_menu_page(
			__( 'App Builder', $this->plugin_name ),
			__( 'App Builder', $this->plugin_name ),
			APP_BUILDER_CAPABILITY,
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' ),
			APP_BUILDER_CDN_JS . '/icon.svg'
		);

		// Load enqueue styles and script
		add_action( "admin_print_styles-$hook_suffix", array( $this, 'enqueue_styles' ) );
		add_action( "admin_print_scripts-$hook_suffix", array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		?>
        <div id="app-builder" dir="ltr"></div><?php
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param $links
	 *
	 * @return array
	 * @since    1.0.0
	 */
	public function add_plugin_action_links( $links ): array {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
			),
			$links
		);
	}
}
