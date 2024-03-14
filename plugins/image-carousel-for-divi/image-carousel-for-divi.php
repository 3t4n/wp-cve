<?php
/**
 * Plugin Name:     Image Carousel For Divi
 * Description:     A divi image carousel module to create a slide-show with image.
 * Author:          WP Tools
 * Author URI:      https://wptools.app
 * Text Domain:     image-carousel-using-divi
 * Domain Path:     /languages
 * Version:         1.7.0
 *
 * @package         Divi_Carousel_Images
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/freemius.php';

$loader = \WPT\DiviCarouselImages\Loader::get_instance();

$loader['name']    = 'Image Carousel For Divi';
$loader['version'] = '1.7.0';
$loader['dir']     = __DIR__;
$loader['url']     = plugins_url('/' . basename(__DIR__));
$loader['file']    = __FILE__;
$loader['slug']    = 'image-carousel-using-divi';

$loader->run();
