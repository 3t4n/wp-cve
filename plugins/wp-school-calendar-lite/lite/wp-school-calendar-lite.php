<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Lite {

    private static $_instance = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        if ( is_admin() ) {
            require_once WPSC_PLUGIN_DIR . 'lite/includes/admin/meta-boxes.php';
            require_once WPSC_PLUGIN_DIR . 'lite/includes/admin/builder.php';
        }
        
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'wp_loaded',             array( $this, 'register_scripts' ) );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function register_scripts() {
        wp_register_style( 'wpsc-admin-lite', WPSC_PLUGIN_URL . 'lite/assets/css/admin.css' );
    }
    
    /**
     * Load admin stylesheet and script
     * 
     * @since 1.0
     */
    public function admin_enqueue_scripts() {
        $screen = get_current_screen();
        
        if ( isset( $screen->post_type ) && in_array( $screen->post_type, array( 'school_calendar', 'important_date' ) ) && isset( $screen->base ) ) {
            wp_enqueue_style( 'wpsc-admin-lite' );
        }
    }
    
}

WP_School_Calendar_Lite::instance();