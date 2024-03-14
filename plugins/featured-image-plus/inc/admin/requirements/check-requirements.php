<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly

/**
 * Stop plugin activation if minimum requirement aren't met & display error notice.
 */
function fip_check_requirements() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( version_compare( PHP_VERSION, FIP_MIN_PHP_VERSION ) >= 0
		&& version_compare( $GLOBALS['wp_version'], FIP_MIN_WP_VERSION ) >= 0 ) {
		load_plugin_textdomain( FIP_PLUGIN_TEXTDOMAIN, false, FIP_PLUGIN_BASENAME . 'lang' );

		add_action(
			'plugin_action_links',
			__NAMESPACE__ . '\fip_add_action_links',
			10,
			2
		);

		add_action(
			'admin_enqueue_scripts',
			__NAMESPACE__ . '\fip_enqueue_admin_assets'
		);
	} else {
		$message = sprintf(
			wp_kses(
				/* translators: %1$s is replaced with "Plugin Name" */
				/* translators: %2$s is replaced with "Min PHP Version" */
				/* translators: %3$s is replaced with "Min WP Version" */
				__( '%1$s requires a minimum of PHP %2$s and WordPress %3$s', 'featured-image-plus' ),
				json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
			),
			'<strong>' . FIP_PLUGIN_NAME . '</strong>',
			'<em>' . FIP_MIN_PHP_VERSION . '</em>',
			'<em>' . FIP_MIN_WP_VERSION . '</em>.<br />'
		);

		$message .= sprintf(
			wp_kses(
				/* translators: %1$s is replaced with "PHP Version" */
				/* translators: %2$s is replaced with "WordPress Version" */
				__( 'You are currently running PHP %1$s and WordPress %2$s.', 'featured-image-plus' ),
				json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
			),
			'<strong>' . PHP_VERSION . '</strong>',
			'<strong>' . $GLOBALS['wp_version'] . '</strong>'
		);

		printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );

		deactivate_plugins( FIP_PLUGIN_BASENAME );
	}
}

add_action( 'admin_init', __NAMESPACE__ . '\fip_check_requirements' );
