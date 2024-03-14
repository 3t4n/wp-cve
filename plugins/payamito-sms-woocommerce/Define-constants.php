<?php

// don't call the file directly
if (!defined('ABSPATH')) {
	die('direct access abort ');
}

if (!defined('PAYAMITO_WC_BASENAME')) {
	defined('PAYAMITO_WC_BASENAME') || define('PAYAMITO_WC_BASENAME', __DIR__);
}
if (!defined('VERSION_PAYAMITO_WOOCOMMERC')) {
	define('VERSION_PAYAMITO_WOOCOMMERC', '1.3.5');
}
if (!defined('PAYAMITO_WC_URL')) {
	define('PAYAMITO_WC_URL', plugin_dir_url(__FILE__));
}
if (!defined('PAYAMITO_WC_DIR')) {
	define('PAYAMITO_WC_DIR', PAYAMITO_WC_BASENAME);
}
if (!defined('PAYAMITO_WC_COR_DIR')) {
	define('PAYAMITO_WC_COR_DIR', PAYAMITO_WC_DIR . '/includes/core/payamito-core');
}
if (!defined('PAYAMITO_WC_COR_VER')) {
	define('PAYAMITO_WC_COR_VER', '2.1.8');
}
