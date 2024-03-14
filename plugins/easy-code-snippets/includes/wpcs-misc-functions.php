<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * Manage plugin's misc functions
 */

/**
 * Get snippet
 */
function ecsnippets_get_snippet( $id ) {
	if( empty($id) ) return false;
	global $wpdb;
	$Snippet = ECSnippets_Snippet::instance();
	return $Snippet->get_snippet( $id );
}

/**
 * Get snippet title by id
 */
function ens_get_snippet_title_by_id( $id ) {
	$Snippet = ECSnippets_Snippet::instance();
	return $Snippet->get_snippet_title( $id, $key, $value );
}
