<?php

/**
 * File which gets called on plugin uninstall.
 * Since the plugin does not do any sort of setup, nothing is done over here.
 *
 * @link       http://www.webfactoryltd.com
 * @since      0.1
 *
 * Checking whether the file is called by the Wordpress uninstall action or not
 * If not, then exit and prevent unauthorized access
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/* Removing the options from the database. */
delete_option('signals_csmm_options');
delete_option('signals_csmm_meta');
delete_option('csmm_pointers');
delete_transient('csmm_rate_hide');
delete_transient('csmm_welcome_hide');
delete_transient('csmm_olduser_hide');
