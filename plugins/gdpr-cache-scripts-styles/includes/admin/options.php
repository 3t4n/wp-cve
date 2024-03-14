<?php
/**
 * Integrates the plugin’s options on the wp-admin dashboard.
 *
 * @package GdprCache
 */

namespace GdprCache;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


// ----------------------------------------------------------------------------

add_action( 'admin_menu', __NAMESPACE__ . '\register_admin_menu' );

// ----------------------------------------------------------------------------


/**
 * Registers a new admin menu for our plugin.
 *
 * @since 1.0.0
 * @return void
 */
function register_admin_menu() {
	$hook = add_management_page(
		__( 'GDPR Cache Options', 'gdpr-cache' ),
		__( 'GDPR Cache', 'gdpr-cache' ),
		GDPR_CACHE_CAPABILITY,
		'gdpr-cache',
		__NAMESPACE__ . '\render_admin_page'
	);

	add_action( "admin_head-$hook", __NAMESPACE__ . '\show_action_notices' );

	// Spawn background worker when admin opens the options page.
	add_action( "admin_head-$hook", __NAMESPACE__ . '\spawn_worker' );
}


/**
 * Displays a success notice on the admin page after an action was performed.
 *
 * @since 1.0.0
 * @return void
 */
function show_action_notices() {
	if ( empty( $_GET['_wpnonce'] ) || empty( $_GET['update'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gdpr-cache' ) ) {
		return;
	}

	$notice = sanitize_key( wp_unslash( $_GET['update'] ) );

	if ( empty( $notice ) ) {
		return;
	}

	if ( 'refreshed' === $notice ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\admin_notice_refreshed' );
	} elseif ( 'purged' === $notice ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\admin_notice_purged' );
	}
}


/**
 * Renders the admin option-page of our plugin.
 *
 * @since 1.0.0
 * @return void
 */
function render_admin_page() {
	// Flush plugin cache when the Options Page is opened.
	wp_cache_delete( 'data', 'gdpr-cache' );

	// In case the cron task is not running, start it now.
	enable_cron();

	require GDPR_CACHE_PATH . 'templates/admin-options.php';
}


/**
 * Outputs an admin notice: Cache refreshed.
 *
 * @since 1.0.0
 * @return void
 */
function admin_notice_refreshed() {
	printf(
		'<div class="notice-%s notice is-dismissible"><p>%s</p></div>',
		'success',
		esc_html__( 'Refreshing the cache in the background', 'gdpr-cache' )
	);
}


/**
 * Outputs an admin notice: Cache purged.
 *
 * @since 1.0.1
 * @return void
 */
function admin_notice_purged() {
	printf(
		'<div class="notice-%s notice is-dismissible"><p>%s</p></div>',
		'success',
		esc_html__( 'Cache purged', 'gdpr-cache' )
	);
}
