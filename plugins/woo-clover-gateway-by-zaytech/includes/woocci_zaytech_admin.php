<?php
/**
 * This class is to add a review request system.
 *
 * Original Author: danieliser
 * Original Author URL: https://danieliser.com
 * Improved & Adapted By Zaytech Team
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Woocci_Modules_Reviews
 *
 * This class adds a review request system for your plugin or theme to the WP dashboard.
 */
class Woocci_Zaytech_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Load Scripts
     */
    public function enqueue_scripts() {
        wp_register_script( 'woocci_admin_scripts', plugins_url( 'assets/js/woocci-admin-scripts.min.js', WOOCCI_MAIN_FILE ), array( ), WOOCCI_VERSION, true );
        wp_enqueue_script( 'woocci_admin_scripts' );
    }
}

new Woocci_Zaytech_Admin();