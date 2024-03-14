<?php
/*
Plugin Name: Sticky Product Add to Cart and Checkout Bar for WooCommerce
Plugin URI: https://wordpress.org/plugins/woo-sticky-product-bar/
Description: This plugin allows you to add a sticky bar to the product, cart and checkout pages.
Version: 1.0.47
Tested up to: 6.4
Text Domain: wc-sticky-product-bar
Author: OneTeamSoftware
Author URI: http://oneteamsoftware.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace OneTeamSoftware\WooCommerce\StickyProductBar;

// No direct file access
defined('ABSPATH') || exit;

require_once(__DIR__ . '/includes/StickyProductBar/Plugin.php');

(new Plugin(__FILE__, '1.0.47'))->register();
