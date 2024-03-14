<?php
/**
 * Scripts
 *
 * @package     GamiPress\LearnDash\Points_Importer\Scripts
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_learndash_points_importer_admin_register_scripts() {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Scripts
    wp_register_script( 'gamipress-learndash-points-importer-admin-js', GAMIPRESS_LEARNDASH_POINTS_IMPORTER_URL . 'assets/js/gamipress-learndash-points-importer-admin' . $suffix . '.js', array( 'jquery' ), GAMIPRESS_LEARNDASH_POINTS_IMPORTER_VER, true );

}
add_action( 'admin_init', 'gamipress_learndash_points_importer_admin_register_scripts' );

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_learndash_points_importer_admin_enqueue_scripts( $hook ) {


    // Tools page
    if( $hook === 'gamipress_page_gamipress_tools' ) {

        //Scripts
        wp_enqueue_script( 'gamipress-learndash-points-importer-admin-js' );

    }

}
add_action( 'admin_enqueue_scripts', 'gamipress_learndash_points_importer_admin_enqueue_scripts', 100 );