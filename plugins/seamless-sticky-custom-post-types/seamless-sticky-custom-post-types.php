<?php
/**
 * Plugin Name: Seamless Sticky Custom Post Types
 * Plugin URI: http://wordpress.org/plugins/seamless-sticky-custom-post-types/
 * Description: Extends the native sticky post functionality to custom post types in a way that is identical to default posts.
 * Author: Jascha Ephraim
 * Version: 1.4
 * Author URI: http://jaschaephraim.com/
 * License: GPL2
 *
 * Copyright 2013  Jascha Ephraim  (email : jascha@jaschaephraim.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

add_action( 'admin_enqueue_scripts', 'sscpt_admin_enqueue_scripts' );
function sscpt_admin_enqueue_scripts() {

	$screen = get_current_screen();

	// Only continue if this is an edit screen for a custom post type
	if ( !in_array( $screen->base, array( 'post', 'edit' ) ) || in_array( $screen->post_type, array( 'post', 'page' ) ) )
		return;

	// Editing an individual custom post
	if ( $screen->base == 'post' ) {
		$is_sticky = is_sticky();
		$js_vars = array(
			'screen' => 'post',
			'is_sticky' => $is_sticky ? 1 : 0,
			'checked_attribute' => checked( $is_sticky, true, false ),
			'label_text' => __( 'Stick this post to the front page' ),
			'sticky_visibility_text' => __( 'Public, Sticky' )
		);

	// Browsing custom posts
	} else {
		global $wpdb;

		$sticky_posts = implode( ', ', array_map( 'absint', ( array ) get_option( 'sticky_posts' ) ) );
		$sticky_count = $sticky_posts
			? $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( 1 ) FROM $wpdb->posts WHERE post_type = %s AND post_status NOT IN ('trash', 'auto-draft') AND ID IN ($sticky_posts)", $screen->post_type ) )
			: 0;

		$js_vars = array(
			'screen' => 'edit',
			'post_type' => $screen->post_type,
			'status_label_text' => __( 'Status' ),
			'label_text' => __( 'Make this post sticky' ),
			'sticky_text' => __( 'Sticky' ),
			'sticky_count' => $sticky_count
		);
	}

	// Enqueue js and pass it specified variables
	wp_enqueue_script(
		'sscpt-admin',
		plugins_url( 'admin.min.js', __FILE__ ),
		array( 'jquery' )
	);
	wp_localize_script( 'sscpt-admin', 'sscpt', $js_vars );

}

?>
