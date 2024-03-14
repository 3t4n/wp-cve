<?php

/**
 * Plugin Name: Timeline Blocks for Gutenberg
 * Plugin URI: https://wordpress.org/plugins/timeline-blocks/
 * Description: A beautiful timeline block to showcase your posts in timeline presentation with multiple templates availability.
 * Author: Techeshta
 * Author URI: https://www.techeshta.com
 * Version: 1.1.6
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: timeline-blocks
 */
/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

define('TB_DOMAIN', 'timeline-blocks');
define('TB_DIR', plugin_dir_path(__FILE__));

/**
 * Initialize the blocks
 */
function tb_timeline_loader() {
    /**
     * Load the blocks functionality
     */
    require_once plugin_dir_path(__FILE__) . 'dist/init.php';

    /**
     * Load Post Grid PHP
     */
    require_once plugin_dir_path(__FILE__) . 'src/blocks/index.php';
}

add_action('plugins_loaded', 'tb_timeline_loader');

/**
 * Load the plugin text-domain
 */
function tb_timeline_init() {
    load_plugin_textdomain('timeline-blocks', false, basename(dirname(__FILE__)) . '/src/languages');
}

add_action('init', 'tb_timeline_init');

/**
 * Add a check for our plugin before redirecting
 */
function tb_timeline_activate() {
    add_option('tb_timeline_gutenberg_do_activation_redirect', true);
}

register_activation_hook(__FILE__, 'tb_timeline_activate');

/**
 * Add image sizes
 */
function tb_timeline_image_sizes() {
    // Post Grid Block
    add_image_size('tb-timeline-landscape', 600, 400, true);
    add_image_size('tb-timeline-square', 600, 600, true);
}

add_action('after_setup_theme', 'tb_timeline_image_sizes');
