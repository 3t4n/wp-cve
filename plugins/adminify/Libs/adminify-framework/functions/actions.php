<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'adminify_get_icons' ) ) {
	function adminify_get_icons() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'adminify_icon_nonce' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid nonce verification.', 'adminify' ) ] );
		}

		ob_start();

		$icon_library = ( apply_filters( 'adminify_fa4', false ) ) ? 'fa4' : 'fa5';

		ADMINIFY::include_plugin_file( 'fields/icon/' . $icon_library . '-icons.php' );

		$icon_lists = apply_filters( 'adminify_field_icon_add_icons', adminify_get_default_icons() );

		if ( ! empty( $icon_lists ) ) {
			foreach ( $icon_lists as $list ) {
				echo ( count( $icon_lists ) >= 2 ) ? '<div class="adminify-icon-title">' . esc_attr( $list['title'] ) . '</div>' : '';

				foreach ( $list['icons'] as $icon ) {
					echo '<i title="' . esc_attr( $icon ) . '" class="' . esc_attr( $icon ) . '"></i>';
				}
			}
		} else {
				echo '<div class="adminify-error-text">' . esc_html__( 'No data available.', 'adminify' ) . '</div>';
		}

		$content = ob_get_clean();

		wp_send_json_success( [ 'content' => $content ] );
	}
	add_action( 'wp_ajax_adminify-get-icons', 'adminify_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'adminify_export' ) ) {
	function adminify_export() {
		$nonce  = ( ! empty( $_GET['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
		$unique = ( ! empty( $_GET['unique'] ) ) ? sanitize_text_field( wp_unslash( $_GET['unique'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'adminify_backup_nonce' ) ) {
			die( esc_html__( 'Error: Invalid nonce verification.', 'adminify' ) );
		}

		if ( empty( $unique ) ) {
			die( esc_html__( 'Error: Invalid key.', 'adminify' ) );
		}

		// Export
		header( 'Content-Type: application/json' );
		header( 'Content-disposition: attachment; filename=backup-' . gmdate( 'd-m-Y' ) . '.json' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		echo json_encode( get_option( $unique ) );

		die();
	}
	add_action( 'wp_ajax_adminify-export', 'adminify_export' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'adminify_import_ajax' ) ) {
	function adminify_import_ajax() {
		if ( ! empty( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
		} else {
			$nonce = '';
		}

		if ( ! empty( $_POST['unique'] ) ) {
			$unique = sanitize_text_field( wp_unslash( $_POST['unique'] ) );
		} else {
			$unique = '';
		}

		if ( ! empty( $_POST['data'] ) ) {
			// sanitized after decode
			$data = wp_kses_post_deep( json_decode( wp_unslash( $_POST['data'] ), true ) );
		} else {
			$data = [];
		}

		if ( ! wp_verify_nonce( $nonce, 'adminify_backup_nonce' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid nonce verification.', 'adminify' ) ] );
		}

		if ( empty( $unique ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid key.', 'adminify' ) ] );
		}

		if ( empty( $data ) || ! is_array( $data ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'adminify' ) ] );
		}

		// Success
		update_option( $unique, $data );

		wp_send_json_success();
	}
	add_action( 'wp_ajax_adminify-import', 'adminify_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'adminify_reset_ajax' ) ) {
	function adminify_reset_ajax() {
		$nonce  = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$unique = ( ! empty( $_POST['unique'] ) ) ? sanitize_text_field( wp_unslash( $_POST['unique'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'adminify_backup_nonce' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid nonce verification.', 'adminify' ) ] );
		}

		// Success
		delete_option( $unique );

		wp_send_json_success();
	}
	add_action( 'wp_ajax_adminify-reset', 'adminify_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'adminify_chosen_ajax' ) ) {
	function adminify_chosen_ajax() {
		if ( ! empty( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
		} else {
			$nonce = '';
		}

		if ( ! empty( $_POST['type'] ) ) {
			$type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
		} else {
			$type = '';
		}

		if ( ! empty( $_POST['term'] ) ) {
			$term = sanitize_text_field( wp_unslash( $_POST['term'] ) );
		} else {
			$term = '';
		}

		if ( ! empty( $_POST['query_args'] ) ) {
			$query = sanitize_text_field( wp_unslash( $_POST['query_args'] ) );
		} else {
			$query = [];
		}

		if ( ! wp_verify_nonce( $nonce, 'adminify_chosen_ajax_nonce' ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid nonce verification.', 'adminify' ) ] );
		}

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: Invalid term ID.', 'adminify' ) ] );
		}

		$capability = apply_filters( 'adminify_chosen_ajax_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( [ 'error' => esc_html__( 'Error: You do not have permission to do that.', 'adminify' ) ] );
		}

		// Success
		$options = ADMINIFY_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );
	}
	add_action( 'wp_ajax_adminify-chosen', 'adminify_chosen_ajax' );
}
