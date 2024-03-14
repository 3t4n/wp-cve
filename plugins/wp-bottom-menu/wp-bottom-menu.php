<?php
/**
 * Plugin Name: WP Bottom Menu
 * Description: WP Bottom Menu allows you to add a woocommerce supported bottom menu to your site.
 * Version: 2.2.3
 * Author: J4 & LiquidThemes
 * Author URI: https://hub.liquid-themes.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-bottom-menu
 * Domain Path: /languages
 * 
 * WP Bottom Menu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * WP Bottom Menu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WP_BOTTOM_MENU_VERSION', '2.2.3' );
define( 'WP_BOTTOM_MENU_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_BOTTOM_MENU_DIR_PATH', plugin_dir_path( __FILE__ ) );


final class WPBottomMenu{

    /**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var WPBottomMenu The single instance of the class.
	 */
	private static $_instance = null;

    /**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return WPBottomMenu An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    /**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
    public function __construct() {
        $this->init();
    }

    /**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'wp-bottom-menu' );

	}

    /**
	 * Initialize the plugin
	 *
	 * Load the files required to run the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
    function init(){

        $this->i18n();
        $this->include_files();

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_footer', array($this, 'wp_bottom_menu' ) );
        add_filter( 'plugin_action_links', array($this, 'wp_bottom_menu_action_links'), 10, 2 );

        // Admin menu - Settings > WP Bottom Menu
        add_action( 'admin_menu', function() {
            add_submenu_page(
                'options-general.php', 'WP Bottom Menu', 'WP Bottom Menu', 'manage_options', 'wp-bottom-menu',
                function(){
                    wp_redirect( admin_url( 'customize.php?autofocus[panel]=wpbottommenu_panel' ) );
                }
            );
		} );        

        add_action( 'customize_controls_enqueue_scripts', function(){

            // customizer js
            wp_enqueue_script( 'wp-bottom-menu-customize', WP_BOTTOM_MENU_DIR_URL . 'assets/js/customizer.js', array( 'jquery', 'customize-controls' ), false, true );
            
            // select2
            wp_enqueue_script( 'select2', WP_BOTTOM_MENU_DIR_URL . 'assets/vendors/select2/select2.min.js', [], false, false );
            wp_enqueue_style( 'select2', WP_BOTTOM_MENU_DIR_URL . 'assets/vendors/select2/select2.min.css', array(), WP_BOTTOM_MENU_VERSION, 'all' );
            wp_add_inline_script( 'wp-bottom-menu-customize', '
                jQuery( document ).ready(function() {
                    jQuery(".wp-bottom-menu-select2").select2({
                        placeholder: "Select an option"
                      });
                });
            ');
        } );

        // Load WooCommerce Fragments
        if ( class_exists( 'WooCommerce' ) ){
            add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'wp_bottom_menu_add_to_cart_fragment'), 10, 1 );
        }

        // Register Nav Menus
        add_action( 'after_setup_theme', function() {
            register_nav_menus( array(
                'wpbm_custom' => __( 'WP Bottom Menu', 'wp-bottom-menu' ),
            ) );
        } );

        // Polylang register translatable strings
        add_action('init', function(){
            if ( function_exists('pll_register_string') ) {
                $customizer_repeater_wpbm = get_option('customizer_repeater_wpbm', json_encode( array(
                    array("choice" => "wpbm-homepage" ,"subtitle" => "fa-home", "title" => "Home", "id" => "customizer_repeater_1" ),
                    array("choice" => "wpbm-woo-account" ,"subtitle" => "fa-user", "title" => "Account", "id" => "customizer_repeater_2" ),
                    array("choice" => "wpbm-woo-cart" ,"subtitle" => "fa-shopping-cart", "title" => "Cart", "id" => "customizer_repeater_3" ),
                    array("choice" => "wpbm-woo-search" ,"subtitle" => "fa-search", "title" => "Search", "id" => "customizer_repeater_4" ),
                ) ) );
        
                $customizer_repeater_wpbm_decoded = json_decode($customizer_repeater_wpbm);

                foreach ( $customizer_repeater_wpbm_decoded as $repeater_item ) {
                    pll_register_string( 'Menu Item', $repeater_item->title, 'WP Bottom Menu' );
                }
            }
        });

    } 
    
    function include_files(){

        require_once( WP_BOTTOM_MENU_DIR_PATH . 'inc/customizer/customizer-repeater/functions.php' );
        require_once( WP_BOTTOM_MENU_DIR_PATH . 'inc/customizer/customizer.php' );
        require_once( WP_BOTTOM_MENU_DIR_PATH . 'inc/customizer/condition.php' );

    }

    // enqueue scripts
    function enqueue_scripts() {

        // check the display condition
        if ( $this->display_condition() ){
          
            wp_enqueue_style( 'wp-bottom-menu', WP_BOTTOM_MENU_DIR_URL . 'assets/css/style.css', array(), WP_BOTTOM_MENU_VERSION, 'all' );
            wp_enqueue_script( 'wp-bottom-menu', WP_BOTTOM_MENU_DIR_URL . 'assets/js/main.js', array(), WP_BOTTOM_MENU_VERSION, true );
            
            if ( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome' ){
                wp_enqueue_style( 'font-awesome', WP_BOTTOM_MENU_DIR_URL . 'inc/customizer/customizer-repeater/css/font-awesome.min.css', array(), CUSTOMIZER_REPEATER_VERSION );
            }
            if ( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome2' ){
                wp_enqueue_style( 'font-awesome-wpbm', WP_BOTTOM_MENU_DIR_URL . 'assets/vendors/fontawesome/all.min.css', array(), '6.1.1' );
            }

            wp_localize_script( 'wp-bottom-menu', 'WPBM',
                array( 
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'siteurl' => site_url(),
                )
            );
            
        }
            
    }

    // wp bottom menu
    function wp_bottom_menu() {

        // check the display condition
        if ( !$this->display_condition() ){
            return;
        }

        ?>
        <div class="wp-bottom-menu" id="wp-bottom-menu">

        <?php
        $customizer_repeater_wpbm = get_option('customizer_repeater_wpbm', json_encode( array(
            array("choice" => "wpbm-homepage" ,"subtitle" => "fa-home", "title" => "Home", "id" => "customizer_repeater_1" ),
            array("choice" => "wpbm-woo-account" ,"subtitle" => "fa-user", "title" => "Account", "id" => "customizer_repeater_2" ),
            array("choice" => "wpbm-woo-cart" ,"subtitle" => "fa-shopping-cart", "title" => "Cart", "id" => "customizer_repeater_3" ),
            array("choice" => "wpbm-woo-search" ,"subtitle" => "fa-search", "title" => "Search", "id" => "customizer_repeater_4" ),
        ) ) );
        /*This returns a json so we have to decode it*/

        $customizer_repeater_wpbm_decoded = json_decode($customizer_repeater_wpbm);
        $wpbm_woo_search = $wpbm_post_search = $wpbm_custom_search = $wpbm_custom_menu = false;
        $search_icon = 'fa-search';
        $wpbm_link_target = get_option( 'wpbottommenu_target' ) ? 'target=_blank' : '';
        foreach ( $customizer_repeater_wpbm_decoded as $repeater_item ) {

            $tag = 'a';

            if ( $repeater_item->choice == "wpbm-woo-search" || $repeater_item->choice == "wpbm-post-search" || $repeater_item->choice == "wpbm-custom-search" ):
                $tag = 'div';
                if ( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome' ) {
                    $search_icon = 'fa ' . $repeater_item->subtitle;
                } elseif ( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome2' || get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'svg' ) {
                    $search_icon = $repeater_item->subtitle;
                }
            ?>
                <<?php echo $tag; ?> title="<?php echo esc_attr( $repeater_item->title ); ?>" class="wp-bottom-menu-item wp-bottom-menu-search-form-trigger">
            <?php elseif ( $repeater_item->choice == "wpbm-menu" ): ?>
                <?php $tag = 'div'; ?>
                <<?php echo $tag; ?> title="<?php echo esc_attr( $repeater_item->title ); ?>" class="wp-bottom-menu-item wp-bottom-menu-nav-trigger">
            <?php elseif ( $repeater_item->choice == "wpbm-onclick" ): ?>
                <?php $tag = 'div'; ?>
                <<?php echo $tag; ?> onclick="<?php echo $repeater_item->text; ?>" title="<?php echo esc_attr( $repeater_item->title ); ?>" class="wp-bottom-menu-item">
            <?php else: ?>
                <?php 
                    $wpbm_item_url = '';
                    $classes = array('wp-bottom-menu-item');
                    switch($repeater_item->choice){
                        case "wpbm-homepage":
                            $wpbm_item_url = esc_url( home_url() );
                            if ( is_front_page() ){
                                array_push( $classes, 'active' );
                            }
                        break;
                        case "wpbm-woo-cart":
                            if ( class_exists( 'WooCommerce' ) ) {
								$wpbm_item_url = esc_url( wc_get_page_permalink( 'cart' ) );
							} else {
								$wpbm_item_url = '#';
							}   
                        break;
                        case "wpbm-woo-account":
                            if ( class_exists( 'WooCommerce' ) ) {
								$wpbm_item_url = esc_url( wc_get_page_permalink( 'myaccount' ) );
							} else {
								$wpbm_item_url = '#';
							}  
                        break;
                        case "wpbm-page-back":
							$wpbm_item_url = 'javascript:void(0)';
                            array_push( $classes, 'wpbm-page-back' );
                        break;
                        default:
                            $wpbm_item_url = esc_url( $repeater_item->link );
                    }

                    if ( url_to_postid($wpbm_item_url) === get_the_ID() ){
                        array_push( $classes, 'active' );
                    } elseif ( class_exists('WooCommerce') && is_shop() && url_to_postid($wpbm_item_url) === 0 ) {
                        if ( $wpbm_item_url !== home_url() ) {
                            array_push( $classes, 'active' );
                        }
                    }
                    
                ?>
                <<?php echo $tag; ?> href="<?php echo $wpbm_item_url; ?>" class="<?php echo esc_attr( join( ' ', $classes ) ); ?>" <?php echo esc_attr( $wpbm_link_target ); ?>>
            <?php endif; ?>
                    
                    <div class="wp-bottom-menu-icon-wrapper">
                        <?php if( get_option( 'wpbottommenu_show_cart_count', false ) ): ?>
                            <?php if ( class_exists( 'WooCommerce' ) && $repeater_item->choice == "wpbm-woo-cart" ) : ?>
                                <div class="wp-bottom-menu-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome' ): ?>
                            <i class="wp-bottom-menu-item-icons fa <?php echo esc_attr( $repeater_item->subtitle ); ?>"></i>
                        <?php elseif( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'fontawesome2' ): ?>
                            <i class="wp-bottom-menu-item-icons <?php echo esc_attr( $repeater_item->subtitle ); ?>"></i>
                        <?php else: ?>
                        <?php echo html_entity_decode( $repeater_item->subtitle ); ?>
                        <?php endif; ?>
                    </div>
                    <?php if( !get_option( 'wpbottommenu_disable_title', false ) ): ?>
                        <?php if( get_option( 'wpbottommenu_show_cart_total', false ) && $repeater_item->choice == "wpbm-woo-cart" && class_exists( 'WooCommerce' ) ): ?>
                            <span class="wp-bottom-menu-cart-total"><?php WC()->cart->get_cart_total(); ?></span>
                        <?php elseif( get_option( 'wpbottommenu_show_account_name' ) && $repeater_item->choice == "wpbm-woo-account" && class_exists( 'WooCommerce' ) && is_user_logged_in() ): ?>
                            <?php echo wp_get_current_user()->first_name ? wp_get_current_user()->first_name : wp_get_current_user()->user_login; ?>
                        <?php else: ?>
                            <span><?php echo $this->translated_menu_title($repeater_item->title); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                </<?php echo $tag; ?>>
            <?php

            if ( $repeater_item->choice == "wpbm-woo-search" && !$wpbm_woo_search )
                $wpbm_woo_search = true;

            if ( $repeater_item->choice == "wpbm-post-search" && !$wpbm_post_search )
                $wpbm_post_search = true;

            if ( $repeater_item->choice == "wpbm-custom-search" && !$wpbm_custom_search )
                $wpbm_custom_search = true;

            if ( $repeater_item->choice == "wpbm-menu" && !$wpbm_custom_menu )
                $wpbm_custom_menu = true;

        }
        ?>
    </div>

    <?php if ( $wpbm_custom_menu ) : ?>
        <div class="wp-bottom-menu-nav-wrapper">
        <span class="wpbm-nav-close">&times;</span>
            <?php 
                if ( has_nav_menu( 'wpbm_custom' ) ) {
                    wp_nav_menu( array(
                        'menu'           => 'wpbm_custom',
                        'container'      => 'ul',
                        'menu_id'        => 'wpbm-nav',
                        'menu_class'     => 'wpbm-nav-items',
                    ) );
                } else {
                    esc_html_e( 'Add a menu in "WP Dashboard->Appearance->Menus" and select Display location "WP Bottom Menu"', 'wp-bottom-menu' );
                }
            ?>
        </div>
    <?php endif;
    
    if ( $wpbm_woo_search || $wpbm_post_search || $wpbm_custom_search ): ?>
        <div class="wp-bottom-menu-search-form-wrapper" id="wp-bottom-menu-search-form-wrapper">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>" class="wp-bottom-menu-search-form">
            <?php if ( get_option( 'wpbottommenu_iconset', 'fontawesome' ) == 'svg' ) : ?>
                <?php echo html_entity_decode( $search_icon ); ?>
            <?php else : ?>
                <i class="<?php echo esc_attr( $search_icon ); ?>"></i>
            <?php endif; ?>
            <?php if ( $wpbm_woo_search && class_exists( 'WooCommerce' ) ) { ?>
                <input type="hidden" name="post_type" value="product" />
            <?php } elseif ( $wpbm_post_search ) { ?>
                <input type="hidden" name="post_type" value="post" />
            <?php } else {
                $search_cpt = get_option( 'wpbottommenu_search_cpt', 'all' );
                if ( $search_cpt === 'all' || empty( $search_cpt ) ){
                    ?><input type="hidden" name="post_type" value="all" /><?php
                } else {
                    $search_cpt = explode(',', $search_cpt);
                    foreach( $search_cpt as $cpt ){
                        if ( !empty( $cpt ) ){
                            ?><input type="hidden" name="post_type[]" value="<?php echo esc_attr( $cpt ); ?>" /><?php
                        }
                    }
                }
            } ?>
            <input type="search" class="search-field" placeholder="<?php if( get_option( 'wpbottommenu_placeholder_text', 'Search' ) ) echo get_option( 'wpbottommenu_placeholder_text', 'Search' ); else echo esc_attr_x( 'Search', 'wp-bottom-menu' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        </form>
        </div>
    <?php endif;
        
    }

    function translated_menu_title( $title ) {
        if ( function_exists('pll__') ) {
            return pll__( $title );
        }

        return $title;
    }

    function display_condition(){

        $condition = new \WPBottomMenu_Condition();
        return $condition->get_condition();
        
    }

    // woocommerce cart fragment
    function wp_bottom_menu_add_to_cart_fragment( $fragments ) {
        $fragments['div.wp-bottom-menu-cart-count'] = '<div class="wp-bottom-menu-cart-count">' . WC()->cart->get_cart_contents_count() . '</div>'; 
        $fragments['span.wp-bottom-menu-cart-total'] = '<span class="wp-bottom-menu-cart-total">' . WC()->cart->get_cart_total() . '</span>';
        return $fragments;
    }

    // plugin action links
    function wp_bottom_menu_action_links( $links_array, $plugin_file_name ){
        if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
            array_unshift( $links_array, '<a href="' . admin_url( 'customize.php?autofocus[panel]=wpbottommenu_panel' ) . '">Settings</a>' );
        }
        return $links_array;
    }
    
} // class
WPBottomMenu::instance();


