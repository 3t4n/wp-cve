<?php
defined( 'ABSPATH' ) or exit;

/**
 * Plugin Name: Scroll Styler
 * Plugin URI: https://jablonczay.com/scrollstyler/
 * Description: Add pretty scroll style to Webkit browsers
 * Author: jablonczay
 * Version: 1.1
 * Author URI: https://jablonczay.com/
 * Text Domain: scrollStyler
 * Domain Path: /languages
 */

if ( ! class_exists('ScrollStyler' ) ) {
    
    class ScrollStyler {

        public static $pluginDbOptionName = 'scrollStylerOptions';
        public static $dataDefaults = array(
            'scrollbarWidth' => 15,
            'scrollbarTrackPadding' => 3,
            'scrollbarTrackBgColor' => '#dddddd',
            'scrollbarThumbBgColor' => 'rgba(0, 0, 0, 0.8)',
            'scrollbarThumbBgColorHover' => 'rgba(0, 0, 0, 0.9)',
            'scrollbarThumbBgColorActive' => 'rgba(0, 0, 0, 1)',
            'scrollbarThumbBorderRadius' => 15
        );
        protected $pluginAdminSlugname = 'scroll-styler';
        protected $pluginAdminName = 'Scroll Styler';
        protected $pluginName = 'ScrollStyler';
        
        /**
         * Construct the plugin object
         */
        public function __construct() {
            
            // Installation and uninstallation hooks
            register_activation_hook( __FILE__, array( $this->pluginName, 'activate' ) );
            register_deactivation_hook( __FILE__, array( $this->pluginName, 'deactivate' ) );
            register_uninstall_hook( __FILE__, array( $this->pluginName, 'uninstall' ) );

            // Localization
            add_action( 'plugins_loaded', array( $this, 'localization' ) );
            
            if ( is_admin() ) {

                require_once( sprintf( "%s/ScrollStylerSettings.php", dirname( __FILE__ ) ) );
                
                new ScrollStylerSettings();
                
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'addActionLinks' ) );

            } else {

                $pluginUrl = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
                $pluginLoad = true;

                if ( strpos( $pluginUrl, 'wp-login' ) !== false || strpos( $pluginUrl, 'wp-admin' ) !== false) {
                    $pluginLoad = false;
                }

                if ( $pluginLoad ) {
                    add_action( 'init', array( $this, 'addPluginAssets' ) );
                }
            }
        }

        /**
         * Get Instance
         */
        public static function getInstance() {

            if ( isset( $instance) ) return;

            $instance = new ScrollStyler;
            return $instance;
        }

        /**
         * Localization
         */
        public function localization() {
            // Localization
            load_plugin_textdomain( 'scrollStyler', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        /**
         * Add Plugin Assets
         */
        public function addPluginAssets() {
            
            $data = json_decode( get_option(self::$pluginDbOptionName ), true );
            $data = array_merge( self::$dataDefaults, $data );
            
            $style = '';
            $style .= '::-webkit-scrollbar{width:' . $data[ 'scrollbarWidth' ] . 'px; background-color:' . $data[ 'scrollbarTrackBgColor' ] . ';}';
            $style .= '::-webkit-scrollbar-track{background-color:' . $data[ 'scrollbarTrackBgColor' ] . ';}';
            $style .= '::-webkit-scrollbar-thumb{background-color:' . $data[ 'scrollbarThumbBgColor' ] . ';border-radius:' . $data[ 'scrollbarThumbBorderRadius' ] . 'px; border: ' . $data[ 'scrollbarThumbBgColor' ] . ';border:' . $data[ 'scrollbarTrackPadding' ] . 'px solid ' . $data[ 'scrollbarTrackBgColor' ] . ';}';
            $style .= '::-webkit-scrollbar-thumb:hover{background-color:' . $data[ 'scrollbarThumbBgColorHover' ] . ';}';
            $style .= '::-webkit-scrollbar-thumb:active{background-color:' . $data[ 'scrollbarThumbBgColorActive' ] . ';}';

            wp_register_style( $this->pluginAdminSlugname, false );
            wp_enqueue_style( $this->pluginAdminSlugname );
            wp_add_inline_style( $this->pluginAdminSlugname, $style );
        }
        
        /**
         * Add the settings link to the plugins page
         */
        public function addActionLinks( $links ) { 
            
            $actionLink = array(
                '<a href="' . admin_url( 'options-general.php?page=' . $this->pluginAdminSlugname ) . '">' . __( 'Settings', 'scrollStyler' ) . '</a>'
            );
            
            return array_merge( $links, $actionLink );
        }
        
        /**
         * Activate the plugin
         */
        public static function activate() {}
        
        /**
         * Deactivate the plugin
         */
        public static function deactivate() {}
        
        /**
         * Uninstall the plugin
         */
        public static function uninstall() {

            if ( ! get_option( self::$pluginDbOptionName ) ) return;

            // Delete options
            delete_option( self::$pluginDbOptionName );
        }
    }
}

/**
 * Create an instance
 */
if ( class_exists( 'ScrollStyler' ) ) {
    $scrollStyler = ScrollStyler::getInstance();
}