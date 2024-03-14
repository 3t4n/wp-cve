<?php

/**
 *
 * @link       catchplugins.com
 * @since      1.0
 *
 * @package    Header_Enhancement
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_name = 'header_enhancement_pro_options';

delete_option( $option_name );

// For site options in Multisite
delete_site_option( $option_name );
