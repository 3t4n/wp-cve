<?php
/**
 * Plugin Name:     Ultimate Carousel For Divi
 * Plugin URI:      https://wptools.app/wordpress-plugin/ultimate-divi-carousel-for-image-post-type-taxonomy-woocommerce/
 * Description:     Create stunning, branded carousels with ease. Showcase your products, post types, categories, and images like never before with Ultimate Divi Carousel
 * Author:          wpt00ls
 * Author URI:      https://wptools.app
 * Text Domain:     ultimate-carousel-for-divi
 * Domain Path:     /languages
 * Version:         4.6.0
 *
 * @package         Ultimate_Carousel_For_Divi
 */

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/freemius.php";

$loader                   = \WPT\UltimateDiviCarousel\Loader::getInstance();
$loader["plugin_name"]    = "Ultimate Carousel For Divi";
$loader["plugin_version"] = "4.6.0";
$loader["plugin_dir"]     = __DIR__;
$loader["plugin_slug"]    = basename(__DIR__);
$loader["plugin_url"]     = plugins_url("/" . $loader["plugin_slug"]);
$loader["plugin_file"]    = __FILE__;

$loader->run();
