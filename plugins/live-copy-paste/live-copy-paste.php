<?php

/**
 * Plugin Name: Live Copy Paste
 * Plugin URI: https://bdthemes.com/live-copy-paste/
 * Description: By using this plugin, you can easily import/paste all sections on your site from the Elementor Editor/Widget Demo/Ready-Made Pages and Blocks. One click to change the world.
 * Version: 1.3.0
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: live-copy-paste
 * Domain Path: /languages
 * License: GPL3
 * Elementor requires at least: 3.0.0
 * Elementor tested up to: 3.17.3
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


define('VERSION', '1.3.0');

require_once 'classes/class-live-copy-paste-loader.php';
