<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CptsBase;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownTimerCPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init CPTs.
 */
function setup_cpts() {
	CountDownTimerCPT::init();
}
