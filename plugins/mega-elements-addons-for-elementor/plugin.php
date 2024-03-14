<?php

namespace MegaElementsAddonsForElementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'Mega_Elements_Addons_For_Elementor' ) ) {
    
    /*
    * Intialize and Sets up the plugin
    */
    class Mega_Elements_Addons_For_Elementor {
        
        /**
         * Member Variable
         *
         * @var instance
         */
        private static $instance = null;
        
        /**
         * Sets up needed actions/filters for the plug-in to initialize.
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function __construct() {
            
            add_action( 'plugins_loaded', array( $this, 'mega_elements_addons_for_elementor_setup' ) );
            add_action( 'elementor/init', array( $this, 'add_elementor_category' ) );
            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_enqueue_scripts' ), 10 );
            add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ), 10 );
            add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ), 10 );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
        }

        /**
         * Installs translation text domain and checks if Elementor is installed
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function mega_elements_addons_for_elementor_setup() {
            $this->meafe_get_started();
            $this->load_domain();
            $this->load_required_files();
        }

        /**
         * Load plugin translated strings using text domain
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function load_domain() {
            load_plugin_textdomain( 'mega-elements-addons-for-elementor' );            
        }

        /**
         * Getting started with elementor dependency
         *
         * @return void
         */
        public function meafe_get_started() {
            // Check for required PHP version
            if ( version_compare( PHP_VERSION, MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_PHP_VERSION, '<' ) ) {
                add_action( 'admin_notices', array( $this, 'required_php_version_missing_notice' ) );
                return;
            }

            // Check if Elementor installed and activated
            if ( ! did_action( 'elementor/loaded' ) ) {
                add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
                return;
            }

            // Check for required Elementor version
            if ( ! version_compare( ELEMENTOR_VERSION, MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
                add_action( 'admin_notices', array( $this, 'required_elementor_version_missing_notice' ) );
                return;
            }
        }

        /**
         * Admin notice for required php version
         *
         * @return void
         */
        public function required_php_version_missing_notice() {
            $notice = sprintf(
                /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mega-elements-addons-for-elementor' ),
                '<strong>' . esc_html__( 'Mega Elements - Addons for Elementor', 'mega-elements-addons-for-elementor' ) . '</strong>',
                '<strong>' . esc_html__( 'PHP', 'mega-elements-addons-for-elementor' ) . '</strong>',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_PHP_VERSION
            );

            printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
        }

        /**
         * Admin notice for elementor if missing
         *
         * @return void
         */
        public function elementor_missing_notice() {
            $notice = meafe_kses_intermediate( sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
                __( '%1$s requires %2$s to be installed and activated to function properly. %3$s', 'mega-elements-addons-for-elementor' ),
                '<strong>' . __( 'Mega Elements - Addons for Elementor', 'mega-elements-addons-for-elementor' ) . '</strong>',
                '<strong>' . __( 'Elementor', 'mega-elements-addons-for-elementor' ) . '</strong>',
                '<a href="' . esc_url( admin_url( 'plugin-install.php?s=Elementor&tab=search&type=term' ) ) . '">' . __( 'Please click on this link and install Elementor', 'mega-elements-addons-for-elementor' ) . '</a>'
            ) );

            printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
        }

        /**
         * Admin notice for required elementor version
         *
         * @return void
         */
        public function required_elementor_version_missing_notice() {
            $notice = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mega-elements-addons-for-elementor' ),
                '<strong>' . esc_html__( 'Mega Elements - Addons for Elementor', 'mega-elements-addons-for-elementor' ) . '</strong>',
                '<strong>' . esc_html__( 'Elementor', 'mega-elements-addons-for-elementor' ) . '</strong>',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION
            );

            printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice );
        }
        
        /**
         * Elementor Init
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return void
         */
        public function add_elementor_category() {
            require_once ( MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . 'includes/meafe-category.php' );          
        }

        /**
         * Enqueue CSS files
         * @since 1.0.0
         * @access public
         *
         */
        public function editor_enqueue_scripts() {
            
            wp_enqueue_style(
                'meafe-icon',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/admin/css/icon.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );
        }

        /**
         * Enqueue Js files
         * @since 1.0.0
         * @access public
         *
         */
        public function enqueue_scripts(){
            wp_enqueue_media();
        }

        /** 
        * Register CSS files
        * @since 1.0.0
        * @access public
        */
        public function register_styles() {
            
            wp_register_style(
                'meafe-about',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-about/about.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-accordion',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-accordion/accordion.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-blockquote',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-blockquote/blockquote.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-blog',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-blog/blog.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-post-modules',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-post-modules/post-modules.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-image-card',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-image-card/image-card.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-timeline',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-timeline/timeline.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-bten',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-bten/bten.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );
            
            wp_register_style(
                'meafe-button',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-button/button.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-category',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-category/category.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 
            
            wp_register_style(
                'meafe-cf7',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-cf7/cf7.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-checklist',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-checklist/checklist.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-clients',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-clients/clients.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-countdown',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-countdown/countdown.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-counter',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-counter/counter.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-cta',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-cta/cta.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 
            
            wp_register_style(
                'meafe-dualheading',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-dualheading/dualheading.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-featurelist',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-featurelist/featurelist.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-services',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-services/services.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-team',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-team/team.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-testimonial',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-testimonial/testimonial.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-testimonial-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-testimonial-carousel/testimonial-carousel.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-advanced-testimonial',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-advanced-testimonial/advanced-testimonial.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-post-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-post-carousel/post-carousel.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-pricing-table',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-pricing-table/pricing-table.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-team-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-team-carousel/team-carousel.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-price-menu',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-price-menu/price-menu.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-tabs',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-tabs/tabs.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            ); 

            wp_register_style(
                'meafe-events',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-events/events.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION, true
            );

            wp_register_style(
                'meafe-product-cat-tab',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-product-cat-tab/product-cat-tab.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-product-cat-grid',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-product-cat-grid/product-cat-grid.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );

            wp_register_style(
                'meafe-product-grid',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-product-grid/product-grid.css',
                array(),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
            );
        }
        
        /**
         * Register Js files
         * @since 1.0.0
         * @access public
         *
         */
        public function register_scripts(){
            wp_register_script(
                'meafe-accordion',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-accordion/accordion.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'jquery-countdown',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-countdown/jquery.countdown.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-countdown',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-countdown/countdown.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );
            
            wp_register_script(
                'meafe-clients',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-clients/clients.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-counter',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-counter/counter.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );
            
            wp_register_script(
                'meafe-testimonial-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-testimonial-carousel/testimonial-carousel.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-advanced-testimonial',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-advanced-testimonial/advanced-testimonial.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );
            
            wp_register_script(
                'meafe-post-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-post-carousel/post-carousel.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-timeline',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-timeline/timeline.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );
            
            wp_register_script(
                'tooltip',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/frontend/js/tooltip.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-pricing-table',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-pricing-table/pricing-table.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-team-carousel',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-team-carousel/team-carousel.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-tabs',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-tabs/tabs.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );


            wp_register_script(
                'meafe-product-cat-tab',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-product-cat-tab/product-tab.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_register_script(
                'meafe-product-grid',
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'includes/widgets/meafe-product-grid/product-grid.js',
                array( 'jquery' ),
                MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
                true
            );

            wp_localize_script(
                'meafe-product-cat-tab',
                'meafe_publicVars',
                [
                    // 'nonce' => wp_create_nonce( self::WIDGETS_NONCE ),
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    // 'action' => self::WIDGETS_NONCE,
                ]
            );

            wp_enqueue_script( 'masonry' );
        }

        /**
         * Load Required files
         *
         * @return void
         */
        public function load_required_files() {
            require_once ( MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . 'includes/meafe-helpers.php' );
            require_once MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . '/includes/classes/class-widgets-manager.php';
            require_once MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . '/includes/classes/class-dashboard.php';
        }

        /**
         * Creates and returns an instance of the class
         * 
         * @since 1.0.0
         * @access public
         * 
         * @return object
         */
        public static function get_instance() {
            if( self::$instance == null ) {
                self::$instance = new self;
            }
            return self::$instance;
        }    
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mega_elements_addons_for_elementor() {

	return Mega_Elements_Addons_For_Elementor::get_instance();
}
run_mega_elements_addons_for_elementor();
