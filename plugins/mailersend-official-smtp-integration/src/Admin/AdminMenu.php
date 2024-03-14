<?php

namespace MailerSend\Admin;

use MailerSend\Actions;
use MailerSend\CheckConflict;

class AdminMenu {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'create_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	/**
	 * Initialize Menu and check for actions
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function init() {

		// check if action is defined
		if ( isset( $_POST['action'] ) ) {

			if ( $_POST['action'] == 'update_credentials' ) {

				( new Actions() )->saveCredentials();
			}

			if ( $_POST['action'] == 'update_settings' ) {

				( new Actions() )->saveConfig();
			}

			if ( $_POST['action'] == 'mailer_test' ) {

				( new Actions() )->sendTest();
			}

			if ( $_POST['action'] == 'mailer_delete' ) {

				( new Actions() )->deleteConfig();
			}
		}

		// check for plugin conflicts
		if ( CheckConflict::hasConflict() ) {
			add_action( 'admin_notices', [ '\\MailerSend\\CheckConflict', 'viewNotice' ] );
		}

	}

	/**
	 * Add Admin Menu
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function create_admin_menu() {

		// add menu page link
		$hook = add_menu_page(
			'MailerSend SMTP', 'MailerSend SMTP', 'manage_options', 'mailersend_config',
			[ $this, 'get_admin_view' ], MAILERSEND_SMTP_URL . 'assets/images/icon.svg', 80
		);

		// load scripts and styles
		add_action( 'load-' . $hook, [ $this, 'load_scripts_and_styles' ] );

	}

	/**
	 * Action to load scripts and styles
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function load_scripts_and_styles() {
		add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_styles' ] );
	}

	/**
	 * Load scripts and styles
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function load_admin_styles() {

		wp_register_style(
			'mailersend',
			MAILERSEND_SMTP_URL . '/assets/css/mailersend.css',
			[],
			MAILERSEND_SMTP_VER
		);

		wp_enqueue_style( 'mailersend' );

		wp_enqueue_script(
			'mailersend',
			MAILERSEND_SMTP_URL . 'assets/js/mailersend.js',
			[ 'jquery' ],
			MAILERSEND_SMTP_VER
		);
	}

	/**
	 * Get AdminView to present layout
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function get_admin_view() {
		new AdminView();
	}
}
