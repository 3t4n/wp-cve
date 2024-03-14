<?php
/*
Plugin Name:	WPDevDesign - Oxygen - Navigator
Plugin URI:		https://wpdevdesign.com/plugin-oxygen-navigator/
Description:	Adds useful links in the Toolbar for directly editing Pages and Templates when using Oxygen.
Version:		1.0.2
Author:			Sridhar Katakam
Author URI:		https://wpdevdesign.com/
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This plugin. If not, see {URI to Plugin License}.
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'admin_enqueue_scripts', 'wpdd_oxygen_navigator_enqueue_files' );
add_action( 'wp_enqueue_scripts', 'wpdd_oxygen_navigator_enqueue_files' );
/**
 * Loads assets in the WP admin and on the frontend.
 */
function wpdd_oxygen_navigator_enqueue_files() {
	wp_enqueue_style( 'wpdd-oxygen-navigator', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}

add_action( 'admin_bar_menu', 'wpdd_oxygen_navigator_custom_edit_oxygen_templates_pages', 999 );
/**
 * Adds Templates menu item in the WordPress toolbar.
 *
 * @param object $wp_admin_bar WP_Admin_Bar instance, passed by reference.
 */
function wpdd_oxygen_navigator_custom_edit_oxygen_templates_pages( $wp_admin_bar ) {
	// if ( ! is_super_admin()
	// 	 || ! is_object( $wp_admin_bar )
	// 	 || ! function_exists( 'is_admin_bar_showing' )
	// 	 || ! is_admin_bar_showing() ) {
	// 	return;
	// }
	if ( ! function_exists( 'oxygen_vsb_current_user_can_access' ) || ! oxygen_vsb_current_user_can_access() ) {
		return;
	}

	// oxygen icon.
	// $iconhtml = sprintf( '<img src="%s" style="height: 13px; padding-right: 6px;" />', plugins_url( 'assets/img/oxygen-icon.png', __FILE__ ) );
	$iconhtml = sprintf( '<img src="%s" />', plugins_url( 'assets/img/oxygen-icon.png', __FILE__ ) );

	$wp_admin_bar->add_node(
		array(
			'id'    => 'oxy-templates',
			'title' => $iconhtml . __( 'Templates' ),
			'href'  => admin_url( 'edit.php?post_type=ct_template' ),
			'meta'  => array(
				'class' => 'oxy-toolbar-item',
				'title' => __( 'List of Oxygen Templates' ),
			),
		)
	);
	$wp_admin_bar->add_node(
		array(
			'id'    => 'oxy-pages',
			'title' => $iconhtml . __( 'Pages' ),
			'href'  => admin_url( 'edit.php?post_type=page' ),
			'meta'  => array(
				'class' => 'oxy-toolbar-item',
				'title' => __( 'List of WordPress Pages' ),
			),
		)
	); ?>
<?php }

add_action( 'admin_bar_menu', 'wpdd_oxygen_navigator_custom_edit_oxy_templates_submenu', 999 );
/**
 * Adds Oxygen Templates as submenu items to the Templates menu item in the WordPress toolbar.
 *
 * @param object $wp_admin_bar WP_Admin_Bar instance, passed by reference.
 */
function wpdd_oxygen_navigator_custom_edit_oxy_templates_submenu( $wp_admin_bar ) {

	if ( ! function_exists( 'oxygen_vsb_current_user_can_access' ) || ! oxygen_vsb_current_user_can_access() ) {
		return;
	}

	// WP_Query arguments
	$args = array(
		'post_type' => array( 'ct_template' ),
		// 'order' => 'ASC',
		// 'orderby' => 'title',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		foreach ( $query->get_posts() as $p ) {
			$ct_template_type = get_post_meta( $p->ID, 'ct_template_type', true );

			$ct_parent_template = get_post_meta( $p->ID, 'ct_parent_template', true );

			$shortcodes = '';

			if ( $ct_parent_template && $ct_parent_template > 0 ) {
				$shortcodes = get_post_meta( $ct_parent_template, 'ct_builder_shortcodes', true );
			}

			$ct_inner = ( $shortcodes && strpos( $shortcodes, '[ct_inner_content' ) !== false ) ? '&ct_inner=true' : '';

			$edit_url = ct_get_post_builder_link( $p->ID ) . $ct_inner;

			$wp_admin_bar->add_node(
				array(
					'id'    => $p->ID,
					'title' => $p->post_title,
					'parent' => 'oxy-templates',
					'href'  => esc_url( $edit_url ),
					'meta'  => array(
						'title' => __( 'Edit this Template' ),
					),
				)
			);
		} // End foreach().
	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();
}

add_action( 'admin_bar_menu', 'wpdd_oxygen_navigator_custom_edit_wp_pages_submenu', 999 );
/**
 * Adds WordPress Pages as submenu items to the Pages menu item in the WordPress toolbar.
 *
 * @param object $wp_admin_bar WP_Admin_Bar instance, passed by reference
 */
function wpdd_oxygen_navigator_custom_edit_wp_pages_submenu( $wp_admin_bar ) {
	if ( ! function_exists( 'oxygen_vsb_current_user_can_access' ) || ! oxygen_vsb_current_user_can_access() ) {
		return;
	}

	// WP_Query arguments
	$args = array(
		'post_type' => array( 'page' ),
		// 'order' => 'ASC',
		// 'orderby' => 'title',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);

	// The Query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		foreach ( $query->get_posts() as $p ) {
			if ( get_option( 'page_for_posts' ) == $p->ID || get_option( 'page_on_front' ) == $p->ID ) {
				$generic_view = ct_get_archives_template( $p->ID ); // true, for exclude templates of type inner_content

				if ( ! $generic_view ) {  // if not template is set to apply to front page or blog posts page, then use the generic page template, as these are pages
					$generic_view = ct_get_posts_template( $p->ID );
				}
			} else {
				$generic_view = ct_get_posts_template( $p->ID ); // true, exclude templates of type inner_content
			}

			$ct_other_template = get_post_meta( $p->ID, 'ct_other_template', true );

			// check if the other template contains ct_inner_content
			$shortcodes = false;

			if ( $ct_other_template && $ct_other_template > 0 ) {
				$shortcodes = get_post_meta( $ct_other_template, 'ct_builder_shortcodes', true );
			} elseif ( $generic_view && $ct_other_template != -1) {
				$shortcodes = get_post_meta( $generic_view->ID, 'ct_builder_shortcodes', true );
			}

			$ct_inner = ( ( $shortcodes && strpos( $shortcodes, '[ct_inner_content') !== false ) && intval( $ct_other_template ) !== -1 ) ? '&ct_inner=true' : '';

			$edit_url = esc_url( ct_get_post_builder_link( $p->ID ) ) . $ct_inner;

			$wp_admin_bar->add_node(
				array(
					'id'    => $p->ID,
					'title' => $p->post_title,
					'parent' => 'oxy-pages',
					'href'  => $edit_url,
					'meta'  => array(
						'title' => __( 'Edit this Page with Oxygen' ),
					),
				)
			);
		} // End foreach().
	} else {
		// no posts found
	} // End if().

	// Restore original Post Data
	wp_reset_postdata();
}
