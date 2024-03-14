<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs\Base;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs\SettingsAJAX;

/**
 * Setup AJAXs.
 *
 * @return void
 */
function setup_ajaxs() {
	SettingsAJAX::init();
}
