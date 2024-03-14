<?php

/**
* Plugin Name: Advance Menu Manager
* Plugin URI:   https://www.thedotstore.com/advance-menu-manager-wordpress/
* Description:  Customize and manage your menu from here. Add, edit, and delete menu items.
* Author:       theDotstore
* Author URI:   https://www.thedotstore.com/
* License:      GPL-2.0+
* License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:  advance-menu-manager
* Version:      3.0.7
* License:      GNU General Public License v3.0
* License URI:  http://www.gnu.org/licenses/gpl-3.0.html
* 
* WP tested up to:     6.3.2
* Requires PHP:        7.2
* Requires at least:   5.0
*
* @author    theDotstore
* @category  Plugin
* @copyright Copyright (c) 2019-2020 theDotstore.
* @license
*/
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'ammp_fs' ) ) {
    ammp_fs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'ammp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ammp_fs()
    {
        global  $ammp_fs ;
        
        if ( !isset( $ammp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $ammp_fs = fs_dynamic_init( array(
                'id'              => '3496',
                'slug'            => 'advance-menu-manager',
                'type'            => 'plugin',
                'public_key'      => 'pk_20a3cb3184ddb17fc7c53bf40727d',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'            => array(
                'slug'       => 'advance-menu-manager',
                'first-path' => 'admin.php?page=advance-menu-manager-pro&tab=menu_advance_manager_get_started_method',
                'contact'    => false,
                'support'    => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $ammp_fs;
    }
    
    // Init Freemius.
    ammp_fs();
    // Signal that SDK was initiated.
    do_action( 'ammp_fs_loaded' );
    ammp_fs()->get_upgrade_url();
    ammp_fs()->add_action( 'after_uninstall', 'ammp_fs_uninstall_cleanup' );
}

/**
 * prevent direct access data leaks
 *
 * This is the condition to prevent direct access data leaks.
 *
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}

if ( !defined( 'DSAMM_PLUGIN_NAME' ) ) {
    define( 'DSAMM_PLUGIN_NAME', 'Advance Menu Manager' );
}
if ( !defined( 'DSAMM_PLUGIN_SLUG' ) ) {
    define( 'DSAMM_PLUGIN_SLUG', 'advance-menu-manager' );
}
if ( !defined( 'DSAMM_PLUGIN_VERSION_TYPE' ) ) {
    define( 'DSAMM_PLUGIN_VERSION_TYPE', esc_html__( 'Free Version', 'advance-menu-manager' ) );
}
if ( !defined( 'DSAMM_PLUGIN_TITLE_NAME' ) ) {
    define( 'DSAMM_PLUGIN_TITLE_NAME', 'Advance Menu Manager' );
}
if ( !defined( 'DSAMM_PRO_PLUGIN_URL' ) ) {
    define( 'DSAMM_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'DSAMM_PLUGIN_BASENAME' ) ) {
    define( 'DSAMM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'DSAMM_PLUGINPRO_VERSION' ) ) {
    define( 'DSAMM_PLUGINPRO_VERSION', '3.0.7' );
}
if ( !defined( 'DSAMM_PRO_PLUGIN_BASENAME' ) ) {
    define( 'DSAMM_PRO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
define( 'DSAMM_PLUGIN_BASE_PATH', plugin_dir_url( __FILE__ ) . "/images/" );
define( 'DSAMM_PLUGIN_PATH', plugin_dir_url( __FILE__ ) . "includes/" );
define( 'DSAMM_PLUGIN_FILE', plugin_basename( __FILE__ ) );
/**
 * 
 * Hook fire on activation of plugin
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_activate_pro' ) ) {
    register_activation_hook( __FILE__, 'dsamm_activate_pro' );
    function dsamm_activate_pro()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class_activator.php';
        set_transient( '_welcome_screen_activation_redirect_data', true, 30 );
    }

}

/**
 * 
 * Hook for add links on plugin listing 
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_plugin_action_links' ) ) {
    $prefix = ( is_network_admin() ? 'network_admin_' : '' );
    function dsamm_plugin_action_links( $actions )
    {
        $custom_actions = array(
            'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
            'page' => 'advance-menu-manager-pro&tab=menu-manager-add&section=menu-add',
        ), admin_url( 'admin.php' ) ) ), __( 'Settings', 'advance-menu-manager' ) ),
            'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'www.thedotstore.com/support' ), __( 'Support', 'advance-menu-manager' ) ),
        );
        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }
    
    add_filter(
        "{$prefix}plugin_action_links_" . DSAMM_PRO_PLUGIN_BASENAME,
        'dsamm_plugin_action_links',
        10,
        4
    );
}

/**
 * 
 * This function will run for register text domain for translation compatible
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_load_plugin_textdomain' ) ) {
    function dsamm_load_plugin_textdomain()
    {
        load_plugin_textdomain( 'advance-menu-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    
    add_action( 'plugins_loaded', 'dsamm_load_plugin_textdomain' );
}

/**
 * 
 * This function will run for enqueue the styles and scripts for plugin only in admin.
 *
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_add_scripts_styles_admin' ) ) {
    function dsamm_add_scripts_styles_admin()
    {
        $current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( !empty($current_page) && isset( $current_page ) && $current_page === 'advance-menu-manager-pro' ) {
            wp_enqueue_style( 'dsamm_style_fancy', plugin_dir_url( __FILE__ ) . 'includes/admin/css/fancy_alert.css' );
            wp_enqueue_style( 'dsamm_style_fancy' );
            wp_register_script(
                'dsamm_fancy_alert',
                plugin_dir_url( __FILE__ ) . '/includes/js/fancy_alert.js',
                array( 'jquery' ),
                false
            );
            wp_enqueue_script( 'dsamm_fancy_alert' );
            wp_register_script(
                'dsamm_pagination',
                plugin_dir_url( __FILE__ ) . '/includes/js/dsamm_pagination.js',
                array( 'jquery' ),
                false
            );
            wp_enqueue_script( 'dsamm_pagination' );
            wp_enqueue_script(
                'custom-js-own',
                plugin_dir_url( __FILE__ ) . 'includes/js/custom.js',
                array( 'jquery' ),
                false
            );
            wp_register_style(
                'own-style-css',
                plugin_dir_url( __FILE__ ) . 'includes/admin/css/style.css',
                array( 'wp-jquery-ui-dialog' ),
                'all'
            );
            wp_register_style(
                'own-media-style',
                plugin_dir_url( __FILE__ ) . 'includes/admin/css/media.css',
                array(),
                false,
                'all'
            );
            wp_enqueue_script(
                'custom-js-dsamm',
                plugin_dir_url( __FILE__ ) . 'includes/js/dsamm_custom.js',
                array( 'jquery', 'jquery-ui-dialog' ),
                false
            );
            $params = array(
                'ajaxurl'    => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce( 'dsamm_ajax_value_nonce' ),
            );
            wp_localize_script( 'custom-js-own', 'ajax_object', $params );
            $params = array(
                'ajaxurl'    => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce( 'dsamm_ajax_value_nonce' ),
            );
            wp_localize_script( 'dsamm_pagination', 'ajax_object', $params );
        }
        
        wp_enqueue_style( 'amm_style_notice', plugin_dir_url( __FILE__ ) . 'includes/admin/css/notice.css' );
        wp_enqueue_style( 'amm_style_notice' );
    }
    
    add_action( 'admin_enqueue_scripts', 'dsamm_add_scripts_styles_admin' );
}

/**
 * Welcome screen activation redirect
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_welcome_pro_screen_do_activation_redirect' ) ) {
    function dsamm_welcome_pro_screen_do_activation_redirect()
    {
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'advance-menu-manager-pro&tab=menu_advance_manager_get_started_method',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    add_action( 'admin_init', 'dsamm_welcome_pro_screen_do_activation_redirect' );
}

/**
 * spl_autoload_register function
 *
 * This function will run admin panel loades.
 *
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_pro_autoloader' ) ) {
    function dsamm_pro_autoloader( $name )
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class_admin_page.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class_admin_menu_walker.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class_menu_ajax_action.php';
    }
    
    spl_autoload_register( 'dsamm_pro_autoloader' );
}

/**
 * 
 * Admin menu for plugin
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_menu_advance_menu_manager_pro' ) ) {
    function dsamm_menu_advance_menu_manager_pro()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                __( 'DotStore Plugins', 'advance-menu-manager' ),
                'DotStore Plugins',
                'NULL',
                'dots_store',
                'dot_store_advance_menu',
                plugin_dir_url( __FILE__ ) . 'images/menu-icon.png',
                6
            );
        }
        add_submenu_page(
            "dots_store",
            "Advance Menu Manager",
            "Advance Menu Manager",
            "manage_options",
            "advance-menu-manager-pro",
            "dsamm_advance_submenu_extra_pro"
        );
    }
    
    add_action( 'admin_menu', 'dsamm_menu_advance_menu_manager_pro' );
}

/**
 * Callback function of sub menu function
 *  
 * @version     3.0.0
 * @author      theDotstore
 * 
 */
if ( !function_exists( 'dsamm_advance_submenu_extra_pro' ) ) {
    function dsamm_advance_submenu_extra_pro()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/admin/header/plugin-header.php';
        wp_enqueue_style( 'own-webkit-style' );
        wp_enqueue_style( 'own-style-css' );
        wp_enqueue_style( 'own-media-style' );
        $tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $tab_exist = ( isset( $tab ) && !empty($tab) ? $tab : '' );
        
        if ( $tab_exist ) {
            
            if ( $tab_exist === "menu-manager-add" ) {
                ?>
                <div class="amm-main-table res-cl adv-menu-manager-main left-container">
                    <!-- Menu content start -->
                    <?php 
                require_once dirname( __FILE__ ) . '/includes/admin/admin.php';
                ?>
                    <!-- Menu content end -->
                </div>
                <?php 
            }
            
            if ( $tab_exist === 'dotstore_introduction_menu_advance_manager' ) {
                require_once plugin_dir_path( __FILE__ ) . 'includes/admin/amm-pro-information-page.php';
            }
            if ( $tab_exist === 'menu_advance_manager_get_started_method' ) {
                require_once plugin_dir_path( __FILE__ ) . 'includes/admin/amm-pro-get-started-page.php';
            }
            if ( $tab_exist === "menu_advance_manager_premium_method" ) {
                require_once plugin_dir_path( __FILE__ ) . 'includes/admin/premium_method.php';
            }
        }
        
        require_once plugin_dir_path( __FILE__ ) . 'includes/admin/header/plugin-sidebar.php';
    }

}
/**
 * 
 * Ajax actions loads
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */
add_action( 'wp_ajax_my_action_delete_menu', array( 'DSAMM_Admin_Interface', 'dsamm_action_ajax_for_delete_menu' ) );
add_action( 'wp_ajax_my_action_create_menu_ajax', array( 'DSAMM_Admin_Interface', 'dsamm_action_ajax_for_create_menu' ) );
add_action( 'wp_ajax_amm_duplicate_menu', array( 'DSAMM_Admin_Interface', 'dsamm_amm_duplicate_menu' ) );
// menu edit ajax action
add_action( 'wp_ajax_my_action_for_popup_menu_item_edit', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_menu_edit_action_method_own' ) );
// add new post/page
add_action( 'wp_ajax_my_action_for_popup_add_new_post', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_add_new_post_action_method_own' ) );
// search texonomy terms
add_action( 'wp_ajax_my_action_for_amm_taxonomy_search', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_my_action_for_amm_taxonomy_search' ) );
//edit menu item as client on menu item
add_action( 'wp_ajax_my_action_for_popup_menu_item_edit_front_end', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_popup_menu_item_edit_frontend' ) );
add_action( 'wp_ajax_my_action_for_main_popup_fontend_menu_item_edit_submit', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_main_popup_fontend_menu_item_edit_submit_action_own' ) );
// popup content
add_action( 'wp_ajax_my_action_for_add_new_menu_item_html', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_add_new_menu_item_html_own' ) );
add_action( 'wp_ajax_my_action_for_add_new_menu_item_html_filter', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_add_new_menu_item_html_filter_own' ) );
// Pagination post per page feature
add_action( 'wp_ajax_my_action_for_add_pagination_limit', array( 'DSAMM_Revision_Ajax_Action', 'dsamm_add_pagination_post_per_page_limit_method' ) );
/**
 * 
 * AMM Menu shortcode
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_menu_show_callback' ) ) {
    function dsamm_menu_show_callback( $amm_atts )
    {
        ob_start();
        
        if ( !empty($amm_atts['menu_name']) && 'your_menu_name' !== $amm_atts['menu_name'] ) {
            $menu_class = ( !empty($amm_atts['menu_class']) ? $amm_atts['menu_class'] : '' );
            $container = ( !empty($amm_atts['container']) ? $amm_atts['container'] : '' );
            $container_id = ( !empty($amm_atts['container_id']) ? $amm_atts['container_id'] : '' );
            $container_class = ( !empty($amm_atts['container_class']) ? $amm_atts['container_class'] : '' );
            echo  wp_nav_menu( array(
                'menu'            => $amm_atts['menu_name'],
                'menu_class'      => $menu_class,
                'container'       => $container,
                'container_id'    => $container_id,
                'container_class' => $container_class,
            ) ) ;
            return ob_get_clean();
        }
    
    }
    
    if ( !is_admin() ) {
        add_shortcode( 'amm_menu', 'dsamm_menu_show_callback' );
    }
}

/**
 * 
 * If tab not set then redirect to menu main page 
 * 
 * @version     3.0.0
 * @author      theDotstore
 * 
 */

if ( !function_exists( 'dsamm_admin_without_tab_redirect' ) ) {
    function dsamm_admin_without_tab_redirect()
    {
        $tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        
        if ( !empty($page) && empty($tab) && 'advance-menu-manager-pro' === $page ) {
            $url = site_url( 'wp-admin/admin.php?page=advance-menu-manager-pro&tab=menu-manager-add&section=menu-add' );
            wp_safe_redirect( $url, 301 );
            exit;
        }
    
    }
    
    add_action( 'wp_loaded', 'dsamm_admin_without_tab_redirect' );
}

/**
 * Filter data
 *
 * @param string $string
 *
 * @since 3.0.7
 *
 */
function dsamm_filter_sanitize_string( $string )
{
    $str = preg_replace( '/\\x00|<[^>]*>?/', '', $string );
    return str_replace( [ "'", '"' ], [ '&#39;', '&#34;' ], $str );
}
