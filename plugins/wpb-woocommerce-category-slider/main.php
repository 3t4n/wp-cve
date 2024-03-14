<?php

/**
 * Plugin Name:       WPB Product Categories Slider for WooCommerce
 * Plugin URI:        https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/
 * Description:       WPB Product Categories Slider for WooCommerce is highly customizable Category slider plugin for WooCommerce.
 * Version:           1.3
 * Author:            wpbean
 * Author URI:        https://wpbean.com/
 * Text Domain:       wpb-woocommerce-category-slider
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Define constant
 */

if ( ! defined( 'WPB_WCS_FREE_INIT' ) ) {
    define( 'WPB_WCS_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WPB_WCS_TEXTDOMAIN' ) ) {
    define( 'WPB_WCS_TEXTDOMAIN', 'wpb-woocommerce-category-slider' );
}

if ( ! defined( 'WPB_WCS_PLUGIN_DIR' ) ) {
    define( 'WPB_WCS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPB_WCS_PLUGIN_DIR_FILE' ) ) {
    define( 'WPB_WCS_PLUGIN_DIR_FILE', __FILE__ );
}


/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_WCS_PREMIUM' ) ) {
    function wpb_wcs_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Accordion Menu or Category while you are using the premium one.', WPB_WCS_TEXTDOMAIN ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'wpb_wcs_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}


/**
 * Add plugin action links
 */

if( !function_exists('wpb_wcs_plugin_actions_links') ){
	function wpb_wcs_plugin_actions_links( $links ) {
		$links[] = '<a href="https://wpbean.com/support/" target="_blank">'. esc_html__( 'Support', WPB_WCS_TEXTDOMAIN ) .'</a>';
		$links[] = '<a href="admin.php?page=wpb-woocommerce-category-slider-about">'. esc_html__( 'Settings', WPB_WCS_TEXTDOMAIN ) .'</a>';
		$links[] = '<a style="color: #27ae60; font-weight: bold" href="https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/" target="_blank">'. esc_html__( 'Upgrade to PRO!', WPB_WCS_TEXTDOMAIN ) .'</a>';

		return $links;
	}
}


/**
 * Plugin Init
 */

function wpb_wcs_free_plugin_init(){
	load_plugin_textdomain( WPB_WCS_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wcs_plugin_actions_links' );

	register_deactivation_hook( plugin_basename( __FILE__ ), 'wpb_wcs_lite_plugin_deactivation' );
	add_action( 'admin_notices', 'wpb_wcs_pro_discount_admin_notice' );
	add_action( 'admin_init', 'wpb_wcs_pro_discount_admin_notice_dismissed' );
	add_action( 'wp_dashboard_setup', 'wpb_wcs_add_dashboard_widgets' );

	require_once dirname( __FILE__ ) . '/inc/wpb-wcs-functions.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wcs-shortcode.php';
	require_once dirname( __FILE__ ) . '/admin/settings/class.settings-api.php';
	require_once dirname( __FILE__ ) . '/admin/settings/wpb-wcs-settings-config.php';
	require_once dirname( __FILE__ ) . '/admin/taxonomie-meta.php';
}
add_action( 'plugins_loaded', 'wpb_wcs_free_plugin_init' );