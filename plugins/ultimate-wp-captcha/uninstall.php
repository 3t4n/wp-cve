<?php
/**
 * Functions for uninstall Ultimate WP Captcha
 *
 * @since 1.0.0
 *
 * @package uwc
 */

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Fires on plugin uninstall.
 */
do_action( 'uwc_uninstall' );
