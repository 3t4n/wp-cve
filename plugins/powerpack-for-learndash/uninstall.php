<?php
/**
 * Functions for uninstall LearnDash PowerPack
 *
 * @since 1.0.0
 *
 * @package LearnDash PowerPack
 */

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

/**
 * Fires on plugin uninstall.
 */
do_action( 'learndash_powerpack_uninstall' );
