<?php
/**
 * Register Menu for Plugin
 * 
 * @package Walker_Core
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Walker_Core_Menu' ) ) :
    class Walker_Core_Menu {
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'register_main_menus'),	9 );
                if ( wc_fs()->can_use_premium_code() ) {
                    $theme = wp_get_theme();
                    if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme || 'Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme  ):
                        add_action( 'admin_menu', array( $this, 'register_slider_submenu'),  9 );
                        if(!get_theme_mod('disable_walker_core_testimonial')){
                            add_action( 'admin_menu', array( $this, 'register_testimonial_submenu'), 10 );   
                        }
                        if(!get_theme_mod('disable_walker_core_team')){
                            add_action( 'admin_menu', array( $this, 'register_teams_submenu'), 11 );
                        }
                        
                        if(!get_theme_mod('disable_walker_core_brands')){
                            add_action( 'admin_menu', array( $this, 'register_brands_submenu'),  13 );
                        }
                    endif;
                    if ( 'Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme ):
                        if(!get_theme_mod('disable_walker_core_faq')){
                            add_action( 'admin_menu', array( $this, 'register_faqs_submenu'),  12 );
                        }
                    endif;

                    if('WalkerShop' == $theme->name || 'WalkerShop' == $theme->parent_theme ){
                         add_action( 'admin_menu', array( $this, 'register_slider_submenu'),  9 );
                         add_action( 'admin_menu', array( $this, 'register_testimonial_submenu'), 10 );  
                         add_action( 'admin_menu', array( $this, 'register_brands_submenu'),  13 );
                    }

                    if('MularX' == $theme->name || 'MularX' == $theme->parent_theme ){
                         add_action( 'admin_menu', array( $this, 'register_slider_submenu'),  9 );
                         add_action( 'admin_menu', array( $this, 'register_testimonial_submenu'), 10 );
                         add_action( 'admin_menu', array( $this, 'register_teams_submenu'), 11 );  
                         add_action( 'admin_menu', array( $this, 'register_brands_submenu'),  13 );

                    }
                }
        }
    
        public function register_main_menus() {
    
            add_menu_page( 'Walker Core', 'Walker Core', 'manage_options', 'walker-core', array( $this, 'walker_core_info' ), '','5' );
    
            add_submenu_page('walker-core',
                'Dashboard',
                __( 'Dashboard', 'walker-core' ),
                'manage_options',
                'walker-core');
    
        }
        

            public function register_testimonial_submenu() {
        
                add_submenu_page(
                    'walker-core',
                    'testimonials',
                    'Testimonials',
                    'manage_options',
                    'edit.php?post_type=wcr_testimonials'
                );
        
            }
            public function register_slider_submenu() {
        
                add_submenu_page(
                    'walker-core',
                    'slider',
                    'Slider',
                    'manage_options',
                    'edit.php?post_type=wcr_slider'
                );
        
            }
            public function register_teams_submenu() {
        
                add_submenu_page(
                    'walker-core',
                    'teams',
                    'Teams',
                    'manage_options',
                    'edit.php?post_type=wcr_teams'
                );
        
            }
            public function register_faqs_submenu() {
        
                add_submenu_page(
                    'walker-core',
                    'faqs',
                    'FAQs',
                    'manage_options',
                    'edit.php?post_type=wcr_faqs'
                );
        
            }
            public function register_brands_submenu() {
        
                add_submenu_page(
                    'walker-core',
                    'brands',
                    'Brands Carousel',
                    'manage_options',
                    'edit.php?post_type=wcr_brands'
                );
        
            }
        

        public function walker_core_info() {
            include_once('partials/dashboard.php');
            // echo 'Welcome to the Walker Core';
        }
        
    
       
    
    }
    
    $Walker_Plugin_Mneu = new Walker_Core_Menu;
    
    endif;
