<?php
/**
* Plugin Name
*
* @package           PluginPackage
* @author            Michael Gangolf
* @copyright         2022 Michael Gangolf
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Slide everything for Elementor
* Plugin URI:        https://wordpress.org/plugins/category-slider-for-elementor/
* Description:       Creates a simple Swiper slider out of container elements
* Version:           1.5.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Michael Gangolf
* Author URI:        https://www.migaweb.de/
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       miga_slide_everything
* Elementor tested up to:  3.19.0
*/

use Elementor\Plugin;

add_action('init', static function () {
    if (! did_action('elementor/loaded')) {
        return false;
    }

    require_once(__DIR__ . '/widgets/SlideEverything.php');
    \Elementor\Plugin::instance()->widgets_manager->register(new \Elementor_Widget_miga_slide_everything());
});
