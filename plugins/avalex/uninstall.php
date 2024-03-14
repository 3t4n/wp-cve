<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $wpdb;
global $wp_version;

$tableName = $wpdb->prefix . 'avalex';

if( $this->wp_version < 6.2 ) {
	$wpdb->query( "DROP TABLE IF EXISTS $tableName" );
} else {
	$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %i", $tableName ) );
}