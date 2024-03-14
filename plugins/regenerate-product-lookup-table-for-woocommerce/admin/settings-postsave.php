<?php

// No direct access to file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function smnwcrpl_option_post_save( $old_value, $new_value ) {

	$options            = get_option( 'smnwcrpl_options', smnwcrpl_options_default() );
	$cron_schedule_time = sanitize_text_field( $options['cron_schedule_time'] );

	smnwcrpl_product_lookup_table_cron_deactivate();

	wp_schedule_event( time(), $cron_schedule_time, 'smnwcrpl_regenerate_product_lookup_table' );

}

add_action( 'update_option_smnwcrpl_options', 'smnwcrpl_option_post_save', 10, 2 );