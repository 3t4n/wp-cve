<?php

use EasyBooking\Settings;

/**
*
* Deprecated settings functions.
* @version 2.2.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Outputs or returns a select input
* @param array - $args
*
**/
function wceb_settings_select( $args ) {
	Settings::select( $args );
}

/**
*
* Outputs or returns a checkbox input
* @param array - $args
*
**/
function wceb_settings_checkbox( $args ) {
	Settings::checkbox( $args );
}

/**
*
* Outputs or returns a text input
* @param array - $args
*
**/
function wceb_settings_input( $args ) {
	Settings::input( $args );
}

/**
*
* Outputs or returns a texterea input
* @param array - $args
*
**/
function wceb_settings_textarea( $args ) {
	Settings::textarea( $args );
}

/**
*
* Outputs a (maybe sortable) table with the possibility to add or delete rows
* @param str - $content - the content name
* @param array - $columns
* @param array - $args
*
**/
function wceb_settings_table( $content, $columns, $args ) {
	Settings::table( $content, $columns, $args );
}

/**
*
* Generate columns for the table
* @param array - $columns
* @param array - $args
* @param array - $item | Existing item
*
**/
function wceb_settings_table_columns( $columns, $args, $item = array() ) {
	Settings::table_columns( $columns, $args, $item );
}