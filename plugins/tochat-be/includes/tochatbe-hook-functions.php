<?php
defined( 'ABSPATH' ) || exit;

/**
 * Override about message by post or page
 *
 * @since 1.0.5
 * 
 * @return string
 */
function tochatbe_about_message_by_page( $about_message ) {
    if ( ! get_the_ID() ) {
        return $about_message;
    }
    if ( ! $mod_about_message = get_post_meta( get_the_ID(), '_tochatbe_about_message', true ) ) {
        return $about_message;
    }

    return $mod_about_message;
}