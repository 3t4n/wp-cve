<?php
/**
 * Plugin Name: Conversion And Remarketing Code
 * Description: Easily place tracking code in the header or footer of your entire site or individual posts/pages.
 * Version: 1.0.1
 * Author: neeaagh
 * Author URI: http://drivenlocal.com
 * License: GPL2
 */

 /*
	Copyright 2015

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("Direct access blocked!");

function add_script_to_head() {
	$post_id = get_the_ID();
	if ( !empty( $post_id ) ) {
    	$header_text = get_post_meta( $post_id, 'script-header-text', true );
    	$sitewide_header_text = get_option( 'sitewide-script-header-text' );
    	if ( !empty( $header_text ) )
    		echo stripslashes( $header_text );
    	if ( !empty( $sitewide_header_text ) )
    		echo stripcslashes( $sitewide_header_text );
	}
}

function add_script_to_footer() {
	$post_id = get_the_ID();
	if ( !empty( $post_id ) ) {
    	$footer_text = get_post_meta( $post_id, 'script-footer-text', true );
    	$sitewide_footer_text = get_option( 'sitewide-script-footer-text' );
    	if ( !empty( $footer_text ) )
    		echo stripslashes( $footer_text );
    	if ( !empty( $sitewide_footer_text ) )
    		echo stripcslashes( $sitewide_footer_text );
	}
}

add_action( 'wp_head', 'add_script_to_head' );
add_action( 'wp_footer', 'add_script_to_footer' );

function add_script_meta_box() {
    $screens = get_post_types( array( 'public' => true, '_builtin' => false ) );
    array_push( $screens, 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
	        'conversion-and-remarketing-meta-box',
	        __( 'Conversion And Remarketing Code' ),
	        'render_script_meta_box',
	        $screen,
	        'normal',
	        'default'
	    );
	}
}

function render_script_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'script_post_nonce' );
	$header_code = esc_attr( get_post_meta( $post->ID, 'script-header-text', true ) );
	$footer_code = esc_attr( get_post_meta( $post->ID, 'script-footer-text', true ) );
	echo '<p>';
	echo '<label for="script-header-text">' . _e( "Code to insert in header", 'example' ) . '</label>';
    echo '<textarea class="widefat" name="script-header-text" id="script-header-text">' . $header_code . '</textarea>';
    echo '</p>';
    echo '<p>';
	echo '<label for="script-footer-text">' . _e( "Code to insert in footer", 'example' ) . '</label>';
    echo '<textarea class="widefat" name="script-footer-text" id="script-footer-text">' . $footer_code . '</textarea>';
    echo '</p>';
}

function save_script_post_meta( $post_id, $post ) {
	if ( !isset( $_POST['script_post_nonce'] ) || !wp_verify_nonce( $_POST['script_post_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	// can user edit ths post type?
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

    $header_script = isset( $_POST['script-header-text'] ) ? $_POST['script-header-text'] : '';
    $footer_script = isset( $_POST['script-footer-text'] ) ? $_POST['script-footer-text'] : '';

    update_post_meta( $post_id, 'script-header-text', $header_script);
    update_post_meta( $post_id, 'script-footer-text', $footer_script);
}

add_action( 'add_meta_boxes', 'add_script_meta_box' );
add_action( 'save_post', 'save_script_post_meta', 10, 2 );

function sitewide_script_menu() {
	add_options_page('Remarketing Code', 'Remarketing Code', 'manage_options', 'remarketing-code', 'sitewide_script_page');
}

function sitewide_script_page() {
	?>
	<div class="wrap">
		<h2>Remarketing Code</h2>
		<p class="description">
			Code entered here will show up in the &lt;head&gt; or footer of every page.
			This is ideal for embedding remarketing and analytics tags. If you'd like to
			embed on specific pages or posts, you must do so from the post's edit page.
		</p>
	</div>

	<form method="post" action="options.php">
    	<?php
    	settings_fields( 'sitewide-script-settings-group' );
    	do_settings_sections( 'sitewide-script-settings-group' );
		$header_code = esc_attr( get_option( 'sitewide-script-header-text' ) );
		$footer_code = esc_attr( get_option( 'sitewide-script-footer-text' ) );
		echo '<p>';
		echo '<label for="sitewide-script-header-text">' . _e( "Code to insert in header", 'example' ) . '</label>';
	    echo '<textarea class="widefat" name="sitewide-script-header-text" id="sitewide-script-header-text">' . $header_code . '</textarea>';
	    echo '</p>';
	    echo '<p>';
		echo '<label for="sitewide-script-footer-text">' . _e( "Code to insert in footer", 'example' ) . '</label>';
	    echo '<textarea class="widefat" name="sitewide-script-footer-text" id="sitewide-script-footer-text">' . $footer_code . '</textarea>';
	    echo '</p>';
		submit_button(); ?>
	</form>
	<?
}

function register_sitewide_script_settings() {
	register_setting( 'sitewide-script-settings-group', 'sitewide-script-header-text' );
	register_setting( 'sitewide-script-settings-group', 'sitewide-script-footer-text' );
}

if ( is_admin() ){
	add_action( 'admin_menu', 'sitewide_script_menu' );
	add_action( 'admin_init', 'register_sitewide_script_settings' );
}

?>