function wp_bottom_menu_plugin_activate() { 
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'wp_bottom_menu_plugin_activate' );

function wp_bottom_menu_plugin_deactivate() {
	/* If you want all settings to be deleted when the plugin is deactive, activate this field. 
    TODO : Add reset all settings option

    delete_option(' customizer_repeater_wpbm' );
    delete_option( 'wpbottommenu_display_px' );
    delete_option( 'wpbottommenu_display_always' );
    delete_option( 'wpbottommenu_fontsize' );
    delete_option( 'wpbottommenu_iconsize' );
    delete_option( 'wpbottommenu_textcolor' );
    delete_option( 'wpbottommenu_htextcolor' );
    delete_option( 'wpbottommenu_iconcolor' );
    delete_option( 'wpbottommenu_hiconcolor' );
    delete_option( 'wpbottommenu_bgcolor' );
    delete_option( 'wpbottommenu_zindex' );
    delete_option( 'wpbottommenu_disable_title' );
    delete_option( 'wpbottommenu_iconset' );
    delete_option( 'wpbottommenu_placeholder_text' );
    delete_option( 'wpbottommenu_show_cart_count' );
    delete_option( 'wpbottommenu_show_cart_total' );
    delete_option( 'wpbottommenu_cart_count_bgcolor' );
    delete_option( 'wpbottommenu_hide_pages' );
    delete_option( 'wpbottommenu_wrapper_padding' );
	*/
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wp_bottom_menu_plugin_deactivate' );