<?php

/**
 * Plugin Name: WP School Calendar
 * Plugin URI: https://sorsawo.com/en/wordpress-school-calendar/
 * Description: Helps you build amazing school calendar for your WordPress site.
 * Author: Sorsawo Digital
 * Author URI: https://sorsawo.com/en/
 * Version: 3.8.2
 * Text Domain: wp-school-calendar
 * Domain Path: languages
 * 
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wpsc_fs' ) ) {
    wpsc_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wpsc_fs' ) ) {
        function wpsc_fs()
        {
            global  $wpsc_fs ;
            
            if ( !isset( $wpsc_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                $wpsc_fs = fs_dynamic_init( array(
                    'id'             => '5764',
                    'slug'           => 'wp-school-calendar-lite',
                    'premium_slug'   => 'wp-school-calendar-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_62a91c6b07d4c7e1d3f9a83d9f23b',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'menu'           => array(
                    'slug'       => 'edit.php?post_type=school_calendar',
                    'first-path' => 'edit.php?post_type=school_calendar&page=wpsc-getting-started',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wpsc_fs;
        }
        
        // Init Freemius.
        wpsc_fs();
        // Signal that SDK was initiated.
        do_action( 'wpsc_fs_loaded' );
    }
    
    class WP_School_Calendar
    {
        private static  $_instance = NULL ;
        /**
         * Initialize all variables, filters and actions
         */
        public function __construct()
        {
            // Define plugin file path
            if ( !defined( 'WPSC_PLUGIN_FILE' ) ) {
                define( 'WPSC_PLUGIN_FILE', __FILE__ );
            }
            // Plugin version
            if ( !defined( 'WPSC_PLUGIN_VERSION' ) ) {
                define( 'WPSC_PLUGIN_VERSION', '3.8.2' );
            }
            // File base name.
            if ( !defined( 'WPSC_PLUGIN_BASENAME' ) ) {
                define( 'WPSC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
            }
            // Plugin Folder Path.
            if ( !defined( 'WPSC_PLUGIN_DIR' ) ) {
                define( 'WPSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }
            // Plugin Folder URL.
            if ( !defined( 'WPSC_PLUGIN_URL' ) ) {
                define( 'WPSC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }
            require_once WPSC_PLUGIN_DIR . 'includes/functions.php';
            require_once WPSC_PLUGIN_DIR . 'includes/post-type.php';
            require_once WPSC_PLUGIN_DIR . 'includes/widget.php';
            
            if ( is_admin() ) {
                require_once WPSC_PLUGIN_DIR . 'includes/admin/functions.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/meta-boxes.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/categories.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/builder.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/settings.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/tools.php';
                require_once WPSC_PLUGIN_DIR . 'includes/admin/getting-started.php';
            }
            
            add_action( 'init', array( $this, 'load_plugin_textdomain' ), 0 );
            add_action( 'init', array( $this, 'init' ), 1 );
            add_action( 'template_redirect', array( $this, 'render_custom_style' ), 0 );
            add_action( 'wp_loaded', array( $this, 'register_scripts' ) );
            add_action( 'admin_init', array( $this, 'silent_upgrade_db' ), 20 );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 120 );
            add_filter( 'query_vars', array( $this, 'custom_style_vars' ) );
            // Shortcode
            add_shortcode( 'wp_school_calendar', array( $this, 'add_shortcode' ) );
            // Gutenberg Block
            add_action( 'init', array( $this, 'register_gutenberg_block' ) );
            add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
            
            if ( file_exists( WPSC_PLUGIN_DIR . 'pro/wp-school-calendar-pro.php' ) && wpsc_fs()->can_use_premium_code() ) {
                require_once WPSC_PLUGIN_DIR . 'pro/wp-school-calendar-pro.php';
            } elseif ( file_exists( WPSC_PLUGIN_DIR . 'lite/wp-school-calendar-lite.php' ) ) {
                require_once WPSC_PLUGIN_DIR . 'lite/wp-school-calendar-lite.php';
            }
        
        }
        
        /**
         * retrieve singleton class instance
         * @return instance reference to plugin
         */
        public static function instance()
        {
            if ( NULL === self::$_instance ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        /**
         * Initialize custom stylesheet
         * 
         * @since 1.0
         * 
         * @global object $wp
         */
        public function init()
        {
            global  $wp ;
            
            if ( 'Y' === wpsc_settings_value( 'external_color_style' ) ) {
                $wp->add_query_var( 'wpsc-custom-style' );
                add_rewrite_rule( 'wpsc-custom-style\\.css$', 'index.php?wpsc-custom-style=1', 'top' );
            }
        
        }
        
        /**
         * Add 'wpsc-custom-style' to query vars
         * 
         * @since 1.0
         * 
         * @param array $vars Original query vars
         * @return array Modified query vars
         */
        public function custom_style_vars( $vars )
        {
            if ( 'Y' === wpsc_settings_value( 'external_color_style' ) ) {
                $vars[] = 'wpsc-custom-style';
            }
            return $vars;
        }
        
        /**
         * Render custom stylesheet
         * 
         * @since 1.0
         */
        public function render_custom_style()
        {
            if ( 'N' === wpsc_settings_value( 'external_color_style' ) ) {
                return;
            }
            
            if ( get_query_var( 'wpsc-custom-style' ) === '1' ) {
                header( 'Content-Type: text/css; charset: UTF-8' );
                echo  wpsc_get_important_date_single_color() ;
                exit;
            }
        
        }
        
        /**
         * Load Localisation files.
         * 
         * @since 3.0
         * 
         * Locales found in:
         *  - WP_LANG_DIR/wp-school-calendar/wp-school-calendar-LOCALE.mo
         *  - WP_LANG_DIR/plugins/wp-school-calendar-LOCALE.mo
         */
        public function load_plugin_textdomain()
        {
            $locale = ( is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale() );
            $locale = apply_filters( 'plugin_locale', $locale, 'wp-school-calendar' );
            unload_textdomain( 'wp-school-calendar' );
            load_textdomain( 'wp-school-calendar', WP_LANG_DIR . '/wp-school-calendar/wp-school-calendar-' . $locale . '.mo' );
            load_plugin_textdomain( 'wp-school-calendar', false, plugin_basename( dirname( WPSC_PLUGIN_FILE ) ) . '/languages' );
        }
        
        /**
         * Process upgrade database
         * 
         * @since 1.0
         */
        public function silent_upgrade_db()
        {
            if ( wp_doing_ajax() ) {
                return;
            }
            if ( get_option( 'wpsc_options', array() ) === array() ) {
                wpsc_create_initial_options();
            }
            $wpsc_version = get_option( 'wpsc_version', '' );
            
            if ( version_compare( $wpsc_version, WPSC_PLUGIN_VERSION, '<' ) || '' === $wpsc_version ) {
                wpsc_upgrade_32();
                wpsc_upgrade_34();
                wpsc_upgrade_36();
                wpsc_upgrade_37();
                wpsc_upgrade_371();
                wpsc_upgrade_38();
                wpsc_upgrade_381();
                update_option( 'wpsc_version', WPSC_PLUGIN_VERSION );
            }
        
        }
        
        /**
         * Get admin script arguments
         * 
         * @since 1.0
         * 
         * @return array Admin script arguments
         */
        public static function admin_script_args()
        {
            return apply_filters( 'wpsc_admin_script_args', array(
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'nonce'            => wp_create_nonce( 'wpsc_admin' ),
                'loading'          => __( 'Loading...', 'wp-school-calendar' ),
                'datepickerButton' => __( 'Choose', 'wp-school-calendar' ),
                'warnDelete'       => __( 'Are you sure want to delete this item?', 'wp-school-calendar' ),
            ) );
        }
        
        /**
         * Load admin stylesheet and script
         * 
         * @since 1.0
         */
        public function admin_enqueue_scripts()
        {
            
            if ( function_exists( 'get_current_screen' ) ) {
                $screen = get_current_screen();
                
                if ( isset( $screen->post_type ) && 'school_calendar' === $screen->post_type && isset( $screen->base ) ) {
                    
                    if ( 'school_calendar_page_wpsc-settings' === $screen->base ) {
                        wp_enqueue_script( 'wpsc-settings' );
                        wp_localize_script( 'wpsc-settings', 'WPSC_Admin', self::admin_script_args() );
                        wp_enqueue_style( 'wpsc-settings' );
                    } elseif ( 'school_calendar_page_wpsc-tools' === $screen->base ) {
                        wp_enqueue_script( 'wpsc-tools-import' );
                        wp_localize_script( 'wpsc-tools-import', 'WPSC_Admin', self::admin_script_args() );
                    } elseif ( 'school_calendar_page_wpsc-category' === $screen->base ) {
                        wp_enqueue_script( 'wpsc-category-editor' );
                        wp_localize_script( 'wpsc-category-editor', 'WPSC_Admin', self::admin_script_args() );
                        wp_enqueue_style( 'wpsc-category-editor' );
                    } elseif ( 'school_calendar_page_wpsc-builder' === $screen->base ) {
                        wp_enqueue_media();
                        wp_enqueue_script( 'wpsc-builder' );
                        $start_years = array(
                            '01',
                            '02',
                            '03',
                            '04',
                            '05',
                            '06',
                            '07',
                            '08',
                            '09',
                            '10',
                            '11',
                            '12'
                        );
                        $num_months = array(
                            'twelve',
                            'six',
                            'four',
                            'three',
                            'one'
                        );
                        $custom_default_month_range_options = array();
                        foreach ( $num_months as $num_month ) {
                            foreach ( $start_years as $start_year ) {
                                $tmp_custom_default_month_range_options = wpsc_get_custom_default_month_range_options( $start_year, $num_month );
                                $custom_default_month_range_option = array();
                                foreach ( $tmp_custom_default_month_range_options as $key => $value ) {
                                    $custom_default_month_range_option[] = array(
                                        'key'   => $key,
                                        'value' => $value,
                                    );
                                }
                                $custom_default_month_range_options[] = array(
                                    'num_month'   => $num_month,
                                    'start_year'  => $start_year,
                                    'month_range' => $custom_default_month_range_option,
                                );
                            }
                        }
                        $tmp_custom_default_year_single = wpsc_get_custom_default_year_options( '01' );
                        $custom_default_year_single = array();
                        foreach ( $tmp_custom_default_year_single as $key => $value ) {
                            $custom_default_year_single[] = array(
                                'key'   => $key,
                                'value' => $value,
                            );
                        }
                        $tmp_custom_default_year_dual = wpsc_get_custom_default_year_options( '02' );
                        $custom_default_year_dual = array();
                        foreach ( $tmp_custom_default_year_dual as $key => $value ) {
                            $custom_default_year_dual[] = array(
                                'key'   => $key,
                                'value' => $value,
                            );
                        }
                        wp_localize_script( 'wpsc-builder', 'WPSC_Admin', array_merge( self::admin_script_args( $screen ), array(
                            'custom_default_year_single' => $custom_default_year_single,
                            'custom_default_year_dual'   => $custom_default_year_dual,
                            'custom_default_month_range' => $custom_default_month_range_options,
                        ) ) );
                        wp_enqueue_style( 'wpsc-builder' );
                        wp_enqueue_style( 'wpsc-frontend' );
                        wp_add_inline_style( 'wpsc-frontend', wpsc_get_important_date_single_color() );
                    } elseif ( 'school_calendar_page_wpsc-getting-started' === $screen->base ) {
                        wp_enqueue_style( 'wpsc-getting-started' );
                    }
                
                } elseif ( isset( $screen->post_type ) && 'important_date' === $screen->post_type && isset( $screen->base ) ) {
                    
                    if ( 'edit' === $screen->base ) {
                        wp_enqueue_style( 'wpsc-important-date-list' );
                    } elseif ( 'post' === $screen->base ) {
                        wp_enqueue_script( 'wpsc-important-date-metabox' );
                        wp_localize_script( 'wpsc-important-date-metabox', 'WPSC_Admin', self::admin_script_args() );
                        wp_enqueue_style( 'wpsc-important-date-metabox' );
                    }
                
                } elseif ( isset( $screen->base ) && 'widgets' === $screen->base ) {
                }
            
            }
        
        }
        
        public function register_scripts()
        {
            $gutenberg_block = ( is_wp_version_compatible( '6.2' ) ? 'block.js' : 'deprecated.js' );
            wp_register_script(
                'magnific-popup',
                WPSC_PLUGIN_URL . 'assets/js/jquery.magnific-popup.min.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_register_script(
                'jquery-select2',
                WPSC_PLUGIN_URL . 'assets/js/select2.full.min.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-important-date-metabox',
                WPSC_PLUGIN_URL . 'assets/js/important-date-metabox.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-settings',
                WPSC_PLUGIN_URL . 'assets/js/settings.js',
                array( 'jquery', 'jquery-select2' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-tools-import',
                WPSC_PLUGIN_URL . 'assets/js/import.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-category-editor',
                WPSC_PLUGIN_URL . 'assets/js/category-editor.js',
                array(
                'jquery',
                'wp-color-picker',
                'jquery-ui-core',
                'jquery-ui-sortable'
            ),
                false,
                true
            );
            wp_register_script(
                'wpsc-calendar-list',
                WPSC_PLUGIN_URL . 'assets/js/calendar-list.js',
                array( 'jquery' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-builder',
                WPSC_PLUGIN_URL . 'assets/js/builder.js',
                array( 'jquery', 'magnific-popup', 'jquery-select2' ),
                false,
                true
            );
            wp_register_script(
                'wpsc-gutenberg-block',
                WPSC_PLUGIN_URL . 'assets/js/' . $gutenberg_block,
                array(
                'wp-blocks',
                'wp-i18n',
                'wp-element',
                'wp-editor',
                'wp-components'
            ),
                false,
                true
            );
            wp_register_style( 'magnific-popup', WPSC_PLUGIN_URL . 'assets/css/magnific-popup.css' );
            wp_register_style( 'jquery-ui', WPSC_PLUGIN_URL . 'assets/css/jquery-ui.css' );
            wp_register_style( 'datepicker', WPSC_PLUGIN_URL . 'assets/css/datepicker.css' );
            wp_register_style( 'jquery-select2', WPSC_PLUGIN_URL . 'assets/css/select2.min.css' );
            wp_register_style( 'wpsc-important-date-metabox', WPSC_PLUGIN_URL . 'assets/css/important-date-metabox.css', array( 'jquery-ui', 'datepicker' ) );
            wp_register_style( 'wpsc-getting-started', WPSC_PLUGIN_URL . 'assets/css/getting-started.css', array() );
            wp_register_style( 'wpsc-settings', WPSC_PLUGIN_URL . 'assets/css/settings.css', array( 'jquery-select2' ) );
            wp_register_style( 'wpsc-category-editor', WPSC_PLUGIN_URL . 'assets/css/category-editor.css', array() );
            wp_register_style( 'wpsc-builder', WPSC_PLUGIN_URL . 'assets/css/builder.css', array( 'magnific-popup', 'jquery-select2' ) );
            wp_register_style( 'wpsc-frontend', WPSC_PLUGIN_URL . 'assets/css/frontend.css' );
            wp_register_style( 'wpsc-widget', WPSC_PLUGIN_URL . 'assets/css/widget.css' );
            if ( 'Y' === wpsc_settings_value( 'external_color_style' ) ) {
                wp_register_style( 'wpsc-custom-style', home_url( '/wpsc-custom-style.css' ), array() );
            }
        }
        
        /**
         * Load frontend stylesheet dan scripts
         * 
         * @since 1.0
         * 
         * @global array $post object
         */
        public function enqueue_styles()
        {
            wp_enqueue_style( 'wpsc-widget' );
            $css_location_type = wpsc_settings_value( 'css_location_type' );
            $css_location_posts = wpsc_settings_value( 'css_location_posts' );
            
            if ( 'single' === $css_location_type && !empty($css_location_posts) ) {
                
                if ( is_singular() || is_front_page() && 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) > 0 ) {
                    
                    if ( is_front_page() ) {
                        $post_id = get_option( 'page_on_front' );
                    } else {
                        $post_id = get_the_ID();
                    }
                    
                    
                    if ( in_array( $post_id, $css_location_posts ) ) {
                        wp_enqueue_style( 'wpsc-frontend' );
                        do_action( 'wpsc_enqueue_styles' );
                    }
                
                }
            
            } else {
                wp_enqueue_style( 'wpsc-frontend' );
                do_action( 'wpsc_enqueue_styles' );
            }
            
            
            if ( 'Y' === wpsc_settings_value( 'external_color_style' ) ) {
                wp_enqueue_style( 'wpsc-custom-style' );
            } else {
                wp_add_inline_style( 'wpsc-frontend', wpsc_get_important_date_single_color() );
            }
        
        }
        
        public function add_shortcode( $attributes )
        {
            do_action( 'wpsc_enqueue_scripts' );
            if ( empty($attributes['id']) ) {
                return sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar">%s</div>', __( 'Please insert calendar ID on your shortcode.', 'wp-school-calendar' ) );
            }
            $calendar = wpsc_get_calendar( intval( $attributes['id'] ) );
            if ( !$calendar ) {
                return sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar">%s</div>', __( 'Sorry, the calendar ID is invalid.', 'wp-school-calendar' ) );
            }
            $output = sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar">%s</div>', wpsc_render_calendar(
                $calendar,
                '',
                '',
                true
            ) );
            return $output;
        }
        
        public function register_gutenberg_block()
        {
            global  $pagenow ;
            if ( 'widgets.php' === $pagenow ) {
                return;
            }
            if ( !function_exists( 'register_block_type' ) ) {
                // Gutenberg is not active
                return;
            }
            register_block_type( 'wp-school-calendar/wp-school-calendar', apply_filters( 'wpsc_register_block_type_args', array(
                'attributes'      => array(
                'id' => array(
                'type' => 'string',
            ),
            ),
                'render_callback' => array( $this, 'render_gutenberg_block' ),
            ) ) );
        }
        
        public function render_gutenberg_block( $attributes )
        {
            if ( empty($attributes['id']) ) {
                return sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar" style="background:#f2ab39;border:1px solid #69491a;padding:20px 30px;">%s</div>', __( 'Please select the calendar from the block setting.', 'wp-school-calendar' ) );
            }
            $calendar = wpsc_get_calendar( intval( $attributes['id'] ) );
            if ( !$calendar ) {
                return sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar">%s</div>', __( 'Sorry, the calendar ID is invalid.', 'wp-school-calendar' ) );
            }
            do_action( 'wpsc_enqueue_scripts' );
            $output = sprintf( '<div id="wpsc-block-calendar" class="wpsc-block-calendar">%s</div>', wpsc_render_calendar(
                $calendar,
                '',
                '',
                true
            ) );
            return $output;
        }
        
        public function editor_assets()
        {
            global  $pagenow ;
            if ( 'widgets.php' === $pagenow ) {
                return;
            }
            wp_enqueue_script( 'wpsc-gutenberg-block' );
            wp_localize_script( 'wpsc-gutenberg-block', 'WPSC_Block', array(
                'calendars' => wpsc_options_for_gutenberg_block(),
            ) );
            wp_enqueue_style( 'wpsc-frontend' );
            
            if ( 'Y' === wpsc_settings_value( 'external_color_style' ) ) {
                wp_enqueue_style( 'wpsc-custom-style' );
            } else {
                wp_add_inline_style( 'wpsc-frontend', wpsc_get_important_date_single_color() );
            }
        
        }
    
    }
    WP_School_Calendar::instance();
}
