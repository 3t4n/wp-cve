<?php

namespace MailerSend;

use MailerSend\Admin\AdminMenu;

class MailerSend_SMTP {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @since       1.0.0
	 */
	public function __construct() {

		$this->init();
		$this->hooks();
	}

	/**
	 * Hook to update plugins action links
	 *
	 * @access      public
	 * @return      mixed
	 * @since       1.0.0
	 */
	public function pluginActionLinks( $links ) {

		if ( ! $this->hasServerRequirements() ) {

			$info = '<span style="color: #ff0000; font-weight: bold;">' . sprintf( esc_html__( 'PHP Version %1$s or newer required', 'mailersend-official-smtp-integration' ), MAILERSEND_SMTP_MIN_PHP_VERSION ) . '</span>';
			array_unshift( $links, $info );

			return $links;
		}

		$settings = array(
			'<a href="' . admin_url( 'admin.php?page=mailersend_config' ) . '">' . esc_html__( 'Settings', 'mailersend-official-smtp-integration' ) . '</a>'
		);

		return array_merge( $settings, $links );

	}

	/**
	 * Initialize
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function init() {

		if ( ! $this->hasServerRequirements() ) {
			return;
		}


		$this->run();
	}

	/**
	 * Setup hooks
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function hooks() {

		add_filter( 'plugin_action_links_' . MAILERSEND_SMTP_BASENAME, [ $this, 'pluginActionLinks' ], 10, 2 );
	}

	/**
	 * Check server requirements
	 *
	 * @access      private
	 * @return      boolean
	 * @since       1.0.0
	 */
	private function hasServerRequirements(): bool {

		if ( version_compare( phpversion(), MAILERSEND_SMTP_MIN_PHP_VERSION, '<' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Run, continue loading plugin
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function run() {

		new Mail();

		if ( is_admin() ) {

			new AdminMenu();
		}
	}
}
