<?php
/**
 * Admin Settings Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Settings Class.
 */
class Settings {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 15 );
		add_action( 'admin_init', [ $this, 'redirect' ] );

		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( TLP_FOOD_MENU_PLUGIN_ACTIVE_FILE_NAME ), [ $this, 'marketing' ] );
	}

	/**
	 * Admin menu.
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=' . TLPFoodMenu()->post_type,
			esc_html__( 'Food Menu Settings', 'tlp-food-menu' ),
			esc_html__( 'Settings', 'tlp-food-menu' ),
			'administrator',
			'food_menu_settings',
			[
				$this,
				'render_settings_page',
			]
		);

		add_submenu_page(
			'edit.php?post_type=' . TLPFoodMenu()->post_type,
			esc_html__( 'Get Help', 'tlp-food-menu' ),
			esc_html__( 'Get Help', 'tlp-food-menu' ),
			'administrator',
			'rtfm_get_help',
			[
				$this,
				'render_help_page',
			]
		);
	}

	/**
	 * Render Settings.
	 *
	 * @return void|string
	 */
	public function render_settings_page() {
		Fns::renderView( 'settings' );
	}

	/**
	 * Render Help.
	 *
	 * @return void|string
	 */
	public function render_help_page() {
		Fns::renderView( 'help' );
	}

	/**
	 * Plugin links row.
	 *
	 * @param array  $links Links.
	 * @param string $file File.
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( TLP_FOOD_MENU_PLUGIN_ACTIVE_FILE_NAME === $file ) {
			$report_url         = 'https://www.radiustheme.com/contact/';
			$row_meta['issues'] = sprintf(
				'%2$s <a target="_blank" href="%1$s"><span style="color: red">%3$s</span></a>',
				esc_url( $report_url ),
				esc_html__( 'Facing issue?', 'tlp-food-menu' ),
				esc_html__( 'Please open a support ticket.', 'tlp-food-menu' )
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * Action link.
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public function marketing( $links ) {
		$links[] = '<a target="_blank" href="' . esc_url( 'https://www.radiustheme.com/demo/plugins/food-menu/' ) . '">Demo</a>';
		$links[] = '<a target="_blank" href="' . esc_url( 'https://www.radiustheme.com/docs/food-menu/getting-started/installations/' ) . '">Documentation</a>';

		if ( ! TLPFoodMenu()->has_pro() ) {
			$links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;"  href="' . esc_url( 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/' ) . '">Get Pro</a>';
		}

		return $links;
	}

	/**
	 * Redirect.
	 *
	 * @return void
	 */
	public function redirect() {
		if ( get_option( 'rtfm_activation_redirect', false ) ) {
			delete_option( 'rtfm_activation_redirect' );
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . TLPFoodMenu()->post_type . '&page=rtfm_get_help' ) );
		}
	}
}
