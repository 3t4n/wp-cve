<?php
/**
 * Plugin Name:     AffiliateX
 * Plugin URI:      https://kraftplugins.com/affiliatex/
 * Description:     Create a professional-looking affiliate websites with highly customizable blocks that help in increasing the conversion rate and boost your affiliate income.
 * Author:          Kraft Plugins
 * Author URI:      https://kraftplugins.com
 * Text Domain:     affiliatex
 * Domain Path:     /languages
 * Version:         1.2.2
 * Requires at least: 5.8
 * Requires PHP:      7.0
 *
 * @package         AffiliateX
 */

use AffiliateX\AffiliateX;

defined( 'ABSPATH' ) || exit;

// include autoloader
require_once __DIR__ . '/vendor/autoload.php';

if ( ! defined( 'AFFILIATEX_PLUGIN_FILE' ) ) {
	define( 'AFFILIATEX_PLUGIN_FILE', __FILE__ );
}


/**
 * Init function
 */
function AffiliateX_init() {
	return AffiliateX::instance();
}

$GLOBALS['AffiliateX'] = AffiliateX_init();

// Invokes all functions attached to the 'affiliatex_free_loaded' hook
do_action( 'affiliatex_free_loaded' );
