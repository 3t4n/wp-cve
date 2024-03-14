<?php
/**
* Plugin Name: Magni Image Flip for WooCommerce
* Plugin URI: http://magnigenie.com/downloads/magni-image-flip
* Description: Magni Image Flip adds a flip effect on your woocommerce product thumbnail images. It takes the first image from the gallery and uses it as a second image for the product.
* Version: 1.2
* Author: Magnigenie
* Author URI: http://magnigenie.com
* Text Domain: woomi
* Domain Path: /languages/
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct file access
! defined( 'ABSPATH' ) AND exit;

define('WOOMI_FILE', __FILE__);
define('WOOMI_PATH', plugin_dir_path(__FILE__));
define('WOOMI_BASE', plugin_basename(__FILE__));

add_action('plugins_loaded', 'woomi_load_textdomain');

function woomi_load_textdomain() {
	load_plugin_textdomain( 'woomi', false, dirname( plugin_basename( __FILE__ ) ). '/lang/' );
}

require WOOMI_PATH . '/includes/magni-image-flipper.php';

new Woo_Magni_Image();