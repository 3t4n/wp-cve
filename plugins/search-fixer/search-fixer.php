<?php
/*
Plugin Name: Search Fixer
Plugin URI: http://www.thunderguy.com/semicolon/2011/06/08/search-fixer-wordpress-plugin/
Description: Correctly handle pretty search URLs containing spaces (WordPress bug http://core.trac.wordpress.org/ticket/13961)
Version: 2.0
Author: Bennett McElwee
Author URI: http://www.thunderguy.com/semicolon/
Licence: GPLv2 (Copyright 2011 Bennett McElwee, except for portion copied from WordPress core)
*/

add_filter('posts_search', 'smcln_fix_search', 10, 2);

function smcln_fix_search($search, $wp_query) {
	global $wpdb;

	// Copied this line from somewhere. I think it prevents the filter from running on subqueries, or something.
	if ($GLOBALS['wp_query'] !== $wp_query) {
		return $search;
	}

	if ( ! empty( $_GET['s'] ) ) {
		// This is not a pretty URL, so we don't need to do anything
		return $search;
	}

	// Shorthand
	$q = &$wp_query->query_vars;
	// $q['s'] has already had slashes stripped

	// Next bit is adapted from http://core.trac.wordpress.org/attachment/ticket/13961/13961.patch
	// Props to Sergey Biryukov.
	$decoded = urldecode( $q['s'] );
	if ( $decoded == $q['s'] ) {
		// url decoding made no difference, so we don't need to do anything
		return $search;
	}

	// Regenerate the search query from the decoded search terms
	$search = '';

	// Replace search string with the decoded version for further processing
	$q['s'] = $decoded;
	
	// Process the decoded search string, as in WP_Query->get_posts() - following code copied more-or-less verbatim
	if ( !empty($q['sentence']) ) {
		$q['search_terms'] = array($q['s']);
	} else {
		preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $q['s'], $matches);
		$q['search_terms'] = array_map('_search_terms_tidy', $matches[0]);
	}
	$n = !empty($q['exact']) ? '' : '%';
	$searchand = '';
	foreach( (array) $q['search_terms'] as $term ) {
		$term = esc_sql( like_escape( $term ) );
		$search .= "{$searchand}(($wpdb->posts.post_title LIKE '{$n}{$term}{$n}') OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}'))";
		$searchand = ' AND ';
	}

	if ( !empty($search) ) {
		$search = " AND ({$search}) ";
		if ( !is_user_logged_in() )
			$search .= " AND ($wpdb->posts.post_password = '') ";
	}

	return $search;
}
