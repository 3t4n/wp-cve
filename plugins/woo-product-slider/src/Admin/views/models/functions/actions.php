<?php
/**
 * The admin actions functionality of the plugin.
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

use ShapedPlugin\WooProductSlider\Admin\views\models\classes\SPF_WPSP;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! function_exists( 'spwps_get_icons' ) ) {
	/**
	 *
	 * Get icons from admin ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spwps_get_icons() {
		$capability        = apply_filters( 'sp_wps_shortcodes_ui_permission', 'manage_options' );
		$sp_wps_is_capable = current_user_can( $capability ) ? true : false;
		$nonce             = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spwps_icon_nonce' ) || ! $sp_wps_is_capable ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'woo-product-slider' ) ) );
		}

		ob_start();

		$icon_library = ( apply_filters( 'spwps_fa4', false ) ) ? 'fa4' : 'fa5';

		SPF_WPSP::include_plugin_file( 'fields/icon/' . $icon_library . '-icons.php' );

		$icon_lists = apply_filters( 'spwps_field_icon_add_icons', array() );

		if ( ! empty( $icon_lists ) ) {

			foreach ( $icon_lists as $list ) {

				echo ( count( $icon_lists ) >= 2 ) ? '<div class="spwps-icon-title">' . esc_attr( $list['title'] ) . '</div>' : '';

				foreach ( $list['icons'] as $icon ) {
					echo '<i title="' . esc_attr( $icon ) . '" class="' . esc_attr( $icon ) . '"></i>';
				}
			}
		} else {
				echo '<div class="spwps-error-text">' . esc_html__( 'No data available.', 'woo-product-slider' ) . '</div>';
		}

		$content = ob_get_clean();

		wp_send_json_success( array( 'content' => $content ) );

	}
	add_action( 'wp_ajax_spwps-get-icons', 'spwps_get_icons' );
}

if ( ! function_exists( 'spwps_reset_ajax' ) ) {

	/**
	 *
	 * Reset Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spwps_reset_ajax() {
		$capability      = apply_filters( 'sp_wps_shortcodes_ui_permission', 'manage_options' );
		$is_user_capable = current_user_can( $capability ) ? true : false;
		$nonce           = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'spwps_backup_nonce' ) || ! $is_user_capable ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'woo-product-slider' ) ) );
		}
		$unique = ( ! empty( $_POST['unique'] ) ) ? sanitize_text_field( wp_unslash( $_POST['unique'] ) ) : '';
		// Success.
		delete_option( $unique );

		wp_send_json_success();

	}
	add_action( 'wp_ajax_spwps-reset', 'spwps_reset_ajax' );
}


if ( ! function_exists( 'spwps_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spwps_chosen_ajax() {
		$capability      = apply_filters( 'sp_wps_shortcodes_ui_permission', 'manage_options' );
		$is_user_capable = current_user_can( $capability ) ? true : false;
		$nonce           = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'spwps_chosen_ajax_nonce' ) || ! $is_user_capable ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'woo-product-slider' ) ) );
		}

		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( $_POST['query_args'] ) : array(); // phpcs:ignore

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'woo-product-slider' ) ) );
		}

		$capability = apply_filters( 'spwps_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'woo-product-slider' ) ) );
		}

		// Success.
		$options = SPF_WPSP_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );

	}
	add_action( 'wp_ajax_spwps-chosen', 'spwps_chosen_ajax' );
}
