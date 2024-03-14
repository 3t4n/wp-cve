<?php

namespace CBChangeMailSender;

use CBChangeMailSender\Admin\AdminCore;

/**
 * Class Core.
 *
 * Handle all plugin initialization.
 *
 * @since 1.3.0
 */
class Core {

	/**
	 * Plugin URL.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	private $plugin_url = '';

	/**
	 * Plugin path.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	private $plugin_path = '';

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->plugin_url = rtrim( plugin_dir_url( __DIR__ ), '/\\' );
		$this->plugin_path = rtrim( plugin_dir_path( __DIR__ ), '/\\' );

		$this->hooks();
	}

	/**
	 * Hook the plugin.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function hooks() {

		add_action( 'init', [ $this, 'init' ] );

		add_filter( 'wp_mail_from', [ $this, 'filter_mail_from' ] );
		add_filter( 'wp_mail_from_name', [ $this, 'filter_mail_from_name' ] );

		if ( is_admin() ) {

			$this->get_admin();
		}
	}

	/**
	 * Initial plugin actions.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function init() {

		load_plugin_textdomain( 'cb-mail', false, plugin_basename( $this->get_plugin_path() ) . '/assets/languages' );
	}

	/**
	 * Replace the current "from email" used in emails.
	 *
	 * @param string $old Current "from email".
	 *
	 * @return string
	 */
	public function filter_mail_from( $old ) {

		$from_email = get_option( 'cb_mail_sender_email_id' );

		return empty( $from_email ) ? $old : $from_email;
	}

	/**
	 * Replace the current "from name" used in emails.
	 *
	 * @param string $old Current "from name".
	 *
	 * @return string
	 */
	public function filter_mail_from_name( $old ) {

		$from_name = get_option( 'cb_mail_sender_id' );

		return empty( $from_name ) ? $old : $from_name;
	}

	/**
	 * Load the plugin's admin functionalities.
	 *
	 * @since 1.3.0
	 *
	 * @return AdminCore
	 */
	public function get_admin() {

		static $admin;

		if ( ! isset( $admin ) ) {
			$admin = new AdminCore();
		}

		return $admin;
	}

	/**
	 * Get the URL to the assets directory.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public function get_assets_url() {

		return $this->plugin_url . '/assets';
	}

	/**
	 * Get the plugin path.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public function get_plugin_path() {

		return $this->plugin_path;
	}
}
