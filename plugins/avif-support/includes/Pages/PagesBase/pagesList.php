<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\Pages\PagesBase;

use GPLSCore\GPLS_PLUGIN_AVFSTW\Pages\SettingsPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Pages.
 */
function setup_pages() {
	SettingsPage::init();
}
