<?php
/**
 * Plugin Name: Modular Custom CSS
 * Plugin URI: http://celloexpressions.com/plugins/modular-custom-css
 * Description: Manage and live-preview theme-specific and plugin-specific Custom CSS in the Customizer.
 * Version: 2.1
 * Author: Nick Halsey
 * Author URI: http://nick.halsey.co/
 * Tags: Custom CSS, Customizer, Custom Design
 * Text Domain: modular-custom-css
 * License: GPL

=====================================================================================
Copyright (C) 2017 Nick Halsey

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

/**
 * Register the Custom CSS section, settings, and controls.
 *
 * @since Modular Custom CSS 1.0
 */
function cxnh_custom_css_customize_register( $wp_customize ) {
	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		// Note: this is done ASAP regardless of whether the current user can modify CSS.
		$css = get_theme_mod( 'custom_theme_css' );
		if ( $css ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return = wp_update_custom_css_post( $core_css . $css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old plugin theme_mod, so that the CSS is stored in only one place moving forward.
				remove_theme_mod( 'custom_theme_css' );
			}
		}
	} else {
		// Back-compat for WordPress < 4.7.
		// It really doesn't matter if another plugin or the theme adds the same section; they will merge.
		$wp_customize->add_section( 'custom_css', array(
			'title'    => __( 'Custom CSS', 'modular-custom-css' ),
			'priority' => 150, // After all core sections.
		) );

		// 'theme_mod's are stored with the theme, so different themes can have unique custom css rules with no extra effort.
		$wp_customize->add_setting( 'custom_theme_css' , array(
			'type'      => 'theme_mod', // default value.
			'transport' => 'postMessage',
		) );

		// Uses the `textarea` type added in WordPress 4.0.
		$wp_customize->add_control( 'custom_theme_css', array(
			'label'       => __( 'Custom Theme CSS', 'modular-custom-css' ),
			'description' => __( 'Theme-specific; safely sticks with each theme when switching themes.', 'modular-custom-css' ),
			'type'        => 'textarea',
			'section'     => 'custom_css',
		) );
	}

	// 'option's are used regardless of the current theme, so they apply well to plugin-specific styles.
	$wp_customize->add_setting( 'custom_plugin_css' , array(
		'type'       => 'option',
		'transport'  => 'postMessage',
		'capability' => 'edit_css',
	) );

	if ( class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
		$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'custom_plugin_css', array(
			'label'       => __( 'Plugin CSS', 'modular-custom-css' ),
			'description' => __( 'Theme-agnostic; persists across theme changes.', 'modular-custom-css' ),
			'code_type'   => 'text/css',
			'section'     => 'custom_css',
			'priority'    => 20, // After core CSS control.
		) ) );

		$wp_customize->get_control( 'custom_css' )->label = __( 'Theme CSS', 'modular-custom-css' );
		$wp_customize->get_control( 'custom_css' )->description = __( 'Theme-specific; safely sticks with each theme when switching themes.', 'modular-custom-css' );

	} else {

		// Back-compat for WordPress < 4.9
		$wp_customize->add_control( 'custom_plugin_css', array(
			'label'       => __( 'Plugin CSS', 'modular-custom-css' ),
			'description' => __( 'Theme-agnostic; persists across theme changes.', 'modular-custom-css' ),
			'type'        => 'textarea',
			'section'     => 'custom_css',
			'priority'    => 20, // After core CSS control.
		) );
	}
}
add_action( 'customize_register', 'cxnh_custom_css_customize_register', 11 ); // After core controls.

/**
 * Update the custom CSS setting values in real-time by leveraging the Customizer's postMessage API.
 *
 * @since Modular Custom CSS 1.0
 */
function cxnh_custom_css_preview_js() {
	wp_enqueue_script( 'custom_css_preview', plugin_dir_url( __FILE__ ) . 'modular-custom-css-preview.js', array( 'customize-preview' ), '20140804', true );
}
add_action( 'customize_preview_init', 'cxnh_custom_css_preview_js' );

/**
 * Render the custom CSS on the front-end.
 *
 * @since Modular Custom CSS 1.0
 */
function cxnh_custom_css_output() {
	if ( ! function_exists( 'wp_update_custom_css_post' ) || '' !== get_theme_mod( 'custom_theme_css', '' ) ) {
		// If this isn't WordPress 4.7 or higher, or we haven't migrated the theme CSS to the core functionality yet, add the theme styles.
		echo '<style type="text/css" id="custom-theme-css">' . get_theme_mod( 'custom_theme_css', '' ) . '</style>';
	}

	if ( get_option( 'custom_plugin_css' ) || is_customize_preview() ) {
		echo '<style type="text/css" id="custom-plugin-css">' . get_option( 'custom_plugin_css', '' ) . '</style>';
	}
}
add_action( 'wp_head', 'cxnh_custom_css_output');