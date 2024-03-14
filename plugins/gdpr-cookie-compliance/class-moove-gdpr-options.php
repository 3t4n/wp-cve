<?php
/**
 * Moove_GDPR_Options File Doc Comment
 *
 * @category Moove_GDPR_Options
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Moove_GDPR_Options Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Options
 * @author   Moove Agency
 */
class Moove_GDPR_Options {
	/**
	 * Global options
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'moove_gdpr_admin_menu' ) );
	}

	/**
	 * Moove feed importer page added to settings
	 *
	 * @return  void
	 */
	public function moove_gdpr_admin_menu() {
		$gdpr_settings_page = add_menu_page(
			'GDPR Cookie', // Page_title.
			'GDPR Cookie Compliance', // Menu_title.
			apply_filters( 'gdpr_options_page_cap', 'manage_options' ), // Capability.
			'moove-gdpr', // Menu_slug.
			array( &$this, 'moove_gdpr_settings_page' ), // Function.
			'data:image/svg+xml;base64,PCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMjQiIGhlaWdodD0iMTAyNCIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCI+Cjx0aXRsZT48L3RpdGxlPgo8ZyBpZD0iaWNvbW9vbi1pZ25vcmUiPgo8L2c+CjxwYXRoIGZpbGw9IiNhMGE1YWEiIGQ9Ik04NDAuNzA0IDUzMS40NTZ2MTgxLjI0OHEwIDY4LjYwOC00OC4xMjggMTE2LjczNnQtMTE1LjcxMiA0OC4xMjhoLTQ3Ni4xNnEtNjcuNTg0IDAtMTE1LjcxMi00OC4xMjh0LTQ4LjEyOC0xMTYuNzM2di00NzUuMTM2cTAtNjcuNTg0IDQ4LjEyOC0xMTUuNzEydDExNS43MTItNDkuMTUyaDQ3Ni4xNnEzNS44NCAwIDY2LjU2IDE0LjMzNiA4LjE5MiA0LjA5NiAxMC4yNCAxMy4zMTIgMi4wNDggMTAuMjQtNS4xMiAxNi4zODRsLTI3LjY0OCAyOC42NzJxLTYuMTQ0IDUuMTItMTMuMzEyIDUuMTItMi4wNDggMC01LjEyLTEuMDI0LTEzLjMxMi0zLjA3Mi0yNS42LTMuMDcyaC00NzYuMTZxLTM2Ljg2NCAwLTY0LjUxMiAyNi42MjR0LTI2LjYyNCA2NC41MTJ2NDc1LjEzNnEwIDM3Ljg4OCAyNi42MjQgNjQuNTEydDY0LjUxMiAyNy42NDhoNDc2LjE2cTM3Ljg4OCAwIDY0LjUxMi0yNy42NDh0MjYuNjI0LTY0LjUxMnYtMTQ0LjM4NHEwLTguMTkyIDUuMTItMTMuMzEybDM2Ljg2NC0zNS44NHE1LjEyLTYuMTQ0IDEzLjMxMi02LjE0NCAzLjA3MiAwIDYuMTQ0IDIuMDQ4IDExLjI2NCA0LjA5NiAxMS4yNjQgMTYuMzg0ek05NzIuOCAyNTEuOTA0bC00NjQuODk2IDQ2NC44OTZxLTEzLjMxMiAxNC4zMzYtMzIuNzY4IDE0LjMzNnQtMzEuNzQ0LTE0LjMzNmwtMjQ1Ljc2LTI0NS43NnEtMTQuMzM2LTEzLjMxMi0xNC4zMzYtMzEuNzQ0dDE0LjMzNi0zMi43NjhsNjIuNDY0LTYzLjQ4OHExMy4zMTItMTMuMzEyIDMyLjc2OC0xMy4zMTJ0MzIuNzY4IDEzLjMxMmwxNDkuNTA0IDE1MC41MjggMzY5LjY2NC0zNjkuNjY0cTE0LjMzNi0xMy4zMTIgMzIuNzY4LTEzLjMxMnQzMi43NjggMTMuMzEybDYyLjQ2NCA2Mi40NjRxMTQuMzM2IDE0LjMzNiAxNC4zMzYgMzIuNzY4dC0xNC4zMzYgMzIuNzY4eiI+PC9wYXRoPgo8L3N2Zz4K', // Icon.
			90 // Position.
		);

		$plugin_tabs = gdpr_get_admin_submenu_items();

		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		if ( isset( $current_tab ) && '' !== $current_tab ) :
			$active_tab = $current_tab;
		else :
			$active_tab = 'branding';
		endif; // end if.

		$plugin_link = esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=branding' ) );

		foreach ( $plugin_tabs as $plugin_tab ) :
			$gdpr__sub_settings_page = add_submenu_page(
				'moove-gdpr',
				$plugin_tab['title'],
				$plugin_tab['title'],
				apply_filters( 'gdpr_options_page_cap', 'manage_options' ), // Capability.
				'moove-gdpr_' . $plugin_tab['slug'],
				array( &$this, 'moove_gdpr_settings_page' )
			);
			add_action( 'load-' . $gdpr__sub_settings_page, array( 'Moove_GDPR_Actions', 'moove_gdpr_admin_scripts' ) );
		endforeach;

		add_action( 'load-' . $gdpr_settings_page, array( 'Moove_GDPR_Actions', 'moove_gdpr_admin_scripts' ) );
	}
	/**
	 * Settings page registration
	 *
	 * @return void
	 */
	public function moove_gdpr_settings_page() {
		$data     = array();
		$view_cnt = new GDPR_View();
		$content  = $view_cnt->load( 'moove.admin.settings.settings-page', $data );
		apply_filters( 'gdpr_cc_keephtml', $content, true );
	}

}
new Moove_GDPR_Options();
