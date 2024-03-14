<?php
/**
 *  Front end changes.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Remove WordPress version number meta generator Tag
 *
 * @return void
 */
function white_label_remove_wordpress_version_number_meta_generator()
{
    $remove_wordpress_version_number_meta_generator = white_label_get_option('remove_wordpress_version_number_meta_generator', 'white_label_front_end', false);

    if ($remove_wordpress_version_number_meta_generator === 'on') {
        remove_action('wp_head', 'wp_generator');
    }
}

add_action('init', 'white_label_remove_wordpress_version_number_meta_generator');

/**
 * Remove WordPress version number from RSS feed
 *
 * @return void
 */
function white_label_remove_wordpress_version_number_rss_feed($version)
{
    $remove_wordpress_version_number_rss_feed = white_label_get_option('remove_wordpress_version_number_rss_feed', 'white_label_front_end', false);

    if ($remove_wordpress_version_number_rss_feed === 'on') {
        $version = '';
    }
    
    return $version;
}

add_filter('the_generator', 'white_label_remove_wordpress_version_number_rss_feed');

/**
 * Remove WordPress version number from stylesheets
 *
 * @return void
 */
function white_label_remove_wordpress_version_number_stylesheets($src)
{
    global $wp_version;

    $remove_wordpress_version_number_stylesheets = white_label_get_option('remove_wordpress_version_number_stylesheets', 'white_label_front_end', false);

    if ($remove_wordpress_version_number_stylesheets === 'on') {
        if (strpos($src, 'ver='.$wp_version)) {
            $src = remove_query_arg('ver', $src);
        }
    }

    return $src;
}

add_filter('style_loader_src', 'white_label_remove_wordpress_version_number_stylesheets', 999);

/**
 * Remove WordPress version number from scripts
 *
 * @return void
 */
function white_label_remove_wordpress_version_number_scripts($src)
{
    global $wp_version;

    $remove_wordpress_version_number_scripts = white_label_get_option('remove_wordpress_version_number_scripts', 'white_label_front_end', false);

    if ($remove_wordpress_version_number_scripts === 'on') {
        if (strpos($src, 'ver='.$wp_version)) {
            $src = remove_query_arg('ver', $src);
        }
    }

    return $src;
}

add_filter('script_loader_src', 'white_label_remove_wordpress_version_number_scripts', 999);