<?php
/*
  Description: Booter - Bots & Crawlers Manager
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'NO direct access!' );
}

// if we can't find the files to include - delete self
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! file_exists( WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/booter-crawlers-manager.php' ) ) {
	unlink( __FILE__ );
} elseif ( is_plugin_active( 'booter-bots-crawlers-manager/booter-crawlers-manager.php' ) ) {
	require_once WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/booter-contstants.php';
	require_once WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/includes/Logger.php';
	require_once WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/includes/Utilities.php';
	require_once WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/includes/RequestBlocker.php';
	require_once WP_PLUGIN_DIR . '/booter-bots-crawlers-manager/includes/RateLimiter.php';

	\Upress\Booter\RequestBlocker::initialize();
	\Upress\Booter\RateLimiter::initialize();
}
