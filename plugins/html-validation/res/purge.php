<?php
/**
 * Purge errors
 ***/
function html_validation_purge_errors_by_type() {
	global $wpdb;

	$errortypes = get_option( 'html_validation_error_types', array( 'warning', 'error' ) );

	if ( ! in_array( 'error', $errortypes ) ) {
		$wpdb->query( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'html_validation_errors where errortype = %s ', 'error' ), ARRAY_A );
	}

	if ( ! in_array( 'warning', $errortypes ) ) {
		$wpdb->query( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'html_validation_errors where errortype = %s ', 'warning' ), ARRAY_A );
	}
}

/**
 * Purge errors when file ignored
 **/
function html_validation_purge_errors_by_linkid( $linkid ) {
	global $wpdb;
	if ( '' != $linkid ) {
		$wpdb->query( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'html_validation_errors where linkid = %d ', $linkid ), ARRAY_A );
	}
}

/**
 * Purge errors that no longer exists
 **/
function html_validation_purge_missing_error( $linkid ) {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'DELETE from ' . $wpdb->prefix . 'html_validation_errors where linkid = %d and purgemarker = %d', $linkid, 1 ), ARRAY_A );
}

/**
 * Purge all
 **/
function html_validation_purge_all() {
	global $wpdb;
	$wpdb->query( 'DELETE from ' . $wpdb->prefix . 'html_validation_errors', ARRAY_A );
	$wpdb->query( 'DELETE from ' . $wpdb->prefix . 'html_validation_links', ARRAY_A );
}

/**
 * Purge post type records not monitored
 **/
function html_validation_purge_post_types() {
	global $wpdb;
	$checked = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );

	// purge posts.
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT distinct(post_type) FROM ' . $wpdb->prefix . 'posts  where post_status = %s or post_type = %s', array( 'publish', 'attachment' ) ), ARRAY_A );

	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! is_array( $checked ) || is_array( $checked ) && ! in_array( $row['post_type'], $checked ) ) {

				// ignore links.
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s ', array( 1, $row['post_type'] ) ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'html_validation_errors WHERE linktype = %s ', $row['post_type'] ) );
			} else { // in ignore links.
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s ', array( 0, $row['post_type'] ) ) );
			}
		}
	}
}

/**
 * Purge terms not being monitored
 **/
function html_validation_purge_terms() {
	global $wpdb;
	// purge taxonomy.
	$categories = get_option( 'html_validation_terms', array( 'category' ) );

	$results = $wpdb->get_results( 'SELECT distinct(taxonomy) FROM ' . $wpdb->prefix . 'term_taxonomy', ARRAY_A );
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( ! is_array( $categories ) || is_array( $categories ) && ! in_array( $row['taxonomy'], $categories ) ) {

				$wpdb->query( $wpdb->prepare( 'update ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s', array( 1, $row['taxonomy'] ) ) );
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'html_validation_errors WHERE linktype = %s ', $row['taxonomy'] ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'update ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s', array( 0, $row['taxonomy'] ) ) );
			}
		}
	}
}

/**
 * Purge theme items records
 **/
function html_validation_purge_theme_scan_item() {

	global $wpdb, $html_validation_theme_scan_items;

	$theme_scan_items = get_option( 'html_validation_scan_themes', array( 'Blog Home', '404 Page', 'Search Page' ) );

	if ( ! is_array( $theme_scan_items ) ) {
		foreach ( $html_validation_theme_scan_items as $key => $value ) {
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s', array( 1, $value ) ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'html_validation_errors WHERE linktype = %s ', array( $value ) ) );
		}
	} else {
		foreach ( $html_validation_theme_scan_items as $key => $value ) {
			if ( ! in_array( $value, $theme_scan_items ) ) {

				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s ', array( 1, $value ) ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'html_validation_errors WHERE linktype = %s ', array( $value ) ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'html_validation_links set linkignre = %d WHERE subtype = %s ', array( 0, $value ) ) );
			}
		}
	}
}

/**
 * Purge deleted posts and terms (called as required)
 **/
function html_validation_purge_deleted_records() {

	if ( ! defined( 'DOING_CRON' ) && ! current_user_can( 'edit_pages' ) ) {
		return 1;
	}

	// purge term that have been deleted.
	html_validation_remove_deleted_terms();

	// purge trashed records.
	html_validation_purge_trashed_post_records();

	// purge records for posts moved to draft.
	html_validation_purge_draft_post_records();

	// purge post archive records.
	html_validation_purge_post_archive_records();
}

