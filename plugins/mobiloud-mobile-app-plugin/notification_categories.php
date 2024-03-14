<?php
function ml_get_push_notification_categories() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mobiloud_notification_categories';

	return $wpdb->get_results( "SELECT id, cat_ID FROM $table_name" );
}

function ml_push_notification_categories_clear() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mobiloud_notification_categories';

	$wpdb->query( "TRUNCATE TABLE $table_name" );
}

/**
 * @param int $categoryID
 */
function ml_push_notification_categories_add( $categoryID ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mobiloud_notification_categories';
	$wpdb->insert( $table_name, array( 'cat_ID' => absint( $categoryID ) ), array( '%d' ) );
}


function ml_get_push_notification_taxonomies() {
	return Mobiloud::get_option( 'ml_push_taxonomies_list', array() );
}

function ml_push_notification_taxonomies_clear() {
	Mobiloud::set_option( 'ml_push_taxonomies_list', array() );
}

/**
 * @param array $taxonomies_list
 */
function ml_push_notification_taxonomies_set( $taxonomies_list ) {
	Mobiloud::set_option( 'ml_push_taxonomies_list', $taxonomies_list );
}

