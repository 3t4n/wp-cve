<?php
/**
 * Scripts
 *
 * @package     MailerLiteFIRSS\Scripts
 * @since       1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function mailerlite_firss_admin_scripts( $hook ) {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min';

    /**
     *	Settings page only
     */
    $screen = get_current_screen();

    if ( ! empty( $screen->base ) && ( $screen->base == 'settings_page_plugin_name' || $screen->base == 'widgets' ) ) {

        wp_enqueue_script( 'plugin_name_admin_js', MAILERLITE_FIRSS_URL . '/assets/js/admin' . $suffix . '.js', array( 'jquery' ), MAILERLITE_FIRSS_VER );
        wp_enqueue_style( 'plugin_name_admin_css', MAILERLITE_FIRSS_URL . '/assets/css/admin' . $suffix . '.css', false, MAILERLITE_FIRSS_VER );
    }
}
add_action( 'admin_enqueue_scripts', 'mailerlite_firss_admin_scripts', 100 );