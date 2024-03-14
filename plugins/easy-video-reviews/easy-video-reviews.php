<?php

/**
 * Plugin Name:       Easy Video Reviews
 * Plugin URI:        http://wppool.dev/easy-video-reviews.
 * Description:       Easy Video Reviews is the best and easiest video review plugin for WordPress fully compatible with WooCommerce and Easy Digital Downloads plugins. Your customers can record and send video testimonials right from their browser and you can manage and showcase anywhere in your WordPress website.
 * Version:           2.0.2
 * Requires at least: 5.0
 * Requires PHP:      5.6
 * Author:            WPPOOL
 * Author URI:        https://wppool.dev/.
 * Text Domain:       easy-video-reviews
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt.
 * Domain Path:       /languages
 *
 * @package           EASY_VIDEO_REVIEWS_FILE
 * @link              http://wppool.dev/easy-video-reviews.
 * @since             1.0.0
 * @author            WPPOOL
 */


/**
 * Are you a Developer?
 * No need to modify the code.
 * If you are a developer, you can extend functionalities of Easy Video Reviews.
 * by following this documentation https://wppool.dev/docs-category/how-to-use-easy-video-reviews/.
 */

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

// Defines Easy Video Reviews plugin file..
define('EASY_VIDEO_REVIEWS_FILE', __FILE__);

/**
 * Easy Video Review Version
 */
define('EASY_VIDEO_REVIEWS_VERSION', '2.0.2');

/**
 * Boot loader
 */
require_once __DIR__ . '/includes/class-boot.php';

/**
 * Easy Video Reviews developed by WPPOOL.DEV.
 * For more information please visit http://wppool.dev/..
 */
