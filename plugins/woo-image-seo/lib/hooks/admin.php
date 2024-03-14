<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load translation text domain and AJAX actions
 */
add_action( 'init', function() {
    load_plugin_textdomain( 'woo-image-seo', false, 'woo-image-seo/i18n/languages' );

    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/ajax-actions/save-settings.php';
    require_once WOO_IMAGE_SEO['root_dir'] . 'lib/ajax-actions/send-feedback.php';
}, PHP_INT_MAX );

/*
 * Add settings page to the WooCommerce menu item
 */
add_action( 'admin_menu', function() {
    add_submenu_page(
        'woocommerce',
        'Woo Image SEO',
        'Woo Image SEO',
        'manage_options',
        'woo_image_seo',
        function() {
            require_once WOO_IMAGE_SEO['root_dir'] . '/views/settings.php';
        },
        PHP_INT_MAX
    );
}, PHP_INT_MAX );

/**
 * Enqueue admin scripts
 */
add_action( 'admin_enqueue_scripts', function() {
    if ( strpos( $_SERVER['REQUEST_URI'], 'admin.php?page=woo_image_seo' ) === false ) {
        return;
    }

    wp_enqueue_style(
        'woo-image-seo-settings-page',
        WOO_IMAGE_SEO['assets_url'] . 'style.css',
        [],
        WOO_IMAGE_SEO['version']
    );

    wp_enqueue_script(
        'woo-image-seo-settings-page',
        WOO_IMAGE_SEO['assets_url'] . 'settings.js',
        [],
        WOO_IMAGE_SEO['version']
    );

    // locale-specific css
    if ( woo_image_seo_i18n_has_key( 'css' ) ) {
        wp_enqueue_style(
            'woo-image-seo-i18n',
            WOO_IMAGE_SEO['root_url'] . 'i18n/assets/' . WOO_IMAGE_SEO['site_locale'] . '/css/admin.css',
            [],
            WOO_IMAGE_SEO['version']
        );
    }
}, PHP_INT_MAX );

/**
 * Prepare media library help text
 */
add_action( 'print_media_templates', function() {
    require_once WOO_IMAGE_SEO['views_dir'] . 'media-popup.php';
}, PHP_INT_MAX, 2 );

/**
 * Add link to settings page on the Plugins page
 */
add_filter( 'plugin_action_links_woo-image-seo/woo-image-seo.php', function( $links ) {
    return array_merge(
        [
            'settings' =>'<a href="' . admin_url() . 'admin.php?page=woo_image_seo">' . __( 'Settings', 'woo-image-seo' ) . '</a>'
        ],
        $links
    );
}, PHP_INT_MAX );
