<?php
/**
 * Handle permalink functions
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'upstream_register_permalink_settings' );
add_action( 'admin_init', 'upstream_validate_permalink_settings' );

/**
 * Returns the permalink base for projects and client.
 *
 * @param string $base Base name.
 *
 * @return string|false
 */
function upstream_get_permalink_base( $base ) {
	if ( ! in_array( $base, array( 'projects', 'client' ) ) ) {
		return false;
	}

	$default = trim( sanitize_title( apply_filters( 'upstream_' . $base . '_base', $base ) ) );

	$base = trim( sanitize_title( get_option( 'upstream_' . $base . '_base', $default ) ) );

	if ( empty( $base ) ) {
		$base = $default;
	}

	return $base;
}

/**
 * Returns the project base segment for permalinks.
 *
 * @return mixed
 */
function upstream_get_project_base() {
	return upstream_get_permalink_base( 'projects' );
}

/**
 * Upstream_is_project_base_uri
 *
 * @param string $uri URI.
 */
function upstream_is_project_base_uri( $uri ) {
	$pb = upstream_get_project_base();

	if ( '/' . $pb === $uri ) {
		return true;
	} elseif ( '/' . $pb . '/' === $uri ) {
		return true;
	} elseif ( preg_match( '/^\/' . $pb . '\?/i', $uri ) ) {
		return true;
	}

	return false;
}

/**
 * Returns the client base segment for permalinks.
 *
 * @return mixed
 */
function upstream_get_client_base() {
	return upstream_get_permalink_base( 'client' );
}

/**
 * Register settings for the permalink.
 */
function upstream_register_permalink_settings() {
	/*
	 * Section
	 */
	add_settings_section(
		'upstream',
		__( 'UpStream Settings', 'upstream' ),
		'upstream_permalink_settings_section',
		'permalink'
	);

	/*
	 * Fields
	 */
	add_settings_field(
		'upstream_projects_permalink',
		__( 'Projects base', 'upstream' ),
		'upstream_print_project_permalink_field',
		'permalink',
		'upstream'
	);

	add_settings_field(
		'upstream_client_permalink',
		__( 'Client base', 'upstream' ),
		'upstream_print_client_permalink_field',
		'permalink',
		'upstream'
	);
}

/**
 * Prints the field for the projects' permalink base.
 */
function upstream_print_project_permalink_field() {
	$value   = esc_attr( get_option( 'upstream_projects_base', '' ) );
	$default = esc_attr( apply_filters( 'upstream_projects_base', 'projects' ) );

	echo '<input name="upstream_projects_base" id="upstream_projects_base" type="text" class="regular-text code" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $default ) . '">';
}

/**
 * Prints the field for the client's permalink base.
 */
function upstream_print_client_permalink_field() {
	$value   = esc_attr( get_option( 'upstream_client_base', '' ) );
	$default = esc_attr( apply_filters( 'upstream_client_base', 'client' ) );

	echo '<input name="upstream_client_base" id="upstream_client_base" type="text" class="regular-text code" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $default ) . '">';
}

/**
 * Validates and save permalink settings.
 */
function upstream_validate_permalink_settings() {
	$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

	if ( ! array_key_exists( 'permalink_structure', $post_data ) ) {
		return;
	}

	if ( ! array_key_exists( 'upstream_nonce', $post_data ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $post_data['upstream_nonce'] ), 'upstream_permalink_settings' ) ) {
		return;
	}

	if ( array_key_exists( 'upstream_projects_base', $post_data ) ) {
		$option = sanitize_title( $post_data['upstream_projects_base'] );
		update_option( 'upstream_projects_base', $option );
	}

	if ( array_key_exists( 'upstream_client_base', $post_data ) ) {
		$option = sanitize_title( $post_data['upstream_client_base'] );
		update_option( 'upstream_client_base', $option );
	}
}

/**
 * Prints the output for the permalink section.
 */
function upstream_permalink_settings_section() {
	wp_nonce_field( 'upstream_permalink_settings', 'upstream_nonce' );
}
