<?php

/*
Plugin Name: Financial Stocks & Crypto Market Data Plugin
Plugin URI: https://eodhd.com/financial-apis/wordpress-plugin-for-stock-data/
Description: The stock prices plugin allows you to use a widget and a shortcode to display the ticker data you want.
Version: 1.10.3
Author: Eod Historical Data
Author URI: https://eodhd.com
*/


require( plugin_dir_path( __FILE__ ) . 'widget/ticker-widget.php' );
require( plugin_dir_path( __FILE__ ) . 'widget/news-widget.php' );
require( plugin_dir_path( __FILE__ ) . 'widget/fundamental-widget.php' );
require( plugin_dir_path( __FILE__ ) . 'widget/financial-widget.php' );
require( plugin_dir_path( __FILE__ ) . 'widget/converter-widget.php' );

if(!class_exists('EOD_Stock_Prices_Plugin'))
{
    class EOD_Stock_Prices_Plugin{
        /**
         * A dummy constructor to ensure EOD is only setup once.
         */
        public function __construct(){
            // Do nothing.
        }

        /**
         * Sets up the EOD plugin.
         */
        function initialize()
        {
            // Define constants.
            $IN_DEV = false;
            $this->define( 'EOD_VER', $IN_DEV ? (string) time() : '1.10.2' );
            $this->define( 'EOD_PLUGIN_NAME', 'Financial Stocks & Crypto Market Data Plugin' );
            $this->define( 'EOD_DEFAULT_API', 'demo' );
            $this->define( 'EOD_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'EOD_URL', plugins_url( '/',__FILE__ ) );
            $this->define( 'EOD_BASENAME', plugin_basename( __FILE__ ) );
            $this->define( 'EOD_ELEMENTOR_INSTALLED', in_array( 'elementor/elementor.php', (array) get_option( 'active_plugins', array() ) ) );
            $this->define( 'EOD_DEFAULT_OPTIONS', array(
                'api_key'   => EOD_DEFAULT_API,
            ));
            $this->define( 'EOD_DEFAULT_SETTINGS', array(
                'ndap'              => 3,
                'ndape'             => 2,
                'scrollbar'         => 'on',
                'news_ajax'         => 'on',
                'fd_no_data_warning'=> 'on',
                'evolution_type'    => 'abs',
                'main_color'        => '#f06a40',
            ));

            // Include utility functions.
            include_once EOD_PATH . 'eod-utility-functions.php';

            // Include Classes
            eod_include( 'class/EOD_FD.php' );
            eod_include( 'class/EOD_Financial.php' );
            eod_include( 'class/EOD_Fundamental_Data.php' );

            // Include EOD API.
            eod_include( 'eod-api.php' );

            // Include EOD shortcodes
            eod_include( 'eod-shortcodes.php' );

            // EOD AJAX
            eod_include( 'eod-ajax.php' );

            // Add actions and filters.
            add_action( 'init', array( $this, 'init' ), 5 );
            add_action( 'init', array( $this, 'register_post_types' ), 5 );
            add_action( 'wp_head', array( $this, 'add_header_css' ), 5 );
            add_action( 'rest_api_init',  array( $this, 'eod_rest_api' ) );
            add_action( 'wp_enqueue_scripts',  array( $this, 'client_scripts' ) );
            add_action( 'widgets_init', array( $this, 'register_widgets' ) );

            // Admin panel
            eod_include( 'admin/eod-admin.php' );
            eod_include( 'admin/fundamental-data-presets.php' );
            eod_include( 'admin/financial-presets.php' );

            // Support of elementor
            eod_include( 'plugins/elementor/EOD_Elementor.php' );
        }

        /**
         * Completes the setup process on "init" of earlier.
         */
        function init() {
            // Bail early if called directly from functions.php or plugin file.
            if ( ! did_action( 'plugins_loaded' ) ) {
                return;
            }

            if(is_admin()) {
                $this->admin = new EOD_Stock_Prices_Admin();
            }
        }

        /**
         * Register post types
         */
        function register_post_types(){
            register_post_type('fundamental-data', array(
                'labels'            => array(
                    'name'              => 'Fundamental Data presets',
                    'singular_name'     => 'Fundamental Data preset',
                    'menu_name'         => 'Fundamental Data preset',
                    'all_items'         => 'Fundamental Data presets',
                    'view_item'         => 'View fundamental data preset',
                    'add_new_item'      => 'Add new fundamental data preset',
                    'add_new'           => 'Add new',
                    'edit_item'         => 'Edit fundamental data preset',
                    'update_item'       => 'Update fundamental data preset',
                    'search_items'      => 'Find fundamental data preset',
                    'not_found'         => 'Not found',
                    'not_found_in_trash' => 'Not found in trash'
                ),
                'description'       => '-',
                'supports'          => array('title'),
                'hierarchical'      => false,
                'public'            => false,
                'show_in_rest'      => true,
                'show_ui'           => true,
                'show_in_menu'      => 'eod-stock-prices',
                'menu_position'     => 3,
                'can_export'        => true,
                'has_archive'       => true,
                'rewrite'           => true,
                'capability_type'   => 'page',
            ));

            register_post_type('financials', array(
                'labels'            => array(
                    'name'              => 'Financial Table presets',
                    'singular_name'     => 'Financial Table preset',
                    'menu_name'         => 'Financial Table preset',
                    'all_items'         => 'Financial Table presets',
                    'view_item'         => 'View financial table preset',
                    'add_new_item'      => 'Add new financial table preset',
                    'add_new'           => 'Add new',
                    'edit_item'         => 'Edit financial table preset',
                    'update_item'       => 'Update financial table preset',
                    'search_items'      => 'Find financial table preset',
                    'not_found'         => 'Not found',
                    'not_found_in_trash' => 'Not found in trash'
                ),
                'description'       => '-',
                'supports'          => array('title'),
                'hierarchical'      => false,
                'public'            => false,
                'show_in_rest'      => true,
                'show_ui'           => true,
                'show_in_menu'      => 'eod-stock-prices',
                'menu_position'     => 3,
                'can_export'        => true,
                'has_archive'       => true,
                'rewrite'           => true,
                'capability_type'   => 'page',
            ));
        }

        public function eod_rest_api(){
            $namespace = 'eod-api/v2';

            // Get info about Fundamental Data preset.
            register_rest_route($namespace, 'get-fd-preset/(?P<id>\d+)', array(
                'methods'   => WP_REST_Server::READABLE,
                'callback'  => array( $this, 'rest_api_fd' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                ),
                'permission_callback' => '__return_true',
            ));
        }
        public function rest_api_fd( $data ){
            $preset = get_post( $data['id'] );
            $response = [
                'id' => $preset->ID,
                'fd_list' => get_post_meta($preset->ID, '_fd_list', true),
                'fd_type' => get_post_meta($preset->ID, '_fd_type', true)
            ];

            return new WP_REST_Response( $response );
        }

        /**
         * Register widgets
         */
        public function register_widgets(){
            register_widget( 'EOD_Stock_Prices_Widget' );
            register_widget( 'EOD_News_Widget' );
            register_widget( 'EOD_Fundamental_Widget' );
            register_widget( 'EOD_Financial_Widget' );
            register_widget( 'EOD_Converter_Widget' );
        }

        /**
         *
         */
        public function client_scripts() {
            $eod_display_settings = get_eod_display_options();

            // Base
            wp_enqueue_script( 'eod_stock-prices-plugin', EOD_URL . 'js/eod-stock-prices.js', array('jquery'), EOD_VER );
            wp_enqueue_style('eod_stock-prices-plugin', EOD_URL . 'css/eod-stock-prices.css', array(), EOD_VER);

            // Add ajax vars
            wp_add_inline_script( 'eod_stock-prices-plugin', 'let eod_ajax_nonce = "'.wp_create_nonce('eod_ajax_nonce').'", eod_ajax_url = "'.admin_url('admin-ajax.php').'";', 'before' );

            // Add display vars
            wp_localize_script( 'eod_stock-prices-plugin', 'eod_display_settings', $this->get_js_display_settings());
            wp_localize_script( 'eod_stock-prices-plugin', 'eod_service_data', $this->get_js_service_data());

            // Simple bar
            if( !wp_is_mobile() && $eod_display_settings['scrollbar'] === 'on' ) {
                wp_enqueue_script('simplebar', EOD_URL . 'js/simplebar.min.js', array('jquery'));
                wp_enqueue_style('simplebar', EOD_URL . 'css/simplebar.css');
            }
        }

        public function add_header_css() {
            $eod_display_settings = get_eod_display_options();
            $main_color = $eod_display_settings['main_color'];
            ?>
            <!-- Start additional EOD css -->
            <style>
                .eod_toggle{
                    border: 1px solid <?= $main_color ?>;
                }
                .eod_toggle span{
                    color: <?= $main_color ?>;
                }
                .eod_toggle span.selected{
                    background-color: <?= $main_color ?>;
                }
            </style>
            <!-- End additional EOD css -->
            <?php
        }

        /**
         * Return display settings for js scripts
         * @return array
         */
        public static function get_js_display_settings() {
            global $eod_api;

            $eod_display_settings = get_eod_display_options();
            return array(
                'evolution_type'        => isset($eod_display_settings['evolution_type']) ? $eod_display_settings['evolution_type'] : EOD_DEFAULT_SETTINGS['evolution_type'],
                'ndap'                  => isset($eod_display_settings['ndap']) ? $eod_display_settings['ndap'] : EOD_DEFAULT_SETTINGS['ndap'],
                'ndape'                 => isset($eod_display_settings['ndape']) ? $eod_display_settings['ndape'] : EOD_DEFAULT_SETTINGS['ndape'],
                'fd_no_data_warning'    => $eod_display_settings['fd_no_data_warning'] === 'on',
                'news_ajax'             => $eod_display_settings['news_ajax'] === 'on',
                'financial_hierarchy'   => $eod_api->get_financial_hierarchy(),
            );
        }

        /**
         * Return service data for js scripts
         * @return array
         */
        public static function get_js_service_data() {
            global $eod_api;
            $cc_list = $eod_api->get_cc_codes();
            $forex_list = $eod_api->get_forex_codes();

            return array(
                'converter_targets' => ['cc' => $cc_list, 'forex' => $forex_list]
            );
        }

        /**
         * Defines a constant if doesnt already exist.
         *
         * @param string $name The constant name.
         * @param mixed  $value The constant value.
         */
        function define($name, $value = true ) {
            if ( !defined( $name ) ) {
                define( $name, $value );
            }
        }
    }
}


if(class_exists('EOD_Stock_Prices_Plugin')) {
    /*
    *
    * The main function responsible for returning the one true eod Instance to functions everywhere.
    * Use this function like you would a global variable, except without needing to declare the global.
    *
    * Example: <?php $eod = eod(); ?>
    * @param   void
    * @return  EOD_Stock_Prices_Plugin
    */
    function eod()
    {
        global $eod;

        // Instantiate only once.
        if ( ! isset( $eod ) ) {
            $eod = new EOD_Stock_Prices_Plugin();
            $eod->initialize();
        }
        return $eod;
    }

    // Instantiate.
    eod();
}


