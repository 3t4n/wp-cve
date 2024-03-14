<?php
/**
 * Upgrade actions.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Upgrade actions.
 */
class Upgrade {

	/**
	 * Check Plugins Version
	 *
	 * @return bool
	 */
	public static function check_plugin_version() {
		if ( version_compare( FOOD_MENU_PRO_VERSION, '3', '<' ) ) {
			self::notice();

			return false;
		}

		return true;
	}

	/**
	 * Admin Notice
	 *
	 * @return void
	 */
	public static function notice() {
		add_action(
			'admin_notices',
			function () {
				$class     = 'notice notice-error';
				$text      = esc_html__( 'Food Menu Pro', 'tlp-food-menu' );
				$text_free = esc_html__( 'Food Menu', 'tlp-food-menu' );
				$link      = add_query_arg(
					[
						'tab'       => 'plugin-information',
						'plugin'    => 'tlp-food-menu',
						'TB_iframe' => 'true',
						'width'     => '640',
						'height'    => '500',
					],
					admin_url( 'plugin-install.php' )
				);
				$link_pro  = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';

				printf(
					'<div class="%1$s"><p><b>Error: <a target="_blank" href="%2$s"><strong>%3$s</strong></a> plugin cannot be activated.</b><br><br> <a target="_blank" href="%2$s"><strong>%3$s</strong></a> plugin is not compatible with the current version of <a class="thickbox open-plugin-details-modal" href="%5$s"><strong>%4$s</strong></a> plugin and hence it is kept deactivated. You need to update <a target="_blank" href="%2$s"><strong>%3$s</strong></a> plugin to 3.0.0 or more to get the pro features.</p></div>',
					esc_attr( $class ),
					esc_url( $link_pro ),
					esc_html( $text ),
					esc_html( $text_free ),
					esc_url( $link )
				);
			}
		);
	}

	/**
	 * Deactivate plugin.
	 *
	 * @return void
	 */
	public static function deactivate() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		\deactivate_plugins( \plugin_basename( FOOD_MENU_PRO_PLUGIN_ACTIVE_FILE_NAME ) );

		unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}