/**
 * Remove deleted term
 **/
function html_validation_remove_deleted_terms() {
	global $wpdb;

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT distinct subtype, title FROM ' . $wpdb->prefix . 'html_validation_links where type= %s', 'term' ), ARRAY_A );

	if ( $results ) {
		foreach ( $results as $row ) {

			$term = term_exists( $row['title'] );

			if ( 0 == $term || null == $term ) {

				$wpdb->query( $wpdb->prepare( 'DELETE ' . $wpdb->prefix . 'html_validation_links, ' . $wpdb->prefix . 'html_validation_errors FROM ' . $wpdb->prefix . 'html_validation_links INNER JOIN ' . $wpdb->prefix . 'html_validation_errors ON ' . $wpdb->prefix . 'html_validation_links.linkid = ' . $wpdb->prefix . 'html_validation_errors.linkid WHERE ' . $wpdb->prefix . 'html_validation_links.type = %s and subtype = %s and ' . $wpdb->prefix . 'html_validation_links.title = %s', 'term', $row['subtype'], $row['title'] ) );

			}
		}
	}
}

/**
 * Purge archives for post types not monitored
 **/
function html_validation_purge_post_archive_records() {
	global $wpdb;

	$post_types = get_option( 'html_validation_posttypes', array( 'page', 'post' ) );
	if ( ! is_array( $post_types ) ) {
		$post_types = array();
	}

	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT distinct(post_type) FROM ' . $wpdb->prefix . 'posts where post_status = %s', 'publish' ), ARRAY_A );

	foreach ( $results as $row ) {

		// purge post archive items.
		if ( ! in_array( $row['post_type'], $post_types ) ) {

			$wpdb->query( $wpdb->prepare( 'DELETE ' . $wpdb->prefix . 'html_validation_links, ' . $wpdb->prefix . 'html_validation_errors FROM ' . $wpdb->prefix . 'html_validation_links INNER JOIN ' . $wpdb->prefix . 'html_validation_errors ON ' . $wpdb->prefix . 'html_validation_links.linkid = ' . $wpdb->prefix . 'html_validation_errors.linkid WHERE ' . $wpdb->prefix . 'html_validation_links.type = %s and ' . $wpdb->prefix . 'html_validation_links.subtype = %s', 'archive', $row['post_type'] . '_archive' ) );
		}
	}
}

/**
 * Purge deleted post records
 **/
function html_validation_purge_trashed_post_records() {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT ID, post_type FROM ' . $wpdb->prefix . 'posts where post_status = %s', 'trash' ), ARRAY_A );

	foreach ( $results as $row ) {

		$wpdb->query( $wpdb->prepare( 'DELETE ' . $wpdb->prefix . 'html_validation_links, ' . $wpdb->prefix . 'html_validation_errors FROM ' . $wpdb->prefix . 'html_validation_links INNER JOIN ' . $wpdb->prefix . 'html_validation_errors ON ' . $wpdb->prefix . 'html_validation_links.linkid = ' . $wpdb->prefix . 'html_validation_errors.linkid WHERE ' . $wpdb->prefix . 'html_validation_links.type = %s and ' . $wpdb->prefix . 'html_validation_links.subtype = %s and ' . $wpdb->prefix . 'html_validation_links.postid = %d', 'posttype', $row['post_type'], $row['ID'] ) );
	}
}


/**
 * Purge draft post records
 **/
function html_validation_purge_draft_post_records() {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT ID, post_type FROM ' . $wpdb->prefix . 'posts where post_status = %s or post_status = %s or post_status = %s or post_status = %s or post_status = %s', 'tao_sc_publish', 'draft', 'auto-draft', 'revision', 'private' ), ARRAY_A );

	foreach ( $results as $row ) {

		$wpdb->query( $wpdb->prepare( 'DELETE ' . $wpdb->prefix . 'html_validation_links, ' . $wpdb->prefix . 'html_validation_errors FROM ' . $wpdb->prefix . 'html_validation_links INNER JOIN ' . $wpdb->prefix . 'html_validation_errors ON ' . $wpdb->prefix . 'html_validation_links.linkid = ' . $wpdb->prefix . 'html_validation_errors.linkid WHERE ' . $wpdb->prefix . 'html_validation_links.type = %s and ' . $wpdb->prefix . 'html_validation_links.subtype = %s and ' . $wpdb->prefix . 'html_validation_links.postid = %d', 'posttype', $row['post_type'], $row['ID'] ) );
	}
}
