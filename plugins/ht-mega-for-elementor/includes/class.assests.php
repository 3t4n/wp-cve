<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

if ( !class_exists( 'HTMega_Elementor_Addons_Assests' ) ) {

    class HTMega_Elementor_Addons_Assests{

        /**
         * [$_instance]
         * @var null
         */
        private static $_instance = null;

        /**
         * [instance] Initializes a singleton instance
         * @return [HTMega_Elementor_Addons_Assests]
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * [__construct] Class construcotr
         */
        public function __construct(){

            // Register Scripts
            add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

            // Elementor Editor Style
            add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        }

        /**
         * All available styles
         *
         * @return array
         */
        public function get_styles() {

            $style_list = [

                'htbbootstrap' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htbbootstrap.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-widgets' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-widgets.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-animation' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/animation.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-weather' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-weather.css',
                    'version' => HTMEGA_VERSION
                ],
                'regular-weather-icon' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/weather-icons.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'slick' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/slick.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'magnific-popup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/magnific-popup.css',
                    'version' => HTMEGA_VERSION
                ],
                'ytplayer' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/jquery.mb.YTPlayer.min.css',
                    'version' => HTMEGA_VERSION
                ],
                'swiper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/swiper.min.css',
                    'version' => '8.4.5'
                ],
                'compare-image' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/compare-image.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'justify-gallery' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/justify-gallery.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'datatables' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/datatables.min.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'magnifier' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/magnifier.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'animated-heading' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/animated-text.css',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-widgets' ]
                ],
                'htmega-keyframes' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-keyframes.css',
                    'version' => HTMEGA_VERSION
                ],

                'htmega-admin' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/css/htmega_admin.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-selectric' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/css/selectric.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-temlibray-style' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/css/tmp-style.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-rpbar-css' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'extensions/reading-progress-bar/assets/css/htmega-reading-progress-bar.css',
                    'version' => HTMEGA_VERSION
                ],
                'htmega-stt-css' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'extensions/scroll-to-top/assets/css/htmega-scroll-to-top.css',
                    'version' => HTMEGA_VERSION
                ]
            ];

            return apply_filters( 'htmega_style_list', $style_list );

        }

        /**
         * All available scripts
         *
         * @return array
         */
        public function get_scripts(){

            $google_map_api_key = htmega_get_option( 'google_map_api_key','htmega_general_tabs' );

            $script_list = [

                'htbbootstrap' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/htbbootstrap.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-popper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/popper.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-widgets-scripts' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/htmega-widgets-active.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'slick' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/slick.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'magnific-popup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.magnific-popup.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'beerslider' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-beerslider-min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'ytplayer' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mb.YTPlayer.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'mapmarker' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/mapmarker.jquery.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-easing' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.easing.1.3.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-mousewheel' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.mousewheel.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'vaccordion' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.vaccordion.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'easy-pie-chart' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-easy-pie-chart.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-countdown' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-countdown.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-newsticker' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery-newsticker-min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-goodshare' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/goodshare.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-notify' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/notify.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'counterup' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/jquery.counterup.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'isotope' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/isotope.pkgd.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'swiper' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/swiper.min.js',
                    'version' => '8.4.5',
                    'deps'    => [ 'jquery' ]
                ],
                'justified-gallery' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/justified-gallery.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'datatables' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/datatables.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'magnifier' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/magnifier.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'animated-heading' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/animated-heading.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'waypoints' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/waypoints.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'google-map-api' => [
                    'src'     => 'http://maps.googleapis.com/maps/api/js?sensor=false',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],

                'htmega-admin' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/admin.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-modernizr' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/modernizr.custom.63321.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-selectric' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/jquery.selectric.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'jquery-ScrollMagic' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/ScrollMagic.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'babel-min' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/lib/js/babel.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-templates' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/template_library_manager.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-install-manager' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'admin/assets/js/install_manager.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'htmega-templates', 'wp-util', 'updates' ]
                ],
                'htmega-rpbar-script' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'extensions/reading-progress-bar/assets/js/htmega-reading-progress-bar.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'htmega-stt-script' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'extensions/scroll-to-top/assets/js/htmega-scroll-to-top.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
                'anime' => [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'extensions/floating-effects/assets/js/anime.min.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ],
            ];

            if( !empty( $google_map_api_key ) ){
                $script_list['google-map-api'] = [
                    'src'     => 'https://maps.googleapis.com/maps/api/js?key='.$google_map_api_key,
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ];
            }

            if ( is_plugin_active('woocommerce/woocommerce.php') && htmega_get_option( 'wcaddtocart', 'htmega_thirdparty_element_tabs', 'on' ) === 'on' && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
                $script_list['htmega-single-product-ajax-cart'] = [
                    'src'     => HTMEGA_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
                    'version' => HTMEGA_VERSION,
                    'deps'    => [ 'jquery' ]
                ];
            }

            return apply_filters( 'htmega_script_list', $script_list );

        }

        /**
         * Register scripts and styles
         *
         * @return void
         */
        public function register_assets() {
            $scripts = $this->get_scripts();
            $styles  = $this->get_styles();

            $localize_data_frontend = [];
            $localize_data_admin = [];

            if( is_plugin_active('elementor-pro/elementor-pro.php') ){
                $localize_data_frontend['elementorpro'] = true;
            }else{
                wp_deregister_script( 'swiper' );
                $localize_data_frontend['elementorpro'] = false;
            }
            // string for carousel next/ preve area button
            $localize_data_frontend['buttion_area_text_next'] = __( 'Next', 'htmega-addons');
            $localize_data_frontend['buttion_area_text_prev'] = __( 'Previous', 'htmega-addons');
            
            // Register Scripts
            foreach ( $scripts as $handle => $script ) {
                $deps = ( isset( $script['deps'] ) ? $script['deps'] : false );
                wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
            }

            // Register Styles
            foreach ( $styles as $handle => $style ) {
                $deps = ( isset( $style['deps'] ) ? $style['deps'] : false );
                wp_register_style( $handle, $style['src'], $deps, $style['version'] );
            }

            // Localize Scripts for frontend
            wp_localize_script( 'htmega-widgets-scripts', 'HTMEGAF', $localize_data_frontend );
            if( is_plugin_active('htmega-pro/htmega_pro.php') ){
                wp_localize_script( 'htmega-pro-slick-active', 'HTMEGAF', $localize_data_frontend );
            }
            // admin js ajax request nonce
            $localize_data_admin['admin_ajax_nonce'] = wp_create_nonce( "htmega-admin-ajax-request" );

            wp_localize_script( 'htmega-admin', 'HTMEGAA', $localize_data_admin );

            // Localize Scripts for template manager
            $current_user  = wp_get_current_user();
            $localize_data = [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'adminURL'         => admin_url(),
                'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
                'version'          => HTMEGA_VERSION,
                'pluginURL'        => plugin_dir_url( __FILE__ ),
                'alldata'          => !empty( HTMega_Addons_Elementor::$template_info['templates'] ) ? HTMega_Addons_Elementor::$template_info['templates'] : array(),
                'prolink'          => isset( HTMega_Addons_Elementor::$template_info['pro_link'] ) ? HTMega_Addons_Elementor::$template_info['pro_link'] : '#',

                'prolabel'         => esc_html__( 'Pro', 'htmega-addons' ),
                'loadingimg'       => HTMEGA_ADDONS_PL_URL . 'admin/assets/images/loading.gif',
                'message'          =>[
                    'packagedesc'=> esc_html__( 'in this package', 'htmega-addons' ),
                    'allload'    => esc_html__( 'All Items have been Loaded', 'htmega-addons' ),
                    'notfound'   => esc_html__( 'Nothing Found', 'htmega-addons' ),
                ],
                'buttontxt'      =>[
                    'tmplibrary' => esc_html__( 'Import to Library', 'htmega-addons' ),
                    'tmppage'    => esc_html__( 'Import to Page', 'htmega-addons' ),
                    'import'     => esc_html__( 'Import', 'htmega-addons' ),
                    'buynow'     => esc_html__( 'Buy Now', 'htmega-addons' ),
                    'preview'    => esc_html__( 'Preview', 'htmega-addons' ),
                    'installing' => esc_html__( 'Installing..', 'htmega-addons' ),
                    'activating' => esc_html__( 'Activating..', 'htmega-addons' ),
                    'active'     => esc_html__( 'Active', 'htmega-addons' ),
                ],
                'user'           => [
                    'email' => $current_user->user_email,
                ],
                'plgactivenonce'   => wp_create_nonce( 'htmega_actication_verifynonce' ),
            ];
            wp_localize_script( 'htmega-templates', 'HTTM', $localize_data );

            // Reading progress bar global functionality
            if( is_plugin_active('htmega-pro/htmega_pro.php') ) {

                $htmega_rpbar_module_settings = htmega_get_option( 'htmega_rpbar', 'htmega_rpbar_module_settings' );
                $htmega_rpbar_module_settings = json_decode( $htmega_rpbar_module_settings,true );

                if( $htmega_rpbar_module_settings && ('on' == $htmega_rpbar_module_settings['rpbar_enable']  && ( isset( $htmega_rpbar_module_settings['rpbar_global'] ) && 'on' == $htmega_rpbar_module_settings['rpbar_global'] ) ) ) {

                    $rpbar_select_to_show_pages = isset( $htmega_rpbar_module_settings['rpbar_select_to_show_pages'] ) ? $htmega_rpbar_module_settings['rpbar_select_to_show_pages'] : 'all';

                    if( 'all' == $rpbar_select_to_show_pages && ( is_single() || is_page() ) ) {  

                        wp_enqueue_script( 'htmega-rpbar-script');
                        wp_enqueue_style( 'htmega-rpbar-css');

                    } else if( 'pages' == $rpbar_select_to_show_pages && is_page() ) {

                        wp_enqueue_script( 'htmega-rpbar-script');
                        wp_enqueue_style( 'htmega-rpbar-css');
                        
                    } else if( 'posts' == $rpbar_select_to_show_pages && is_single() ) {
                        
                        wp_enqueue_script( 'htmega-rpbar-script');
                        wp_enqueue_style( 'htmega-rpbar-css');
                    }

                    $rpbar_localize_data = [
                        'bg_color'       => isset( $htmega_rpbar_module_settings['rpbar_background_color']) ? $htmega_rpbar_module_settings['rpbar_background_color'] : 'transparent',
                        'fill_color'     => isset( $htmega_rpbar_module_settings['rpbar_fill_color']) ? $htmega_rpbar_module_settings['rpbar_fill_color'] : '#fill_color',
                        'loading_height' => isset( $htmega_rpbar_module_settings['rpbar_loading_height']) ? $htmega_rpbar_module_settings['rpbar_loading_height'] : 5,
                        'position'       => isset( $htmega_rpbar_module_settings['rpbar_position']) ? $htmega_rpbar_module_settings['rpbar_position'] : 'top',
                    ];
    
                    wp_localize_script( 'htmega-rpbar-script', 'rpbar', $rpbar_localize_data );
                }
            }

            // Scroll To Top global functionality
            if( is_plugin_active('htmega-pro/htmega_pro.php') ) {

                $htmega_stt_module_settings = htmega_get_option( 'htmega_stt', 'htmega_stt_module_settings' );
                $htmega_stt_module_settings = json_decode( $htmega_stt_module_settings,true );

                if( $htmega_stt_module_settings && ('on' == $htmega_stt_module_settings['stt_enable']  && ( isset( $htmega_stt_module_settings['stt_global'] ) && 'on' == $htmega_stt_module_settings['stt_global'] ) ) ) {

                    $stt_select_to_show_pages = isset( $htmega_stt_module_settings['stt_select_to_show_pages'] ) ? $htmega_stt_module_settings['stt_select_to_show_pages'] : 'all';

                    if( 'all' == $stt_select_to_show_pages && ( is_single() || is_page() ) ) {  

                        wp_enqueue_script( 'htmega-stt-script');
                        wp_enqueue_style( 'htmega-stt-css');

                    } else if( 'pages' == $stt_select_to_show_pages && is_page() ) {

                        wp_enqueue_script( 'htmega-stt-script');
                        wp_enqueue_style( 'htmega-stt-css');
                        
                    } else if( 'posts' == $stt_select_to_show_pages && is_single() ) {
                        
                        wp_enqueue_script( 'htmega-stt-script');
                        wp_enqueue_style( 'htmega-stt-css');
                    }

                    $stt_localize_data = [
                        'stt_bg_color'       => isset( $htmega_stt_module_settings['stt_bg_color']) ? $htmega_stt_module_settings['stt_bg_color'] : '#000000',
                        'stt_color'          => isset( $htmega_stt_module_settings['stt_color']) ? $htmega_stt_module_settings['stt_color'] : '#ffffff',
                        'stt_bg_color_hover' => isset( $htmega_stt_module_settings['stt_bg_color_hover']) ? $htmega_stt_module_settings['stt_bg_color_hover'] : '#000000',
                        'stt_color_hover'    => isset( $htmega_stt_module_settings['stt_color_hover']) ? $htmega_stt_module_settings['stt_color_hover'] : '#ffffff',
                        'position'           => isset( $htmega_stt_module_settings['stt_position']) ? $htmega_stt_module_settings['stt_position'] : 'right',
                        'stt_bottom_space'   => isset( $htmega_stt_module_settings['stt_bottom_space']) ? $htmega_stt_module_settings['stt_bottom_space'] : 30,
                    ];
    
                    wp_localize_script( 'htmega-stt-script', 'stt', $stt_localize_data );
                }
            }
            // localize  woocommerce  add to card button action 
            if ( is_plugin_active('woocommerce/woocommerce.php') && htmega_get_option( 'wcaddtocart', 'htmega_thirdparty_element_tabs', 'on' ) === 'on' && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
                $localize_data_woocommerce = [];
                $localize_data_woocommerce['woocommerce_ajax_nonce'] = wp_create_nonce( "htmega-woocommerce-ajax-request" );
                wp_localize_script( 'htmega-single-product-ajax-cart', 'HTMEGAW', $localize_data_woocommerce );
            }
        }


        /**
         * [editor_scripts]
         * @return [void] Load Editor Scripts
         */
        public function editor_scripts() {
            wp_enqueue_style('htmega-element-editor', HTMEGA_ADDONS_PL_URL . 'assets/css/htmega-elementor-editor.css',['elementor-editor'], HTMEGA_VERSION );
            wp_enqueue_script("htmega-widgets-editor", HTMEGA_ADDONS_PL_URL ."/assets/js/htmega-widgets-editor.js", array( "elementor-editor","jquery" ), HTMEGA_VERSION,true);
            
            //Localized  promotional widget for editor js
            wp_localize_script(
                'htmega-widgets-editor',
                'htmegaPanelSettings',
                array(
                    'htmega_pro_installed' => is_plugin_active('htmega-pro/htmega_pro.php') ? true : false,
                    'htmega_pro_widgets'   => $this->get_promotional_widget_list(),
                )
            );
        }

        /**
         * [enqueue_scripts]
         * @return [void] Frontend Scripts
         */
        public function enqueue_scripts(){

            // CSS
            wp_enqueue_style( 'htbbootstrap' );
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'htmega-animation' );
            wp_enqueue_style( 'htmega-keyframes' );

            // JS
            wp_enqueue_script( 'htmega-popper' );
            wp_enqueue_script( 'htbbootstrap' );
            wp_enqueue_script( 'waypoints' ); 

        }
        /**
         * get_promotional_widget_list function
         *
         * @return promotional_widgets list
         */
       public function get_promotional_widget_list() {
        
        $promotional_widgets = array(
            array(
				'key'       => 'htmega-info-box-addons',
				'title'      => __( 'Info Box', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-info',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-info-box-widget/'),
			),
            array(
				'key'       => 'htmega-advanced-slider-addons',
				'title'      => __( 'Advanced Slider', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-post-slider',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-advanced-slider-widget/'),
			),
            array(
				'key'       => 'htmega-background-switcher',
				'title'      => __( 'Background Switcher', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-exchange',
				'action_url' => esc_url('https://wphtmega.com/elementor-background-switcher-widget/'),
			),
            array(
				'key'        => 'htmega-breadcrumbs',
				'title'      => __( 'Breadcrumbs', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-exchange',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-breadcrumbs-widget/'),
			),
            array(
				'key'        => 'htmega-category-list-addons',
				'title'      => __( 'Category List', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-bullet-list',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-category-list-widget/'),
			),
            array(
				'key'       => 'htmega-chart-addons',
				'title'      => __( 'Chart', 'htmega-addons' ),
				'icon'       => 'htmega-icon htmega-chart-img',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-chart-widget/'),
			),
            array(
				'key'       => 'htmega-dynamic-gallery-addons',
				'title'      => __( 'Dynamic Gallery', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-gallery-justified',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-dynamic-gallery-widget/'),
			),
            array(
				'key'       => 'htmega-event-box-addons',
				'title'      => __( 'Event Box', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-table-of-contents',
				'action_url' => esc_url('https://wphtmega.com/elementor-event-box-widget/'),
			),
            array(
				'key'       => 'htmega-event-calendar-addons',
				'title'      => __( 'Event Calendar', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-calendar',
				'action_url' => esc_url('https://wphtmega.com/elementor-event-calendar-widget/'),
			),
            array(
				'key'       => 'htmega-facebook-review-addons',
				'title'      => __( 'Facebook Review', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-facebook',
				'action_url' => esc_url('https://wphtmega.com/elementor-facebook-review-widget/'),
			),
            array(
				'key'       => 'htmega-feature-list-addons',
				'title'      => __( 'Feature List', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-post-list',
				'action_url' => esc_url('https://wphtmega.com/elementor-feature-list-widget/'),
			),
            array(
				'key'       => 'htmega-filterable-gallery-addons',
				'title'      => __( 'Filterable Gallery', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-gallery-justified',
				'action_url' => esc_url('https://wphtmega.com/elementor-filterable-gallery-widget/'),
			),
            array(
				'key'       => 'htmega-flip-switcher-pricing-table-addons',
				'title'      => __( 'Flip Switcher Pricing Table', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-dual-button',
				'action_url' => esc_url('https://wphtmega.com/elementor-pricing-table-flip-box-widget/'),
			),
            array(
				'key'       => 'htmega-icon-box-addons',
				'title'      => __( 'Icon Box', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-icon-box',
				'action_url' => esc_url('https://wphtmega.com/elementor-icon-box-widget/'),
			),
            array(
				'key'       => 'htmega-image-roted-addons',
				'title'      => __( 'Image Rotate', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-image-before-after',
				'action_url' => esc_url('https://wphtmega.com/'),
			),
            array(
				'key'       => 'htmega-interactive-promo-addons',
				'title'      => __( 'Interactive Promo', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-call-to-action',
				'action_url' => esc_url('https://wphtmega.com/elementor-interactive-promo-widget/'),
			),
            array(
				'key'       => 'htmega-lottie-addons',
				'title'      => __( 'Lottie', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-lottie',
				'action_url' => esc_url('https://wphtmega.com/elementor-lottie-widget/'),
			),
            array(
				'key'       => 'htmega-page-list-addons',
				'title'      => __( 'Page List', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-bullet-list',
				'action_url' => esc_url('https://wphtmega.com/elementor-page-list-widget/'),
			),
            array(
				'key'       => 'htmega-post-masonry-addons',
				'title'      => __( 'Post Masonry', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-posts-masonry',
				'action_url' => esc_url('https://wphtmega.com/elementor-post-masonry-widget/'),
			),
            array(
				'key'       => 'htmega-post-timeline-addons',
				'title'      => __( 'Post Timeline', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-time-line',
				'action_url' => esc_url('https://wphtmega.com/elementor-post-timeline-widget/'),
			),
            array(
				'key'       => 'htmega-pricing-menu-addons',
				'title'      => __( 'Pricing Menu', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-info-box',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-price-menu-widget/'),
			),
            array(
				'key'       => 'htmega-pricing-table-flip-box',
				'title'      => __( 'Pricing Table Flip Box', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-flip-box',
				'action_url' => esc_url('https://wphtmega.com/elementor-pricing-table-flip-box-widget/'),
			),
            array(
				'key'       => 'htmega-social-network-icons-addons',
				'title'      => __( 'Social Network Icons', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-social-icons',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-social-network-widget/'),
			),
            array(
				'key'       => 'htmega-source-code-addons',
				'title'      => __( 'Source Code', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-code',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-source-code-widget/'),
			),
            array(
				'key'       => 'htmega-sticky-section-addons',
				'title'      => __( 'Sticky Section', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-filter',
				'action_url' => esc_url('https://wphtmega.com/'),
			),
            array(
				'key'       => 'htmega-taxonomy-terms-addons',
				'title'      => __( 'Taxonomy Terms', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-radio',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-taxonomy-terms-widget/'),
			),
            array(
				'key'       => 'htmega-team-carousel-addons',
				'title'      => __( 'Team Carousel', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-person',
				'action_url' => esc_url('https://wphtmega.com/elementor-team-carousel-widget/'),
			),
            array(
				'key'       => 'htmega-threesixty-rotation-addons',
				'title'      => __( '360 Rotation', 'htmega-addons' ),
				'icon'       => 'htmega-icon htmega-threesixty-rotation-img',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-360-rotation-widget/'),
			),
            array(
				'key'       => 'htmega-whatsapp-chat-addons',
				'title'      => __( 'WhatsApp Chat', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-commenting-o',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-whatsapp-chat-widget/'),
			),
            array(
				'key'       => 'htmega-flip-carousel-addons',
				'title'      => __( 'Flip Carousel', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-media-carousel',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-flip-carousel-widget/'),
			),

            array(
				'key'       => 'htmega-interactive-circle-infographic-addons',
				'title'      => __( 'Interactive Circle Infographic', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-integration',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-interactive-circle-infographic-widget/'),
			),
            array(
				'key'       => 'htmega-copy-coupon-code-addons',
				'title'      => __( 'Copy Coupon Code', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-copy',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-copy-coupon-code-widget/'),
			),
            array(
				'key'       => 'htmega-video-gallery-addons',
				'title'      => __( 'Video Gallery', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-media-carousel',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-video-gallery-widget/'),
			),
            array(
				'key'       => 'htmega-video-playlist-addons',
				'title'      => __( 'Video Playlist', 'htmega-addons' ),
				'icon'       => 'htmega-icon eicon-video-playlist',
				'action_url' => esc_url('https://wphtmega.com/widget/elementor-video-playlist-widget/'),
			),

        );

        return $promotional_widgets;
       }

    }

    HTMega_Elementor_Addons_Assests::instance();

}