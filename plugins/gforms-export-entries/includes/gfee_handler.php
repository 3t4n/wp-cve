<?php

add_action( "wp_ajax_gfee_manual_export", "gfee_manual_export" );
function gfee_manual_export() {

	$export_name = sanitize_text_field( $_POST['export_name'] );
	
	if ( empty( $export_name ) ) {
		$export_name = __( 'Default', 'gforms-export-entries' );
	}
	
	$start = sanitize_text_field( $_POST['date_start'] );
	$stop = sanitize_text_field( $_POST['date_stop'] );

	gfee_generate_export( $export_name, $start, $stop );

	exit();	
}

add_action( "wp_ajax_gfee_delete_export", "gfee_delete_export" );
function gfee_delete_export() {

	$export_name = sanitize_text_field( $_POST['export_name'] );
	
	$settings = get_option( 'gfee_settings', array() );
	
	unset( $settings['exports'][ $export_name ] );
	update_option( 'gfee_settings', $settings, false );

	echo $export_name;

	exit();
}

add_action( "wp_ajax_gfee_import_settings", "gfee_import_settings" );
function gfee_import_settings() {
	$settings = html_entity_decode( $_POST['gfee_settings'] );
	$settings = str_replace( '\\', '', $settings);

	$settings = substr( $settings, 1 );
	$settings = substr( $settings, 0, -1 );

	$settings = json_decode( $settings, true );
	$settings = (array) $settings;

	update_option( 'gfee_settings', $settings, false );
	exit();
}

?>