<?php
/**
 * Admin
 *
 * @package GamiPress\Button\Admin
 * @since 1.0.3
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Button automatic updates
 *
 * @since  1.0.3
 *
 * @param array $automatic_updates_plugins
 *
 * @return array
 */
function gamipress_button_automatic_updates( $automatic_updates_plugins ) {

    $automatic_updates_plugins['gamipress-button'] = __( 'GamiPress - Button', 'gamipress-button' );

    return $automatic_updates_plugins;
}
add_filter( 'gamipress_automatic_updates_plugins', 'gamipress_button_automatic_updates' );