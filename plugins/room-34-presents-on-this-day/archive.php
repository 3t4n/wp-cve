<?php

// Define rewrite rules
global $r34otd_rewrites;
$r34otd_rewrites = array(
	'archives/otd/([0-9]{4})/page/([0-9]{1,})/?$' => 'index.php?r34otdarchive=true&r34otddate=$matches[1]&paged=$matches[2]',
	'archives/otd/([0-9]{4})/?$' => 'index.php?r34otdarchive=true&r34otddate=$matches[1]',
	'archives/otd/page/([0-9]{1,})/?$' => 'index.php?r34otdarchive=true&paged=$matches[1]',
	'archives/otd/?$' => 'index.php?r34otdarchive=true',
);


// Modify query with date filtering
add_action('pre_get_posts', 'r34otd_pre_get_posts', 10, 1);
function r34otd_pre_get_posts($query) {
	if ($query->is_main_query() && get_query_var('r34otdarchive')) {
		if ($r34otddate = get_query_var('r34otddate')) {
			$query->set('monthnum', intval(substr($r34otddate,0,2)));
			$query->set('day', intval(substr($r34otddate,2,2)));
		}
		else {
			$query->set('monthnum', wp_date('n'));
			$query->set('day', wp_date('j'));
		}
		$query->set('date_query', array(array('before' => array('year' => wp_date('Y')))));
	}
}


// Switch to archive template
add_filter('template_include', 'r34otd_template_include', 10, 1);
function r34otd_template_include($template) {
	global $wp_query;
	if ($wp_query->is_main_query() && get_query_var('r34otdarchive')) {
		if ($new_template = locate_template(array('archive.php'))) {
			$template = $new_template;
		}
	}
	return $template;
}


// Change archive title
add_filter('get_the_archive_title', 'r34otd_get_the_archive_title', 10, 1);
add_filter('pre_get_document_title', 'r34otd_get_the_archive_title', 10, 0);
function r34otd_get_the_archive_title($title=null) {
	global $wp_query;
	if ($wp_query->is_main_query() && get_query_var('r34otdarchive')) {
		if ($r34otddate = get_query_var('r34otddate')) {
			$date = strtotime(substr($r34otddate,0,2) . '/' . substr($r34otddate,2,2) . wp_date('Y'));
		}
		else {
			$date = current_time('timestamp');
		}
		$title = wp_date(trim(str_ireplace('Y','',get_option('date_format'))), $date);
	}
	return $title;
}


// Add query variable
add_filter('query_vars', 'r34otd_query_vars', 10, 1);
function r34otd_query_vars($vars) {
	$vars[] = 'r34otdarchive';
	$vars[] = 'r34otddate';
	return $vars;
}


// Add rewrite rules
add_filter('rewrite_rules_array', 'r34otd_rewrite_rules_array', 10, 1);
function r34otd_rewrite_rules_array($rules) {
	global $r34otd_rewrites;
	return (array)$r34otd_rewrites + (array)$rules;
}


// Check if rewrites have been added and flush if needed
add_action('wp_loaded', 'r34otd_wp_loaded', 10, 0);
function r34otd_wp_loaded() {
	global $r34otd_rewrites;
	$rules = get_option('rewrite_rules');
	if (!isset($rules[$r34otd_rewrites[key((array)$r34otd_rewrites)]])) {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
}
