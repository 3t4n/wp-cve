<?php
defined( 'ABSPATH' ) or exit;

if (!class_exists('ScrollStylerSettings')) {

    class ScrollStylerSettings extends ScrollStyler {

        /**
        * Constructor
        */
        public function __construct() {
            
            /**
            * Register actions, hook into WP's admin_init action hook
            */
            add_action( 'admin_init', array( $this, 'initAdmin' ) );
            add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
        }

        /**
        * Include custom assets to the admin page
        */
        public function addAdminAssets() {
            
            // CSS
            wp_enqueue_style( $this->pluginAdminSlugname . '-spectrum-css', plugins_url( '/assets/minicolors/jquery.minicolors.css', __FILE__ ) );
            wp_enqueue_style( $this->pluginAdminSlugname . '-admin-css', plugins_url( '/assets/css/' . $this->pluginAdminSlugname . '-admin.css', __FILE__ ) );
            
            //JS
            wp_enqueue_script( $this->pluginAdminSlugname . '-spectrum-js', plugins_url( '/assets/minicolors/jquery.minicolors.min.js', __FILE__ ) );
            wp_enqueue_script( $this->pluginAdminSlugname . '-admin-js', plugins_url( '/assets/js/' . $this->pluginAdminSlugname . '-admin.js', __FILE__ ) );
        }

        /**
         * Initialize datas on wp admin
         */
        public function initAdmin() {

            $settingsPage = '';
            
            if ( isset( $_REQUEST[ 'page' ] ) ) {
                $settingsPage = $_REQUEST[ 'page' ];   
            }
            
            if ( $settingsPage === $this->pluginAdminSlugname ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'addAdminAssets' ) );
            }
        }

        /**
         * Add menu
         */
        public function addAdminMenu() {
            
            // Add a page to manage this plugin's settings
            add_options_page( $this->pluginAdminName, $this->pluginAdminName, 'manage_options', $this->pluginAdminSlugname, array( $this, 'pluginSettingsPage' ) );
        }

        /**
         * Menu callback
         */
        public function pluginSettingsPage() {
            
            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.', 'scrollStyler' ) );
            }

            // Render the settings template
            include( sprintf( "%s/templates/settings.php", dirname( __FILE__ ) ) );
        }
    }
}