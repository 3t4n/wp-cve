<?php
/**
 * Plugin Name:       Sheets To WP Table Live Sync
 * Plugin URI:        https://wppool.dev/sheets-to-wp-table-live-sync/
 * Description:       Display Google Spreadsheet data to WordPress table in just a few clicks and keep the data always synced. Organize and display all your spreadsheet data in your WordPress quickly and effortlessly.
 * Version:           3.6.1
 * Requires at least: 5.0
 * Requires PHP:      5.4
 * Author:            WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       sheetstowptable
 * Domain Path:       /languages/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txts
 *
 * @package SWPTLS
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

define( 'SWPTLS_VERSION', '3.6.1' );
define( 'SWPTLS_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SWPTLS_BASE_URL', plugin_dir_url( __FILE__ ) );
define( 'SWPTLS_PLUGIN_FILE', __FILE__ );
define( 'SWPTLS_PLUGIN_NAME', 'Sheets To WP Table Live Sync' );

if ( defined( 'SWPTLS_PRO_VERSION' ) && SWPTLS_VERSION > '3.0.0' && SWPTLS_PRO_VERSION < '3.0.0' ) {
	add_action( 'admin_notices', function () {
		printf(
			'<div class="notice notice-error is-dismissible"><h3><strong>%s </strong></h3><p>%s</p></div>',
			esc_html__( 'Sheets to WP Table Live Sync - Pro Plugin', 'sheetstowptable' ),
			esc_html__( 'cannot be activated - requires at least 3.0.0 Plugin automatically deactivated.', 'sheetstowptable' )
		);
	});

	deactivate_plugins( SWPTLS_PRO_PLUGIN_FILE );
}

// Define the class and the function.
require_once __DIR__ . '/app/SWPTLS.php';
swptls();
