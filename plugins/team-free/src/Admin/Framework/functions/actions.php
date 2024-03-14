<?php
/**
 *  Framework actions file.
 *
 * @package    team-free
 * @subpackage team-free/framework
 */

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! function_exists( 'sptp_clean_transient' ) ) {
	/**
	 * Sptp clean transient
	 *
	 * @return void
	 */
	function sptp_clean_transient() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'team-free' ) ) );
		}
		// Success.
		global $wpdb;
		$wp_sitemeta = $wpdb->prefix . 'sitemeta';
		$wp_options  = $wpdb->prefix . 'options';
		if ( is_multisite() ) {
			$wpdb->query( "DELETE FROM {$wp_sitemeta} WHERE `meta_key` LIKE ('%\_site_transient_sptp_%')" );//phpcs:ignore
				wp_send_json_success();
		} else {
			$wpdb->query( "DELETE FROM {$wp_options} WHERE `option_name` LIKE ('%\_transient_sptp_%')" ); //phpcs:ignore
				wp_send_json_success();
		}
	}
	add_action( 'wp_ajax_sptp_clean_transient', 'sptp_clean_transient' );
}

if ( ! function_exists( 'spf_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_chosen_ajax() {

		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( $_POST['query_args'] ) : array(); // phpcs:ignore

		if ( ! wp_verify_nonce( $nonce, 'spf_chosen_ajax_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'team-free' ) ) );
		}

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'team-free' ) ) );
		}

		$capability = apply_filters( 'spf_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'team-free' ) ) );
		}

		// Success.
		$options = TEAMFW_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );

	}
	add_action( 'wp_ajax_spf-chosen', 'spf_chosen_ajax' );
}
