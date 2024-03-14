<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\pages;

use GPLSCore\GPLS_PLUGIN_WPSCTR\pages\CountdownTimerPage;
use GPLSCore\GPLS_PLUGIN_WPSCTR\pages\QuickCountdownTimerPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Pages.
 */
function setup_pages() {
	CountdownTimerPage::get_instance();
	QuickCountdownTimerPage::get_instance();
}
