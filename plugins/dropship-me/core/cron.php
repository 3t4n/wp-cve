<?php
/**
 * Author: Vitaly Kukin
 * Date: 12.10.2018
 * Time: 13:43
 */

function dm_setup_schedule() {
	
	if ( ! wp_next_scheduled( 'dm_cron_image_upload_event' ) ) {
		wp_schedule_event( time(), '3_min', 'dm_cron_image_upload_event');
	}
}
add_action( 'admin_init', 'dm_setup_schedule' );

function dm_cron_schedules( $schedules ) {
	
	$schedules['3_min'] = [
		'interval' => 180,
		'display'  => __( 'One time per 3 minutes', 'dm' ),
	];
	
	$schedules['10_sec'] = [
		'interval' => 10,
		'display'  => __( 'One time per 10 seconds', 'dm' ),
	];
	
	return $schedules;
}
add_filter( 'cron_schedules', 'dm_cron_schedules' );

function dm_cron_image_upload() {
	
	global $wpdb;
	
	if( get_transient( 'me_has_task_upload_images' ) ) {
		return false;
	}
	
	$result = $wpdb->get_row(
		"SELECT option_value
		 FROM {$wpdb->options}
		 WHERE option_name LIKE 'task_upload_images_%' LIMIT 1",
		ARRAY_A
	);
	
	if( ! $result ) {
		return [ 'success' => false, 'messages' => __( 'No task upload images', 'dm' ) ];
	}
	
	$params = maybe_unserialize( $result[ 'option_value' ] );
	
	$post_id = $params[ 'post_id' ];
	
	$post = get_post( $post_id );
	
	if( ! $post ) {
		
		\delete_option( 'me_task_upload_images_' . $post_id );
		
		return false;
	}
	
	$img = new \dm\dmUploadImages();
	
	$images = [
		'images' => []
	];
	
	if( count( $params[ 'images' ] ) ) {
		
		if ( DM_PLUGIN == 'woocommerce' ) {
			
			$images = $img->uploadImagesWoo( $params );
		} else {
			
			$images = $img->uploadImages( $params );
		}
	}
	
	$params[ 'images' ] = $images[ 'images' ];
	
	\delete_option( 'me_task_upload_images_' . $post_id );
	wp_clear_scheduled_hook( 'dm_cron_image_upload_event' );
	wp_schedule_event( time(), '3_min', 'dm_cron_image_upload_event' );
	
	return true;
}
add_action( 'dm_cron_image_upload_event', 'dm_cron_image_upload');