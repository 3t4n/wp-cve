<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Includes the files needed for the Legend Items admin area
 *
 */
function wpsbc_include_files_admin_calendar() {

	// Get legend admin dir path
	$dir_path = plugin_dir_path( __FILE__ );

	// Include submenu page
	if( file_exists( $dir_path . 'class-submenu-page-calendar.php' ) )
		include $dir_path . 'class-submenu-page-calendar.php';

	// Include calendars list table
	if( file_exists( $dir_path . 'class-list-table-calendars.php' ) )
		include $dir_path . 'class-list-table-calendars.php';

	// Include calendar editor outputter
	if( file_exists( $dir_path . 'class-calendar-editor-outputter.php' ) )
		include $dir_path . 'class-calendar-editor-outputter.php';

	// Include admin actions
	if( file_exists( $dir_path . 'functions-actions-ical.php' ) )
		include $dir_path . 'functions-actions-ical.php';

	if( file_exists( $dir_path . 'functions-actions-calendar.php' ) )
		include $dir_path . 'functions-actions-calendar.php';

	if( file_exists( $dir_path . 'functions-actions-legend-item.php' ) )
		include $dir_path . 'functions-actions-legend-item.php';

	if( file_exists( $dir_path . 'functions-actions-ajax-calendar.php' ) )
		include $dir_path . 'functions-actions-ajax-calendar.php';

	if( file_exists( $dir_path . 'functions-actions-ajax-legend-item.php' ) )
		include $dir_path . 'functions-actions-ajax-legend-item.php';

	if( file_exists( $dir_path . 'functions-shortcode-generator.php' ) )
		include $dir_path . 'functions-shortcode-generator.php';

}
add_action( 'wpsbc_include_files', 'wpsbc_include_files_admin_calendar' );


/**
 * Register the Calendars admin submenu page
 *
 */
function wpsbc_register_submenu_page_calendars( $submenu_pages ) {

	if( ! is_array( $submenu_pages ) )
		return $submenu_pages;

	$submenu_pages['calendars'] = array(
		'class_name' => 'WPSBC_Submenu_Page_Calendars',
		'data' 		 => array(
			'page_title' => __( 'Calendars', 'wp-simple-booking-calendar' ),
			'menu_title' => __( 'Calendars', 'wp-simple-booking-calendar' ),
			'capability' => apply_filters( 'wpsbc_submenu_page_capability_calendars', 'manage_options' ),
			'menu_slug'  => 'wpsbc-calendars'
		)
	);

	return $submenu_pages;

}
add_filter( 'wpsbc_register_submenu_page', 'wpsbc_register_submenu_page_calendars', 20 );


/**
 * Returns the HTML for the legend item icon
 *
 * @param int    $legend_item_id
 * @param string $type
 * @param array  $color
 *
 * @return string
 *
 */
function wpsbc_get_legend_item_icon( $legend_item_id, $type, $color = array() ) {

	$output = '<div class="wpsbc-legend-item-icon wpsbc-legend-item-icon-' . esc_attr( $legend_item_id ) . '" data-type="' . esc_attr( $type ) . '">';

		for( $i = 0; $i <= 1; $i++ ){
			
			$svg = '';	
			if($type == "split"){
				$svg = ($i == 0) ? '<svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,0 0,50 50,0" /></svg>' : '<svg height="100%" width="100%" viewBox="0 0 50 50" preserveAspectRatio="none"><polygon points="0,50 50,50 50,0" /></svg>';
			} 
			
			
			$output .= '<div class="wpsbc-legend-item-icon-color" ' . ( ! empty( $color[$i] ) ? 'style="background-color: ' . esc_attr( $color[$i] ) . ';"' : '' ) . '>' . $svg . '</div>';
		}

	$output .= '</div>';

	return $output;

}