<?php
/**
 * Plugin Name:     WP Tools Divi Product Carousel
 * Description:     A divi product carousel module to create a slide-show with WooCommerce product.
 * Author:          WP Tools
 * Author URI:      https://wptools.app
 * Text Domain:     wp-tools-divi-product-carousel
 * Domain Path:     /languages
 * Version:         1.6.0
 *
 * @package         Divi_Product_Carousel
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/freemius.php';

$loader = \WPT\DiviProductCarousel\Loader::get_instance();

$loader['name']    = 'WP Tools Divi Product Carousel';
$loader['version'] = '1.6.0';
$loader['dir']     = __DIR__;
$loader['url']     = plugins_url('/' . basename(__DIR__));
$loader['file']    = __FILE__;
$loader['slug']    = 'wp-tools-divi-product-carousel';

$loader->run();
