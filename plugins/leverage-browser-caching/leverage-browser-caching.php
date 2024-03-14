<?php
/**
 * Plugin Name:	Leverage Browser Caching
 * Description:	It will fix Leverage Browser Caching issue ( Apache Server Only ).
 * Version:		2.3
 * Author:		Rinku Yadav
 * Author URI:	http://rinkuyadav.com
 * License:		GPLv2 or later
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lbrowserc
 * Domain Path: /languages
 *
 * @package     Leverage Browser Caching
 */

// Exit if directly accessed files.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if not admin screen.
if ( ! is_admin() ) {
	return;
}

// Set path to constant.
if ( ! defined( 'LBROWSERC_PATH' ) ) {
	define( 'LBROWSERC_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}

// Set url to constant.
if ( ! defined( 'LBROWSERC_URL' ) ) {
	define( 'LBROWSERC_URL', plugin_dir_url( __FILE__ ) );
}

// Set __FILE__ to constant.
if ( ! defined( 'LBROWSERC_FILE' ) ) {
	define( 'LBROWSERC_FILE', __FILE__ );
}

// Set plugin base.
if ( ! defined( 'LBROWSERC_BASE_FILE' ) ) {
	define( 'LBROWSERC_BASE_FILE', plugin_basename( __FILE__ ) );
}

// Load core class.
require_once LBROWSERC_PATH . 'inc/classes/class-lbrowserc-core.php';
new Lbrowserc_Core();
