<?php
/**
 * Scripts
 *
 * @package     GamiPress\Notifications\By_Type\Scripts
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
function gamipress_notifications_by_type_admin_register_scripts() {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Scripts
    wp_register_script( 'gamipress-notifications-by-type-admin-js', GAMIPRESS_NOTIFICATIONS_BY_TYPE_URL . 'assets/js/gamipress-notifications-by-type-admin' . $suffix . '.js', array( 'jquery' ), GAMIPRESS_NOTIFICATIONS_BY_TYPE_VER, true );

}
add_action( 'admin_init', 'gamipress_notifications_by_type_admin_register_scripts' );

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_notifications_by_type_admin_enqueue_scripts( $hook ) {

    global $post_type;

    if( in_array( $post_type, array( 'achievement-type', 'points-type', 'rank-type' ) )
        || in_array( $post_type, gamipress_get_achievement_types_slugs() )
        || in_array( $post_type, gamipress_get_rank_types_slugs() ) ) {

        // Localize scripts
        wp_localize_script( 'gamipress-notifications-by-type-admin-js', 'gamipress_notifications_by_type', array(
            'achievement_fields'    => array_keys( GamiPress()->shortcodes['gamipress_achievement']->fields ),
            'rank_fields'           => array_keys( GamiPress()->shortcodes['gamipress_rank']->fields )
        ) );

        //Scripts
        wp_enqueue_script( 'gamipress-notifications-by-type-admin-js' );
    }

}
add_action( 'admin_enqueue_scripts', 'gamipress_notifications_by_type_admin_enqueue_scripts', 100 );