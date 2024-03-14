<?php

/**
 * Plugin Name: RabbitLoader
 * Plugin URI: https://rabbitloader.com
 * Author:       RabbitLoader
 * Author URI:   https://rabbitloader.com/
 * Description: RabbitLoader can improve Google PageSpeed score and get you 100 out of 100 by improving the page load time to just a few milliseconds. It improves the Core Web Vitals score for your pages and boost PageSpeed score to help better search rankings and best the experience for your end user.
 * Version: 2.19.18
 * Text Domain: rabbit-loader
 */
/*
RabbitLoader is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. */

defined('ABSPATH') or die('ABSPATH not defined');

$plug_dir = dirname(__FILE__) . '/';
include_once($plug_dir . 'autoload.php');

add_action('save_post', function ($post_ID, $post, $update) {
    if (strcmp($post->post_type, 'nav_menu_item') === 0) {
        RL21UtilWP::onPostChange(RL21UtilWP::POST_ID_ALL);
    } else {
        RL21UtilWP::onPostChange($post_ID);
    }
}, 10, 3);
add_action('wp_insert_post', function ($post_ID, $post, $update) {
    RL21UtilWP::onPostChange($post_ID);
}, 10, 3);
add_action('draft_to_publish', function ($post) {
    if (!empty($post)) {
        RL21UtilWP::onPostChange($post->ID);
    }
}, 10, 1);
add_action('pending_to_publish', function ($post) {
    if (!empty($post)) {
        RL21UtilWP::onPostChange($post->ID);
    }
}, 10, 1);
add_action('transition_post_status', function ($new_status, $old_status, $post) {
    //No need to purge if the post was not public before and its not even now
    if ('publish' !== $old_status && 'publish' !== $new_status) {
        return;
    }
    RL21UtilWP::onPostChange($post->ID);
}, 10, 3);
add_action('transition_comment_status', function ($new_status, $old_status, $comment) {
    RL21UtilWP::onPostChange($comment->comment_post_ID);
}, 10, 3);
add_action('comment_post', function ($comment_ID, $comment_approved, $commentdata) {
    if ($comment_approved == 1) {
        $comment = get_comment($comment_ID);
        RL21UtilWP::onPostChange($comment->comment_post_ID);
    }
}, 10, 3);

add_action('switch_theme', function () {
    RL21UtilWP::onPostChange('all');
}, 10, 0);

add_action('woocommerce_updated_product_stock', function ($product_id) {
    RL21UtilWP::onPostChange($product_id);
}, 10, 1);
add_action('woocommerce_updated_product_price', function ($product_id) {
    RL21UtilWP::onPostChange($product_id);
}, 10, 1);
add_action('woocommerce_rest_insert_product', function ($post, $request, $creating) {
    RL21UtilWP::onPostChange($post->ID);
}, 10, 3);
add_action('woocommerce_rest_insert_product_object', function ($product, $request, $creating) {
    RL21UtilWP::onPostChange($product->id);
}, 10, 3);
add_action('woocommerce_product_object_updated_props', function ($product, $updated) {
    RL21UtilWP::onPostChange($product->get_id());
}, 0, 2);

register_shutdown_function(function () {
    RL21UtilWP::execute_purge($count);
});

if (is_admin()) {

    register_activation_hook(__FILE__, 'RabbitLoader_21_Admin::activate_advanced_cache');
    register_deactivation_hook(__FILE__, 'RabbitLoader_21_Admin::plugin_deactivate');
    register_uninstall_hook(__FILE__, 'RabbitLoader_21_Admin::plugin_uninstall');
    add_filter('plugin_action_links_rabbit-loader/rabbit-loader.php', 'RabbitLoader_21_Admin::settings_link');

    if (!defined('RABBITLOADER_UNINSTALL_MODE')) {
        RabbitLoader_21_Admin::addActions();
    }
} else {
    if (!defined('RABBITLOADER_AC_ACTIVE')) {
        //advance cache failed to work, as a fallback we can intercept requests here
        RabbitLoader_21_Public::process_incoming_request('fallback');
    }
    RabbitLoader_21_Public::addActions();
}

if (defined("WP_CLI") && WP_CLI) {
    include_once($plug_dir . 'wp-cli.php');
}
