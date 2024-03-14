<?php
/*
Helper functions for Clean Testimonials
*/

function the_client () {

	global $post;
	
	return get_post_meta( $post->ID, 'testimonial_client_name', true );

}

function get_the_client ( $testimonial_id ) {

	return get_post_meta( $testimonial_id, 'testimonial_client_name', true );

}

function the_company () {

	global $post;
	
	return get_post_meta( $post->ID, 'testimonial_client_company', true );

}

function get_the_company ( $testimonial_id ) {

	return get_post_meta( $testimonial_id, 'testimonial_client_company', true );

}

function the_email () {

	global $post;
	
	return get_post_meta( $post->ID, 'testimonial_client_email', true );

}

function get_the_email ( $testimonial_id ) {

	return get_post_meta( $testimonial_id, 'testimonial_client_email', true );

}

function the_website () {

	global $post;
	
	return get_post_meta( $post->ID, 'testimonial_client_website', true );

}

function get_the_website ( $testimonial_id ) {

	return get_post_meta( $testimonial_id, 'testimonial_client_website', true );

}

function testimonial_has_permission( $testimonial_id ) {

	return get_post_meta( $testimonial_id, 'testimonial_client_permission', true ) == 'yes';

}

?>