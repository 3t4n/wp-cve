<?php
/*
	Plugin Name: Contact Form 7 Editor Button
	Plugin URI: https://wordpress.org/plugins/ari-cf7-editor-button/
	Description: Add custom button to TinyMCE WordPress editor to insert shortcodes for Contact Form 7 plugin.
	Version: 1.0.0
	Author: ARI Soft
	Author URI: http://www.ari-soft.com
	Text Domain: contact-form-7-editor-button
	Domain Path: /languages
	License: GPL2
 */

defined( 'ABSPATH' ) or die( 'Access forbidden!' );

define( 'ARICF7BUTTON_EXEC_FILE', __FILE__ );
define( 'ARICF7BUTTON_URL', plugin_dir_url( __FILE__ ) );
define( 'ARICF7BUTTON_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'ari_cf7_button_activation_check' ) ) {
    function ari_cf7_button_activation_check() {
        $min_php_version = '5.4.0';
        $min_wp_version = '4.0.0';

        $current_wp_version = get_bloginfo( 'version' );
        $current_php_version = PHP_VERSION;

        $is_supported_php_version = version_compare( $current_php_version, $min_php_version, '>=' );
        $is_spl_installed = function_exists( 'spl_autoload_register' );
        $is_supported_wp_version = version_compare( $current_wp_version, $min_wp_version, '>=' );

        if ( ! $is_supported_php_version || ! $is_spl_installed || ! $is_supported_wp_version ) {
            deactivate_plugins( basename( ARICF7BUTTON_EXEC_FILE ) );

            $recommendations = array();

            if ( ! $is_supported_php_version )
                $recommendations[] = sprintf(
                    __( 'update PHP version on your server from v. %s to at least v. %s', 'contact-form-7-editor-button' ),
                    $current_php_version,
                    $min_php_version
                );

            if ( ! $is_spl_installed )
                $recommendations[] = __( 'install PHP SPL extension', 'contact-form-7-editor-button' );

            if ( ! $is_supported_wp_version )
                $recommendations[] = sprintf(
                    __( 'update WordPress v. %s to at least v. %s', 'contact-form-7-editor-button' ),
                    $current_wp_version,
                    $min_wp_version
                );

            wp_die(
                sprintf(
                    __( '"Contact Form 7 Editor Button" can not be activated. It requires PHP version 5.4.0+ with SPL extension and WordPress 4.0+.<br /><br /><b>Recommendations:</b> %s.<br /><br /><a href="%s" class="button button-primary">Back</a>', 'contact-form-7-editor-button' ),
                    join( ', ', $recommendations ),
                    get_dashboard_url()
                )
            );
        } else {
            ari_cf7_button_init();
        }
    }
}

if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
    require_once ARICF7BUTTON_PATH . 'loader.php';

    add_action( 'plugins_loaded', 'ari_cf7_button_init' );
} else {
    if ( ! function_exists( 'ari_cf7_button_requirement_notice' ) ) {
        function ari_cf7_button_requirement_notice() {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                sprintf(
                    __( '"Contact Form 7 Editor Button" requires PHP v. 5.4.0+, but PHP version %s is used on the site.', 'contact-form-7-editor-button' ),
                    PHP_VERSION
                )
            );
        }
    }

    add_action( 'admin_notices', 'ari_cf7_button_requirement_notice' );
}

register_activation_hook( ARICF7BUTTON_EXEC_FILE, 'ari_cf7_button_activation_check' );
