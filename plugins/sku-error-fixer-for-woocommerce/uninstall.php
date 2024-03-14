<?php

/**
 * Woocommerce SKU Error Fixer Uninstall
 *
 * Uninstalling plugin deletes options.
 *
 * @package     WordPress
 * @author      WordPress Monsters
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete all plugin options from Data Base
 */

$auto_clean_option = get_option( 'sef_auto_clean' );
$version_option = get_option( 'sku_error_fixer_version' );

if ( isset( $auto_clean_option) ) {
	delete_option( 'sef_auto_clean' );
}
if ( isset( $version_option) ) {
	delete_option( 'sku_error_fixer_version' );
}