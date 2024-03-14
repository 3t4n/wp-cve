<?php
/*
	Copyright (C) 2020 Papoo Software & Media GmbH

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License along
	with this program; if not, write to the Free Software Foundation, Inc.,
		51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

class Ccm19Integration {

	/** @var self $instance */
	private static $instance = null;

	/** @var string Convenient variable for site slug*/
	protected $settings_slug = 'ccm19-integration';

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if ( self::$instance === null ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * @return void
	 */
	public static function staticInit()
	{
		$instance = static::getInstance();
		$instance->init();
	}

	/**
	 * @return void
	 */
	public function init()
	{
		// Insert the script on wp_head with extreme priority
		// so that it always runs before any other script.
		add_action( 'wp_head', [ $this, 'on_wp_head' ], - 10 );
		// Enqueue dummy script for dependency management
		wp_register_script( 'ccm19', false, [], false, false );
		wp_enqueue_script( 'ccm19' );
		//Adds network supportet menu
		if ( is_multisite() ) {
			add_action( "network_admin_menu", [ $this, "network_menu" ] );
			add_action( 'network_admin_edit_' . $this -> settings_slug . '-update', [ $this, 'update' ] );
		}
		// Add settings form
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		// Load translations
		load_plugin_textdomain( 'ccm19-integration', false, basename( __DIR__ ) . '/languages' );
	}

	/**
	 * Hook: Initialize plugin settings
	 * @return void
	 */
	public function admin_init()
	{
		if ( is_admin() ) {

			// Add settings
			add_settings_section(
				'ccm19-integration',
				__( 'General settings', 'ccm19-integration' ),
				[ $this, 'options_page_print_info' ],
				'ccm19-integration'
			);

			register_setting( 'ccm19-integration', 'ccm19_code', [
				'type'        => 'string',
				'description' => 'CCM19 Code snippet',
				'default'     => ''
			] );

			add_settings_field(
				'ccm19_code', // ID
				__( 'CCM19 code snippet', 'ccm19-integration' ),
				[ $this, 'option_code_snippet_callback' ],
				'ccm19-integration',
				'ccm19-integration'
			);
		}
	}

	/**
	 * Extract the ccm19.js url from the code snippet
	 *
	 * @return string|null
	 */
	private function get_integration_url()
	{

		$code = get_site_option( 'ccm19_code' );
		if ( ! empty( $code ) ) {
			$match = [];
			preg_match( '/\bsrc=([\'"])((?>[^"\'?#]|(?!\1)["\'])*\/(ccm19|app)\.js\?(?>[^"\']|(?!\1).)*)\1/i', $code, $match );
			if ( $match and $match[2] ) {
				return html_entity_decode( $match[2], ENT_HTML401 | ENT_QUOTES, 'UTF-8' );
			}
		}

		return null;
	}

	/**
	 * Hook: Inserts the script code
	 * @return void
	 */
	public function on_wp_head() {
		$integration_url = $this->get_integration_url();
		
		if ( $integration_url ) {
			wp_print_script_tag( [ 'src' => $integration_url, 'referrerpolicy' => 'origin' ] );
		}
	}

	/**
	 * Hook: Register plugin settings menu
	 * @return void
	 */
	public function admin_menu()
	{
		add_options_page(
			__( 'CCM19 Integration Options', 'ccm19-integration' ),
			__( 'CCM19 Cookie Consent', 'ccm19-integration' ),
			'manage_options',
			'ccm19-integration',
			[ $this, 'options_page' ]
		);
	}
	/**
	 * Display options page
	 *
	 * @return void
	 */
	public function options_page()
	{
		if ( ! current_user_can( 'manage_options' ) || ( is_multisite() && ! current_user_can( 'manage_network_options' ) ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$integration_url = $this->get_integration_url();
		$admin_url       = ( $integration_url ) ? preg_replace( '%/(ccm19|app)\.js?.*$%i', '/', $integration_url ) : null;
		include( WP_PLUGIN_DIR . '/ccm19-integration/options-page.php' );
	}

	/**
	 * Callback: print settings section text
	 *
	 * @return void
	 */
	public function options_page_print_info()
	{
		_e( '<p>Enter your code snippet from CCM19 below to integrate the cookie consent management with your website.</p>', 'ccm19-integration' );
		_e( '<p>If you don\'t yet have a CCM19 account or instance yet, buy or lease one on <a target="_blank" href="https://ccm19.de">ccm19.de</a>.</p>', 'ccm19-integration' );
	}

	/**
	 * Callback: print input field for code snippet
	 *
	 * @return void
	 */
	public function option_code_snippet_callback()
	{
		printf(
			'<textarea id="ccm19-code" name="ccm19_code" cols="60" rows="4">%s</textarea>',
			esc_attr( get_site_option( 'ccm19_code' ) )
		);
	}

	/**
	 * Callback: add's menu for multisite support
	 *
	 * @return void
	 *
	 */
	public function network_menu() {

		add_submenu_page(
			'settings.php',
			__( 'CCM19 Integration Options', 'options_page()' ),
			__( 'CCM19 Cookie Consent', 'options_page()' ),
			'manage_network_options',
			$this -> settings_slug . '-page',
			[ $this, 'options_page' ]
		);

		add_settings_section(
			'ccm19-integration',
			__( 'General settings', 'ccm19-integration' ),
			[ $this, 'options_page_print_info' ],
			$this -> settings_slug . '-page'
		);


		register_setting( $this->settings_slug. '-page', 'ccm19_code', [
			'type'        => 'string',
			'description' => 'CCM19 Code snippet',
			'default'     => ''
		] );


		add_settings_field(
			'ccm19_code',
			__('CCM19 code snippet','multisite-settings'),//title
			[$this,'option_code_snippet_callback'],//callback
			$this->settings_slug.'-page',
			'ccm19-integration'
		);

	}

	/**
	 * @return void
	 *
	 * Callback: Updates all Network sites with the correct snippet
	 *
	 */
	public function update()
	{
		\check_admin_referer($this->settings_slug . '-page-options');
		global $new_whitelist_options;

		//Array with all options from blog/site
		$options = $new_whitelist_options[ $this->settings_slug . '-page'];

		foreach ($options as $option){

			if(isset($_POST[$option])){
				//never delete stripslashes if you use the POST function from WP
				update_site_option($option,stripslashes_deep($_POST[$option]));

			}else{
				delete_site_option($option);
			}
		}
		wp_safe_redirect(
			add_query_arg(
				array(
					'page'      => $this->settings_slug . '-page',
					'updated'   => 'true',
				),
				network_admin_url('settings.php')
			)
		);
		exit;
	}

	//custom error log function for development only
	public function custom_logs()
	{
		if ( ! function_exists('write_log')) {
			function write_log ( $log )  {
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
				} else {
					error_log( $log );
				}
			}
		}
	}
}
