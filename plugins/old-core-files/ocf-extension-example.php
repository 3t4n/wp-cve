<?php
/**
 * Security check
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Sample extensding application
 *
 * This extension will add a new filter option, that will list only
 * old files from the /wp-admin/ directory. JUST SAYING.
 */
class Extending_OCF_Test {

	var $filter = 'wp_admin_files';

	function __construct() {

		// Register the tab, we will call it "group", "filter" or "condition" accross the plugin
		add_filter( 'ocf_filter_methods', array( $this, 'add_group' ) );

		// Attach the filtering function for this tab
		add_filter( "ocf_filter_files_$this->filter", array( $this, 'filter_wp_admin_files' ), 10, 2 );

	}

	function add_group( $groups ) {

		$groups[ $this->filter ] = __( 'wp-admin Files' );
		return $groups;

	}

	function filter_wp_admin_files( $filtered_files, $old_files ) {

		$only_wp_admin_files = array();

		foreach ( $old_files as $file ) {
			// if this file is a /wp-admin/ file
			if ( false !== strpos( $file, 'wp-admin/' ) ) {
				$only_wp_admin_files[] = $file;
			}
		}

		return $only_wp_admin_files;

	}

}
new Extending_OCF_Test;
