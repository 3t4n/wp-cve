<?php

/**
 * @link              https://wupoplugins.com
 * @since             1.0.0
 * @package           wugrat
 *
 * @wordpress-plugin
 * Plugin Name:       WUPO Group Attributes
 * Plugin URI:        https://wupoplugins.com/group-attributes-woocommerce/
 * Description:       Organize product attributes into groups. These will be shown in separate sections on your product pages. Increase readability for your product properties. The plugin works with your existing products and attributes. No need to adapt your products in the backend.
 * Version:           2.3.4
 * Author:            WUPO Plugins
 * Author URI:        https://wupoplugins.com
 * Package Name:      wugrat
 * WP.org Slug:       wupo-group-attributes
 * Text Domain:       wupo-group-attributes
 * Domain Path:       /languages
 * Requires at least: 5.0.0
 * Tested up to:      6.4.2
 * WC requires at least: 3.0.0
 * WC tested up to:   8.3.1
 *
 * Copyright: (c) 2022 WUPO Plugins - Premium WordPress and WooCommerce Extensions
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'wugrat_fs' ) ) {
    wugrat_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'wugrat_fs' ) ) {
        // Freemius integration snippet
        
        if ( !function_exists( 'wugrat_fs' ) ) {
            // Create a helper function for easy SDK access.
            function wugrat_fs()
            {
                global  $wugrat_fs ;
                
                if ( !isset( $wugrat_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $wugrat_fs = fs_dynamic_init( array(
                        'id'               => '9842',
                        'slug'             => 'wupo-group-attributes',
                        'premium_slug'     => 'wupo-group-attributes-pro',
                        'type'             => 'plugin',
                        'public_key'       => 'pk_b6a1ac3656345c226d528d44ded6c',
                        'is_premium'       => false,
                        'premium_suffix'   => 'Pro',
                        'is_org_compliant' => true,
                        'has_addons'       => false,
                        'has_paid_plans'   => true,
                        'has_affiliation'  => 'selected',
                        'navigation'       => 'tabs',
                        'menu'             => array(
                        'slug'        => 'wugrat_settings',
                        'first-path'  => 'options-general.php?page=wugrat_settings',
                        'parent'      => array(
                        'slug' => 'options-general.php',
                    ),
                        'contact'     => false,
                        'support'     => true,
                        'affiliation' => true,
                        'account'     => true,
                    ),
                        'is_live'          => true,
                    ) );
                }
                
                return $wugrat_fs;
            }
            
            // Init Freemius.
            $wugrat_fs = wugrat_fs();
            // Signal that SDK was initiated.
            do_action( 'wugrat_fs_loaded' );
            // Other Freemius init
            //			if ( function_exists( 'fs_override_i18n' ) ) {
            //				fs_override_i18n( array(
            //					'connect-message' => __( 'Never miss an important update - opt in to our security and feature updates notifications, and non-sensitive diagnostic tracking.' ),
            //					'connect-message_on-update' => __( 'Never miss an important update - opt in to our security and feature updates notifications, and non-sensitive diagnostic tracking.' ),
            //					'opt-out-message-clicking-opt-out' => __( 'By clicking "Opt Out", we will no longer be sending any data from %s to us' ),
            //					'license-sync-disclaimer' => __( 'The %1$s will be periodically sending data to check for security and feature updates, and verify the validity of your license.' ),
            //				), 'wupo-group-attributes' );
            //			}
            function my_fs_custom_icon()
            {
                return dirname( __FILE__ ) . '/public/img/icon-256x256.png';
            }
            
            $wugrat_fs->add_filter( 'plugin_icon', 'my_fs_custom_icon' );
            function my_premium_support_forum_url( $wp_org_support_forum_url )
            {
                return 'http://wupoplugins.supportbee.io';
            }
            
            if ( $wugrat_fs->is_premium() ) {
                $wugrat_fs->add_filter( 'support_forum_url', 'my_premium_support_forum_url' );
            }
            function my_support_forum_submenu_text_function( $menu_title )
            {
                $menu_title = 'Helpdesk';
                return $menu_title;
            }
            
            $wugrat_fs->add_filter(
                'support_forum_submenu',
                'my_support_forum_submenu_text_function',
                10,
                1
            );
        }
    
    }
    // Regular init code
    define( 'WUGRAT_NAME', 'wugrat' );
    define( 'WUGRAT_VERSION', '1.0.0' );
    if ( !defined( 'WUGRAT_BASENAME' ) ) {
        define( 'WUGRAT_BASENAME', plugin_basename( __FILE__ ) );
    }
    // Activate logging
    require_once plugin_dir_path( __FILE__ ) . 'includes/wupo-logger.php';
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-wugrat-pro-activator.php
     */
    function activate_wugrat()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wugrat-activator.php';
        Wugrat_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-wugrat-pro-deactivator.php
     */
    function deactivate_wugrat()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wugrat-deactivator.php';
        Wugrat_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'activate_wugrat' );
    register_deactivation_hook( __FILE__, 'deactivate_wugrat' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-wugrat.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     */
    function run_wugrat()
    {
        $plugin = new Wugrat();
        $plugin->run();
    }
    
    run_wugrat();
    function wugrat_fs_uninstall_cleanup()
    {
        delete_option( 'wugrat_group_order' );
        delete_option( 'wc_wugrat_settings_tab_general_enable_wugrat' );
        delete_option( 'wc_wugrat_settings_tab_general_position_single_attributes' );
        delete_option( 'wc_wugrat_settings_tab_general_single_attributes_label' );
        delete_option( 'wc_wugrat_settings_tab_general_position_dimension_attributes' );
        delete_option( 'wc_wugrat_settings_tab_general_dimension_attributes_label' );
        delete_option( 'wc_wugrat_settings_tab_styling_layout' );
        delete_option( 'wc_wugrat_settings_tab_styling_enable_customize_attribute_table_color' );
        delete_option( 'wc_wugrat_settings_tab_styling_text_color_odd_row' );
        delete_option( 'wc_wugrat_settings_tab_styling_background_color_odd_row' );
        delete_option( 'wc_wugrat_settings_tab_styling_text_color_even_row' );
        delete_option( 'wc_wugrat_settings_tab_styling_background_color_even_row' );
        // for site options in Multisite
        delete_site_option( 'wugrat_group_order' );
        delete_site_option( 'wc_wugrat_settings_tab_general_enable_wugrat' );
        delete_site_option( 'wc_wugrat_settings_tab_general_position_single_attributes' );
        delete_site_option( 'wc_wugrat_settings_tab_general_single_attributes_label' );
        delete_site_option( 'wc_wugrat_settings_tab_general_position_dimension_attributes' );
        delete_site_option( 'wc_wugrat_settings_tab_general_dimension_attributes_label' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_layout' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_enable_customize_attribute_table_color' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_text_color_odd_row' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_background_color_odd_row' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_text_color_even_row' );
        delete_site_option( 'wc_wugrat_settings_tab_styling_background_color_even_row' );
        // drop a custom database table
        global  $wpdb ;
        $result = $wpdb->query( "ALTER TABLE {$wpdb->term_taxonomy} DROP COLUMN children" );
        $result = $wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'wugrat_group'" );
        $result = $wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'wugrat_group_set'" );
    }
    
    wugrat_fs()->add_action( 'after_uninstall', 'wugrat_fs_uninstall_cleanup' );
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );
}
