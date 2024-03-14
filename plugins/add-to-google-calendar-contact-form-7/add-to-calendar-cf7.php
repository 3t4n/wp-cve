<?php

/**
 * Plugin Name: Add to Google Calendar - Contact Form 7
 * Description: This plugin provides a “Add to Calendar” button when a form is submitted.
 * Author: Samuel Silva
 * Version: 1.5
 * Author URI: https://samuelsilva.pt/
 */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/


if ( ! defined( 'ABSPATH' ) || ! function_exists( 'add_action' ) ) {
	exit;
}

class AddToCalendarAtccf7 {


	function register() {
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
	}

	function activate() {

	}

	function register_admin_styles() {
		wp_enqueue_style ( 'styles', plugins_url( '/admin/css/styles.css', __FILE__ ) );
	}


}

if ( class_exists( 'AddToCalendarAtccf7' ) ) {
	$AddToCalendarAtccf7 = new AddToCalendarAtccf7();
	$AddToCalendarAtccf7->register();
}


register_activation_hook( __FILE__, array( $AddToCalendarAtccf7, 'activate' ) );

$get_page = ( isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '' );

if ( is_admin() && ( isset( $get_page ) && ( $get_page === 'wpcf7' ||  $get_page === 'wpcf7-new' ) ) ) {
	require_once dirname( __FILE__ ) . '/admin-atc.php';
}


add_action( 'wpcf7_contact_form', 'atccf7_enqueue_scripts', 20, 99 );

function atccf7_enqueue_scripts( $form ) {

	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style( 'atccf7-styles', plugins_url( '/frontend/css/styles.css', __FILE__ ) );

	wp_enqueue_script( 'atccf7-scripts', plugins_url( '/frontend/js/scripts.js', __FILE__ ) );

	$postmeta_temp = get_post_meta( $form->id(), 'atccf7_options_form', true );
	$postmeta_atc  = ( $postmeta_temp !== '' ? $postmeta_temp : false );


	if ( $postmeta_atc ) {
		wp_localize_script( 'atccf7-scripts', 'atccf7_options_form', $postmeta_atc );
	}

}
