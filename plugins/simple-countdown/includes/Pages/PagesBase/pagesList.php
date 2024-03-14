<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Pages\PagesBase;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Pages\QuickCountdownTimerPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Pages.
 */
function setup_pages() {
	QuickCountdownTimerPage::get_instance();
}
