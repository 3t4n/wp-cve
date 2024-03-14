<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save admin setting API Endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed bool or void with json output
 */
function church_tithe_wp_save_setting() {

	if ( ! isset( $_GET['church_tithe_wp_save_setting'] ) ) {
		return false;
	}

	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_setting_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_values',
				'details'    => 'Invalid values',
			)
		);
		die();
	}

	// Sanitize the nonce vars.
	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'nonce_failed',
				'details'    => 'Nonce failed',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Make sure we know which setting is being saved.
	if (
		! isset( $_POST['mpwpadmin_setting_id'] ) ||
		! isset( $_POST['mpwpadmin_setting_value'] ) ||
		! isset( $_POST['mpwpadmin_validation_callback'] )
	) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No setting chosen.',
			)
		);

		die();
	}

	// Sanitize the id.
	$id = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_id'] ) );

	// Sanitize the value.
	$value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_value'] ) );

	// Fetch the settings from the database.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Check the validation callback to make sure we should save this.
	$validation_callback = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_validation_callback'] ) );
	$validated_value     = church_tithe_wp_validation_caller( $validation_callback, $value );

	if ( $validated_value['success'] ) {
		// Append it to the settings array.
		$settings[ $id ] = $validated_value['value'];

		update_option( 'church_tithe_wp_settings', $settings );

		echo wp_json_encode(
			array(
				'success'     => true,
				'saved_value' => $validated_value['value'],
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => $validated_value['error_code'],
			)
		);
	}

	die();

}
add_action( 'admin_init', 'church_tithe_wp_save_setting' );

/**
 * Search for currencies that match the search term
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_ajax_get_currencies() {

	if ( ! isset( $_GET['church_tithe_wp_ajax_get_currencies'] ) ) {
		return false;
	}

	$content_type = isset( $_SERVER['CONTENT_TYPE'] ) ? trim( sanitize_text_field( wp_unslash( $_SERVER['CONTENT_TYPE'] ) ) ) : '';

	if ( 'application/json' !== $content_type ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Request was incorrect.' . $content_type,
			)
		);
		die();
	}

	// Receive the RAW post data.
	$content = trim( file_get_contents( 'php://input' ) );

	$_POST = json_decode( $content, true );

	if ( ! isset( $_POST['mpwpadmin_fetch_options_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_fetch_options_nonce'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, 'default_currency' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'nonce_failed',
				'details'    => 'Nonce failed',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'permission_denied',
				'details'    => 'Permission denied',
			)
		);
		die();
	}

	// If json_decode failed, the JSON is invalid.
	if ( ! is_array( $_POST ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'request_must_be_json',
				'details'    => 'Request must be json.',
			)
		);

		die();
	}

	if ( ! isset( $_POST['mpwpadmin_search_term'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'required_values_missing',
				'details'    => 'Required values were missing',
			)
		);
	}

	$search_term = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_search_term'] ) );

	$matching_currencies      = church_tithe_wp_currency_search_results( $search_term );
	$all_available_currencies = church_tithe_wp_get_currencies();

	if ( ! empty( $matching_currencies ) ) {

		echo wp_json_encode(
			array(
				'success'         => true,
				'matching_values' => $matching_currencies,
			)
		);

	} elseif ( empty( $search_term ) ) {

		echo wp_json_encode(
			array(
				'success'         => true,
				'matching_values' => $all_available_currencies,
			)
		);

	} else {
		echo wp_json_encode(
			array(
				'success'         => true,
				'matching_values' => $all_available_currencies,
			)
		);
	}

	die();

}
add_action( 'admin_init', 'church_tithe_wp_ajax_get_currencies' );

/**
 * Upload Media
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_ajax_upload_media() {

	if ( ! isset( $_GET['church_tithe_wp_ajax_upload_media'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_setting_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	if ( ! isset( $_FILES['mpwpadmin_setting_value'] ) ) {

		if ( isset( $_POST['mpwpadmin_setting_value'] ) ) {
			if ( empty( $_POST['mpwpadmin_setting_value'] ) ) {

				// Sanitize the id.
				$id = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_id'] ) );

				// Fetch the settings from the database.
				$settings = get_option( 'church_tithe_wp_settings' );

				// Append the attachment's id to the settings array.
				unset( $settings[ $id ] );

				// Save the settings.
				update_option( 'church_tithe_wp_settings', $settings );

				echo wp_json_encode(
					array(
						'success' => true,
						'details' => 'File successfully removed',
					)
				);

				die();

			}
		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'details' => 'No upload found',
				)
			);

			die();
		}
	};

	if (
		! isset( $_POST['mpwpadmin_validation_callback'] ) ||
		! isset( $_POST['mpwpadmin_setting_id'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	// Store the uploaded file in a variable.
	$uploadedfile = wp_handle_sideload( wp_unslash( $_FILES['mpwpadmin_setting_value'] ) );

	// Check the validation callback to make sure we should save this.
	$validation_callback = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_validation_callback'] ) );
	$validated_value     = church_tithe_wp_validation_caller( $validation_callback, $uploadedfile );

	if ( ! $validated_value['success'] ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => $validated_value['error_code'],
			)
		);
		die();
	}

	// Allow wp_handle_upload to work via fetch/ajax.
	$upload_overrides = array(
		'test_form' => false,
	);

	// Handle the upload using WordPress's wp_handle_upload function.
	$movefile = wp_handle_upload( wp_unslash( $_FILES['mpwpadmin_setting_value'] ), $upload_overrides, false, 'mpwpadmin_media_upload' );

	// If the upload was successful.
	if ( $movefile && ! isset( $movefile['error'] ) ) {

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $movefile['file'],
			'post_mime_type' => $movefile['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $movefile['file'] );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// Sanitize the id.
		$id = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_id'] ) );

		// Fetch the settings from the database.
		$settings = get_option( 'church_tithe_wp_settings' );

		// Append the attachment's id to the settings array.
		$settings[ $id ] = $attach_id;

		// Save the settings.
		update_option( 'church_tithe_wp_settings', $settings );

		echo wp_json_encode(
			array(
				'success'  => true,
				'details'  => 'File was valid and successfully uploaded',
				'file_url' => $movefile['url'],
			)
		);

		die();
	} else {
		/**
		* Error generated by _wp_handle_upload()
		*
		* @see _wp_handle_upload() in wp-admin/includes/file.php
		*/
		echo wp_json_encode(
			array(
				'success'                    => false,
				'details'                    => $movefile['error'],
				'error_code'                 => 'error',
				'unique_instruction_message' => $movefile['error'],
			)
		);

		die();
	}

	echo wp_json_encode(
		array(
			'success'    => false,
			'error_code' => 'error',
		)
	);

	die();

}
add_action( 'admin_init', 'church_tithe_wp_ajax_upload_media' );

/**
 * Get single transaction for admin
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_transaction_admin() {

	if ( ! isset( $_GET['church_tithe_wp_get_transaction_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_list_view_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Make sure we know which setting is being requested.
	if ( ! isset( $_POST['mpwpadmin_list_view_item_id'] ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No item ID requested.',
			)
		);

		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	$transaction_id                       = absint( $_POST['mpwpadmin_list_view_item_id'] );
	$query_args                           = array();
	$query_args['number']                 = 1;
	$query_args['column_values_included'] = array(
		'id'           => $transaction_id,
		// Query test transactions in test mode, and live ones in live mode.
		'is_live_mode' => church_tithe_wp_stripe_is_live_mode() ? 1 : 0,
	);

	$transactions = church_tithe_wp_get_transaction_history_admin( $query_args );

	echo wp_json_encode(
		array(
			'success'             => true,
			'current_single_item' => isset( $transactions['rows'][0] ) ? $transactions['rows'][0] : false,
		)
	);
	die();
}
add_action( 'admin_init', 'church_tithe_wp_get_transaction_admin' );

/**
 * Get a specific arrangement.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_arrangement_admin() {

	if ( ! isset( $_GET['church_tithe_wp_get_arrangement_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_list_view_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Make sure we know which setting is being requested.
	if ( ! isset( $_POST['mpwpadmin_list_view_item_id'] ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No item ID requested.',
			)
		);

		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	$arrangement_id                       = absint( $_POST['mpwpadmin_list_view_item_id'] );
	$query_args                           = array();
	$query_args['number']                 = 1;
	$query_args['column_values_included'] = array(
		'id'           => $arrangement_id,
		// Query test arrangements in test mode, and live ones in live mode.
		'is_live_mode' => church_tithe_wp_stripe_is_live_mode() ? 1 : 0,
	);

	$arrangements = church_tithe_wp_get_arrangement_history_admin( $query_args );

	echo wp_json_encode(
		array(
			'success'             => true,
			'current_single_item' => isset( $arrangements['rows'][0] ) ? $arrangements['rows'][0] : false,
		)
	);
	die();
}
add_action( 'admin_init', 'church_tithe_wp_get_arrangement_admin' );

/**
 * Get Transaction results
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_transaction_history_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_transaction_history_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_list_view_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	if (
		! isset( $_POST['mpwpadmin_list_view_search_term'] ) ||
		! isset( $_POST['mpwpadmin_list_view_page_id'] ) ||
		! isset( $_POST['mpwpadmin_items_per_page'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	// Check if we are scoping to get transactions from a specific arrangement or not.
	if ( isset( $_POST['church_tithe_wp_arrangement_id'] ) ) {
		$arrangement_id = absint( wp_unslash( $_POST['church_tithe_wp_arrangement_id'] ) );
	} else {
		$arrangement_id = false;
	}

	$query_args = array();

	$original_search_term = isset( $_POST['mpwpadmin_list_view_search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_search_term'] ) ) : false;

	// If there was a search term submitted.
	if ( $original_search_term ) {

		$search_term = '';

		// Break the search into terms.
		$search_terms = explode( ' ', $original_search_term );
		foreach ( $search_terms as $original_search_term ) {
			$search_term .= '*' . $original_search_term;
		}

		// Check if a user is returned by the search.
		$users = new WP_User_Query(
			array(
				'search'         => '*' . esc_attr( $search_term ) . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
			)
		);

		$users_found = $users->get_results();

		if ( $users_found ) {

			// Build a new search term containing all the user IDs separated by *.
			foreach ( $users_found as $user ) {
				$search_term .= '*' . $user->ID;
			}
		}

		// Add the search term to the query.
		$query_args['search']         = $search_term;
		$query_args['search_columns'] = array(
			'id',
			'user_id',
			// 'date_created',
			'type',
			// 'gateway',
			'method',
			'page_url',
			'charged_amount',
			'charged_currency',
			// 'home_currency', phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// 'gateway_fee_hc',
			// 'earnings_hc',
			// 'charge_id',
			// 'refund_id',
			'note_with_tithe',
			// 'statement_descriptor', phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// 'arrangement_id',
			// 'payment_intent_id',
		);

	}

	$page_to_retrieve = isset( $_POST['mpwpadmin_list_view_page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_page_id'] ) ) : 1;
	$items_per_page   = isset( $_POST['mpwpadmin_items_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_items_per_page'] ) ) : 20;

	// Add the number of items to get and the offset to the query.
	if ( $page_to_retrieve && $items_per_page ) {

		$offset               = $items_per_page * $page_to_retrieve - $items_per_page;
		$query_args['number'] = $items_per_page;
		$query_args['offset'] = $offset;

	}

	// Query test arrangements in test mode, and live ones in live mode.
	$query_args['column_values_included'] = array(
		'is_live_mode' => church_tithe_wp_stripe_is_live_mode() ? 1 : 0,
	);

	// Add the arrangement specific query.
	if ( ! empty( $arrangement_id ) ) {
		$query_args['column_values_included']['arrangement_id'] = $arrangement_id;
	}

	$tithe_history_columns_to_include = array(
		'id'                     => __( 'ID', 'church-tithe-wp' ),
		'type'                   => __( 'Type', 'church-tithe-wp' ),
		'date_created_list_view' => __( 'Date', 'church-tithe-wp' ),
		'user_list_view'         => __( 'User', 'church-tithe-wp' ),
		'amount'                 => __( 'Amount', 'church-tithe-wp' ),
	);

	$transactions = church_tithe_wp_get_transaction_history_admin( $query_args );

	echo wp_json_encode(
		array(
			'success'     => true,
			'columns'     => $tithe_history_columns_to_include,
			'rows'        => $transactions['rows'],
			'total_items' => $transactions['count'],
		)
	);
	die();
}
add_action( 'admin_init', 'church_tithe_wp_get_transaction_history_endpoint' );

/**
 * Get Tithe History results
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_get_arrangement_history_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_get_arrangement_history_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_list_view_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	if (
		! isset( $_POST['mpwpadmin_list_view_search_term'] ) ||
		! isset( $_POST['mpwpadmin_list_view_page_id'] ) ||
		! isset( $_POST['mpwpadmin_items_per_page'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	$query_args = array();

	$original_search_term = isset( $_POST['mpwpadmin_list_view_search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_search_term'] ) ) : false;

	// If there was a search term submitted.
	if ( $original_search_term ) {

		$search_term = '';

		// Break the search into terms.
		$search_terms = explode( ' ', $original_search_term );
		foreach ( $search_terms as $original_search_term ) {
			$search_term .= '*' . $original_search_term;
		}

		// Check if a user is returned by the search.
		$users = new WP_User_Query(
			array(
				'search'         => '*' . esc_attr( $search_term ) . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
			)
		);

		$users_found = $users->get_results();

		if ( $users_found ) {

			// Build a new search term containing all the user IDs separated by *.
			foreach ( $users_found as $user ) {
				$search_term .= '*' . $user->ID;
			}
		}

		// Add the search term to the query.
		$query_args['search']         = $search_term;
		$query_args['search_columns'] = array(
			'id',
			'user_id',

			// 'date_created', phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// 'initial_transaction_id',

			'interval_count',
			'interval_string',

			'currency',
			'initial_amount',
			'renewal_amount',

			'recurring_status',
			// 'gateway_subscription_id'
		);

	}

	$page_to_retrieve = isset( $_POST['mpwpadmin_list_view_page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_list_view_page_id'] ) ) : 1;
	$items_per_page   = isset( $_POST['mpwpadmin_items_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['mpwpadmin_items_per_page'] ) ) : 20;

	// Add the number of items to get and the offset to the query.
	if ( $page_to_retrieve && $items_per_page ) {

		$offset               = $items_per_page * $page_to_retrieve - $items_per_page;
		$query_args['number'] = $items_per_page;
		$query_args['offset'] = $offset;
	}

	// Query test arrangements in test mode, and live ones in live mode.
	$query_args['column_values_included'] = array(
		'is_live_mode' => church_tithe_wp_stripe_is_live_mode() ? 1 : 0,
	);

	$columns_to_include = array(
		'id'                  => __( 'ID', 'church-tithe-wp' ),
		'user_list_view'      => __( 'User', 'church-tithe-wp' ),
		'amount_per_interval' => __( 'Amount', 'church-tithe-wp' ),
		'status'              => __( 'Status', 'church-tithe-wp' ),
	);

	$arrangements = church_tithe_wp_get_arrangement_history_admin( $query_args );

	echo wp_json_encode(
		array(
			'success'     => true,
			'columns'     => $columns_to_include,
			'rows'        => $arrangements['rows'],
			'total_items' => $arrangements['count'],
		)
	);
	die();
}
add_action( 'admin_init', 'church_tithe_wp_get_arrangement_history_endpoint' );

/**
 * Get Tithe History results
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_stripe_disconnect_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_stripe_disconnect'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) || ! isset( $_POST['mpwpadmin_nonce_id'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce_id'] ) );

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	if ( ! isset( $_POST['mpwpadmin_stripe_disconnect_mode'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	$query_args = array();

	$live_test_mode = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_stripe_disconnect_mode'] ) );

	$result = church_tithe_wp_stripe_disconnect( $live_test_mode );

	if ( is_wp_error( $result ) || isset( $result['error'] ) ) {
		echo wp_json_encode( $result );
		die();
	}

	echo wp_json_encode(
		array(
			'success' => true,
			'result'  => $result,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_stripe_disconnect_endpoint' );

/**
 * Refund a transaction endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_refund_transaction_admin() {

	if ( ! isset( $_GET['church_tithe_wp_refund_transaction_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_nonce_refund_transaction'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_nonce_refund_transaction'] ) );
	$nonce_id    = 'church_tithe_wp_nonce_refund_transaction';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Make sure we know which transaction is being refunded.
	if ( ! isset( $_POST['church_tithe_wp_transaction_being_refunded'] ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No item ID requested.',
			)
		);

		die();
	}

	$transaction_id        = absint( $_POST['church_tithe_wp_transaction_being_refunded'] );
	$transaction_to_refund = new Church_Tithe_WP_Transaction( $transaction_id );

	// If no transaction matched that ID.
	if ( 0 === $transaction_to_refund->id ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'no_transaction_found',
			)
		);
		die();
	}

	if ( empty( $transaction_to_refund->charge_id ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'webhook_failed',
				'details'    => 'The webhook from Stripe did not set a charge ID for this transaction.',
			)
		);
		die();
	}

	// Send a refund request to Stripe for this transaction.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/refunds',
			'fields' => array(
				'charge'                 => $transaction_to_refund->charge_id,
				'refund_application_fee' => 'true',
			),
		)
	);

	// Execute the call to Stripe.
	$refund = $s->call();

	// If the request to refund at Stripe failed.
	if (
		isset( $refund['error'] ) &&
		( isset( $refund['error']['code'] ) && 'charge_already_refunded' !== $refund['error']['code'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => isset( $refund['error']['code'] ) ? $refund['error']['code'] : $refund['error'],
				'details'    => $refund,
			)
		);
		die();
	}

	// The refund transaction and status of the original transaction are updated when the refund webhook comes in at Stripe
	// So we are done for now here.

	// Wait for Sripe webhook for 1 second.
	sleep( 1 );

	$transaction_to_refund = new Church_Tithe_WP_Transaction( $transaction_id );

	// If the webhook has not come through yet, return pending.
	if ( ! $transaction_to_refund->refund_id ) {
		echo wp_json_encode(
			array(
				'success' => true,
				'pending' => true,
			)
		);
		die();
	}

	// Return success, the transaction is fully refunded and the webhook has come through.
	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_refund_transaction_admin' );

/**
 * Refund a transaction endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_cancel_arrangement_admin() {

	if ( ! isset( $_GET['church_tithe_wp_cancel_arrangement_admin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_nonce_cancel_arrangement'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_nonce_cancel_arrangement'] ) );
	$nonce_id    = 'church_tithe_wp_nonce_cancel_arrangement';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Make sure we know which arrangement is being cancelled.
	if ( ! isset( $_POST['church_tithe_wp_arrangement_being_cancelled'] ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No item ID requested.',
			)
		);

		die();
	}

	$arrangement_id        = absint( $_POST['church_tithe_wp_arrangement_being_cancelled'] );
	$arrangement_to_cancel = new Church_Tithe_WP_Arrangement( $arrangement_id );

	// If no arrangement matched that ID.
	if ( 0 === $arrangement_to_cancel->id ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'no_arrangement_found',
			)
		);
		die();
	}

	// Send a cancellation request to Stripe for this arrangement.
	$cancellation_result = church_tithe_wp_cancel_stripe_subscription( $arrangement_to_cancel, 'cancelled_by_admin' );

	$arrangement = new Church_Tithe_WP_Arrangement( $arrangement_id );

	if ( ! $cancellation_result['success'] ) {
		echo wp_json_encode(
			array(
				'success'          => false,
				'details'          => $cancellation_result['details'],
				'arrangement_info' => array(
					'arrangement_id'                  => $arrangement->id,
					'arrangement_date_created'        => $arrangement->date_created,
					'arrangement_amount_per_interval' => church_tithe_wp_get_visible_amount( $arrangement->renewal_amount, $arrangement->currency ) . ' ' . __( 'per', 'church-tithe-wp' ) . ' ' . $arrangement->interval_string,
					'recurring_status'                => $arrangement->recurring_status,
				),
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'success'          => true,
				'arrangement_info' => array(
					'arrangement_id'                  => $arrangement->id,
					'arrangement_date_created'        => $arrangement->date_created,
					'arrangement_amount_per_interval' => church_tithe_wp_get_visible_amount( $arrangement->renewal_amount, $arrangement->currency ) . ' ' . __( 'per', 'church-tithe-wp' ) . ' ' . $arrangement->interval_string,
					'recurring_status'                => $arrangement->recurring_status,
				),
			)
		);
	}

	die();

}
add_action( 'admin_init', 'church_tithe_wp_cancel_arrangement_admin' );

/**
 * Set the Stripe Connect Success Redirect URL.
 * This is used to determine if we should redirect back to a health check popup,
 * or to the setting page itself.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_set_ctwp_scsr_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_set_ctwp_scsr'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_set_ctwp_scsr_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_set_ctwp_scsr_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_set_ctwp_scsr';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Figure out which redirect mode we are handling, which health check lightbox to redirect to after a successful Stripe Connect.
	if ( ! isset( $_POST['church_tithe_wp_set_ctwp_scsr'] ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'No mode included.',
			)
		);

		die();
	}

	if (
		! isset( $_POST['church_tithe_wp_set_ctwp_scsr'] ) ||
		! isset( $_POST['church_tithe_wp_lightbox_suffix'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	$redirect_mode = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_set_ctwp_scsr'] ) );
	$suffix        = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_lightbox_suffix'] ) );

	// Save the redirect temporarily so it gets used when we return from Stripe.
	if ( 'apple_pay' === $redirect_mode ) {
		update_option( 'ctwp_scsr', admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=apple_pay' . $suffix ) );
		$mode_is = $redirect_mode . $suffix;
	} elseif ( 'stripe_live_mode' === $redirect_mode ) {
		update_option( 'ctwp_scsr', admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=stripe_live_mode' . $suffix ) );
		$mode_is = $redirect_mode . $suffix;
	} elseif ( 'stripe_test_mode' === $redirect_mode ) {
		update_option( 'ctwp_scsr', admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=stripe_test_mode' . $suffix ) );
		$mode_is = $redirect_mode . $suffix;
	} else {
		update_option( 'ctwp_scsr', false );
		delete_option( 'ctwp_scsr' );
		$mode_is = 'default';
	}

	echo wp_json_encode(
		array(
			'success' => true,
			'mode_id' => $mode_is,
		)
	);

	die();

}
add_action( 'admin_init', 'church_tithe_wp_set_ctwp_scsr_endpoint' );

/**
 * Get fresh settings for the entire mpwpadmin single page application
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_refresh_mpwpadmin_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_refresh_mpwpadmin'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_refresh_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_refresh_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_refresh_mpwpadmin';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	$church_tithe_wp_views_and_settings = church_tithe_wp_get_views_and_settings();

	echo wp_json_encode(
		array(
			'success' => true,
			'data'    => $church_tithe_wp_views_and_settings,
		)
	);

	die();

}
add_action( 'admin_init', 'church_tithe_wp_refresh_mpwpadmin_endpoint' );

/**
 * This endpoint sends a test email with a token for confirming it.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_send_test_email_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_send_test_email'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_send_test_email_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_send_test_email_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_send_test_email';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'permissions',
				'details'    => 'Permission denied',
			)
		);
		die();
	}

	if (
		! isset( $_POST['church_tithe_wp_email'] ) ||
		! isset( $_POST['church_tithe_wp_lightbox_suffix'] )
	) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);

		die();
	}

	// Sanitize the email.
	$email = sanitize_email( wp_unslash( $_POST['church_tithe_wp_email'] ) );

	// If this is not a valid email address.
	if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_email',
				'details'    => 'Invalid email address provided.',
			)
		);
	}

	$suffix = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_lightbox_suffix'] ) );

	// Set up the subject line.
	// translators: The url of this website.
	$subject = sprintf( __( 'Email test from %s', 'church-tithe-wp' ), get_bloginfo( 'url' ) );

	// Create the email token.
	$one_time_email_token = wp_generate_password( 64, false );

	// Add the token to the URL we want to return the user to.
	$one_time_email_token_link = admin_url() . '?ctwp_wp_mail_health_check_response=' . $one_time_email_token;

	// Store the email token and save it as a transient. This token is deleted within 1 hour.
	set_transient( 'church_tithe_wp_mail_health_check_token', $one_time_email_token, HOUR_IN_SECONDS );
	set_transient( 'church_tithe_wp_mail_health_check_success_redirect', admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=wp_mail' . $suffix ), HOUR_IN_SECONDS );

	// translators: The name of the current website.
	$line_1  = '<p>' . sprintf( __( 'This email is a test to confirm whether emails are working on %s. Click the link below to confirm the email: ', 'church-tithe-wp' ), get_bloginfo( 'name' ) ) . '</p><p>';
	$line_2  = '<a href="' . $one_time_email_token_link . '">' . $one_time_email_token_link . '</a></p>';
	$message = $line_1 . $line_2;

	$email_from = get_bloginfo( 'admin_email' );

	$email_headers = array(
		'Content-Type: text/html; charset=UTF-8',
		// 'From: ' . get_bloginfo( 'name' ) . ' <' . $email_from . '>',
	);

	// Attempt to send the email using wp_mail.
	$send_result = wp_mail( $email, $subject, $message, $email_headers );

	// If the email did not send.
	if ( ! $send_result ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'wp_mail_false',
				'details'    => 'The email could not be sent.',
			)
		);

		// Set Church Tithe WP to know that emails are not working.
		church_tithe_wp_unconfirm_wp_mail_health_check();

		die();
	}

	// Since we are confirming that emails work here, let's unconfirm that they are for now.
	church_tithe_wp_unconfirm_wp_mail_health_check();

	// If the email was sent, we will return true, but wait for the token in the email to be clicked.
	echo wp_json_encode(
		array(
			'success' => true,
		)
	);

	die();

}
add_action( 'admin_init', 'church_tithe_wp_send_test_email_endpoint' );

/**
 * This endpoint is where the Test email for the wp_mail health check links back to.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_email_confirmation_wp_mail_health_check_endpoint() {

	// If this page load is not a response from the email.
	// Nonce verification is knowingly ignored here. The email token handles that. If no token exists+matches, no action is taken.
	if ( ! isset( $_GET['ctwp_wp_mail_health_check_response'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return false;
	}

	// Nonce verification is knowingly ignored here. The email token handles that. If no token exists+matches, no action is taken.
	$url_email_token = sanitize_text_field( wp_unslash( $_GET['ctwp_wp_mail_health_check_response'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

	/* // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// If logged out, redirec to the login, then back here
	// Leaving this here commented out as evidence of what has been considered.
	// Being logged in is not required because the token takes care of that.
	// If the token fails, we know something's wrong, so we do nothing.
	if ( ! is_user_logged_in() ) {
		wp_redirect( get_bloginfo( 'url' ) . '/wp-login.php?redirect_to=' . admin_url() . '/?ctwp_wp_mail_health_check_response=' . $url_email_token );
		die();
	}
	// Verify the user has permission to do this
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode( array(
			'success' => false,
			'error_code' => 'permissions',
			'details' => 'Permission denied'
		) );
		die();
	}
	*/

	// Get the current email token.
	$saved_email_token = get_transient( 'church_tithe_wp_mail_health_check_token' );

	// If there is no saved URL token at this time.
	if ( ! $saved_email_token ) {

		// Redirect to the mpwpadmin with a failure.
		wp_safe_redirect( admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=wp_mail_health_check&church_tithe_wp_mail_health_check_token_failed=1' ) );
		die();
	}

	// Verify the email token.
	if ( $url_email_token !== $saved_email_token ) {

		// Redirect to the mpwpadmin with a failure note.
		wp_safe_redirect( admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=wp_mail_health_check&church_tithe_wp_mail_health_check_token_failed=1' ) );
		exit;
	}

	// The tokens match! The email is confirmed. Delete the saved token.
	delete_transient( 'church_tithe_wp_mail_health_check_token' );

	$success_redirect = get_transient( 'church_tithe_wp_mail_health_check_success_redirect' );
	delete_transient( 'church_tithe_wp_mail_health_check_success_redirect' );

	// Get the saved options for Church Tithe WP.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Set the wp_mail check to be true, confirmed.
	$settings['wp_mail_confirmed'] = true;

	// Save the options.
	update_option( 'church_tithe_wp_settings', $settings );

	// If they are already logged in, they are likely confirming on the same device where it was requested (ie not on their phone).
	if ( is_user_logged_in() ) {
		// Redirect to the mpwpadmin.
		wp_safe_redirect( $success_redirect );
		exit;

		// if they are not logged in, they probably confirmed the email on a different device, like their phone. So just show a success message.
	} else {
		echo '<h1>' . esc_textarea( __( 'Email Confirmed! Return to the set-up to continue.', 'church-tithe-wp' ) ) . '</h1>';
	}

	die();

}
add_action( 'admin_init', 'church_tithe_wp_email_confirmation_wp_mail_health_check_endpoint' );

/**
 * This endpoint will tell Church Tithe WP that a test email was not received, deleting the flag and any outstanding transient tokens.
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_reset_wp_mail_health_check_endpoint() {

	// If this page load is not a response from the email.
	if ( ! isset( $_GET['church_tithe_wp_reset_wp_mail_health_check'] ) ) {
		return false;
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'permissions',
				'details'    => 'Permission denied',
			)
		);
		die();
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_reset_wp_mail_health_check_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_reset_wp_mail_health_check_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_reset_wp_mail_health_check';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Kill any active email tokens. The test email was not received.
	delete_transient( 'church_tithe_wp_mail_health_check_token' );

	// Set Church Tithe WP to know that emails are not working.
	church_tithe_wp_unconfirm_wp_mail_health_check();

	// The flag was successfuly set to false, and the active email token removed.
	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_reset_wp_mail_health_check_endpoint' );

/**
 * Install the SendGrid plugin via this endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_install_sendgrid_endpoint() {

	// If this page load is not a response from the email.
	if ( ! isset( $_GET['church_tithe_wp_install_sendgrid'] ) ) {
		return false;
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'permissions',
				'details'    => 'Permission denied',
			)
		);
		die();
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_install_sendgrid_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_install_sendgrid_nonce'] ) );
	$nonce_id    = 'install_sendgrid_nonce';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	/** If plugins_api isn't available, load the file that holds the function */
	if ( ! function_exists( 'plugins_api' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	// Get the SendGrid information from the WordPress Repo.
	$args = array( 'slug' => 'sendgrid-email-delivery-simplified' );
	$api  = plugins_api( 'plugin_information', $args );

	// Get credentials for wp filesystem.
	$url   = wp_nonce_url( 'options-general.php' );
	$creds = request_filesystem_credentials( $url, '', false, false );
	if ( false === $creds ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'filesystem_failed',
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// Now we have some credentials, try to get the wp_filesystem running.
	if ( ! WP_Filesystem( $creds ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'filesystem_failed',
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// By this point, the $wp_filesystem global should be working, so let's use it get our plugin.
	global $wp_filesystem;

	// Get the plugins directory and name the temp plugin file.
	$upload_dir = $wp_filesystem->wp_plugins_dir();
	$filename   = trailingslashit( $upload_dir ) . 'temp.zip';

	// Download the plugin file defined in the passed in array.
	$saved_file = $wp_filesystem->get_contents( esc_url_raw( add_query_arg( array( 'site_activating' => get_bloginfo( 'wpurl' ) ), $api->download_link ) ) );

	// If the file the came back was blank, try getting it another way.
	if ( empty( $saved_file ) ) {
		$saved_file = wp_remote_retrieve_body( wp_remote_get( esc_url_raw( add_query_arg( array( 'site_activating' => get_bloginfo( 'wpurl' ) ), $api->download_link ) ) ) );
	}

	// If the file still came back empty, try without using SSL.
	if ( empty( $saved_file ) ) {
		$plugin_download_link = str_replace( 'https', 'http', $api->download_link );
		$saved_file           = wp_remote_retrieve_body( wp_remote_get( esc_url_raw( add_query_arg( array( 'site_activating' => get_bloginfo( 'wpurl' ) ), $plugin_download_link ) ) ) );

		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'ssl_connections_not allowed',
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// If it's still empty.
	if ( empty( $saved_file ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'unable_to_download_zip',
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// Save the contents into a temp.zip file (string stored in $filename).
	if ( ! $wp_filesystem->put_contents( $filename, $saved_file, FS_CHMOD_FILE ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'unable_to_create_zip',
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// Unzip the temp zip file.
	$unzip_result = unzip_file( $filename, trailingslashit( $upload_dir ) . '/' );

	// If there was a problem unzipping the file.
	if ( is_wp_error( $unzip_result ) ) {

		$zip = new ZipArchive();
		if ( $zip->open( $filename ) === true ) {
			$zip->extractTo( trailingslashit( $upload_dir ) );
			$zip->close();
		} else {
			echo wp_json_encode(
				array(
					'success'    => false,
					'error_code' => 'webhost_has_improper_temp_dir',
					'details'    => 'Unable to install SendGrid plugin.',
				)
			);
			die();
		}
	}

	// Delete the temp zipped file.
	$wp_filesystem->rmdir( $filename );

	// Set plugin cache to NULL so activate_plugin->validate_plugin->get_plugins will check again for new plugins.
	wp_cache_set( 'plugins', null, 'plugins' );

	// Activate plugin.
	$result = activate_plugin( trailingslashit( $upload_dir ) . 'sendgrid-email-delivery-simplified/wpsendgrid.php' );

	// If there was a problem activating the plugin.
	if ( is_wp_error( $result ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => $result->get_error_code(),
				'details'    => 'Unable to install SendGrid plugin.',
			)
		);
		die();
	}

	// Delete the email token so that the process can be restarted from scratch.
	delete_transient( 'church_tithe_wp_mail_health_check_token' );

	// Delete the sendgrid API key so it can be re-entered.
	delete_option( 'sendgrid_api_key' );

	// If we activated the plugin and it's all good.
	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_install_sendgrid_endpoint' );

/**
 * Save a SendGrid API Key
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_save_sendgrid_api_key_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_save_sendgrid_api_key'] ) ) {
		return false;
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'permissions',
				'details'    => 'Permission denied',
			)
		);
		die();
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['mpwpadmin_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_save_sendgrid_api_key';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Make sure we know which setting is being saved.
	if (
		! isset( $_POST['mpwpadmin_setting_id'] ) ||
		! isset( $_POST['mpwpadmin_setting_value'] ) ||
		'church_tithe_wp_save_sendgrid_api_key' !== $_POST['mpwpadmin_setting_id']
	) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Invalid settings',
			)
		);
		die();
	}

	// Sanitize the value.
	$value = sanitize_text_field( wp_unslash( $_POST['mpwpadmin_setting_value'] ) );

	// Check the validation callback to make sure we should save this.
	$validated_value = church_tithe_wp_validate_sendgrid_api_key( $value );

	if ( $validated_value['success'] ) {

		// Save the sendgrid API key to its setting in the options table.
		update_option( 'sendgrid_api_key', $validated_value['value'] );

		echo wp_json_encode(
			array(
				'success'     => true,
				'saved_value' => $validated_value['value'],
			)
		);
	} else {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => $validated_value['error_code'],
			)
		);
	}

	die();

}
add_action( 'admin_init', 'church_tithe_wp_save_sendgrid_api_key_endpoint' );

/**
 * Complete the onboarding wizard for tithe jar wp via this endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_complete_wizard_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_complete_wizard'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_complete_wizard_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_complete_wizard_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_complete_wizard';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Set the wizard to be completed.
	update_option( 'church_tithe_wp_wizard_status', 'completed' );

	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_complete_wizard_endpoint' );

/**
 * Set the onboarding wizard for tithe jar wp to be "in progress" via this endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_start_wizard_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_start_wizard'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_start_wizard_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_start_wizard_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_start_wizard';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Set the onboarding wizard as in progress.
	update_option( 'church_tithe_wp_wizard_status', 'in_progress' );

	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_start_wizard_endpoint' );

/**
 * Set the onboarding wizard for tithe jar wp to be "in progress" via this endpoint
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_wizard_later_endpoint() {

	if ( ! isset( $_GET['church_tithe_wp_wizard_later'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_start_wizard_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_start_wizard_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_wizard_later';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Set the onboarding wizard as 'later'.
	update_option( 'church_tithe_wp_wizard_status', 'later' );

	echo wp_json_encode(
		array(
			'success' => true,
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_wizard_later_endpoint' );

/**
 * Endpoint which changes your WordPress site prefix from "http" to "https"
 *
 * @access   public
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_update_wordpress_url() {

	if ( ! isset( $_GET['church_tithe_wp_update_wordpress_url'] ) ) {
		return false;
	}

	// Verify the nonce values exist.
	if ( ! isset( $_POST['church_tithe_wp_update_wordpress_url_nonce'] ) ) {
		echo wp_json_encode(
			array(
				'success'    => false,
				'error_code' => 'invalid_request',
				'details'    => 'Invalid request',
			)
		);
		die();
	}

	$nonce_value = sanitize_text_field( wp_unslash( $_POST['church_tithe_wp_update_wordpress_url_nonce'] ) );
	$nonce_id    = 'church_tithe_wp_update_wordpress_url_nonce';

	// Verify the nonce.
	if ( ! wp_verify_nonce( $nonce_value, $nonce_id ) ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Nonce failed. ',
			)
		);
		die();
	}

	// Verify the user has permission to do this.
	if ( ! current_user_can( 'update_plugins' ) ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Permission denied',
			)
		);
		die();
	}

	// Determine if changing "http" to "https" is possible, because an SSL certificate is installed.
	if ( ! church_tithe_wp_is_site_reachable_over_ssl() ) {
		echo wp_json_encode(
			array(
				'success' => false,
				'details' => 'Site was not reachable over https. ',
			)
		);
		die();
	}

	$current_wordpress_address = get_option( 'home' );
	$current_site_address      = get_option( 'siteurl' );

	$https_wordpress_address = str_replace( 'http://', 'https://', $current_wordpress_address );
	$https_site_address      = str_replace( 'http://', 'https://', $current_site_address );

	// Update the addresses with https.
	update_option( 'home', $https_wordpress_address );
	update_option( 'siteurl', $https_site_address );

	echo wp_json_encode(
		array(
			'success'   => true,
			'https_url' => admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=welcome&mpwpadmin_lightbox=ssl_wizard_step' ),
		)
	);
	die();

}
add_action( 'admin_init', 'church_tithe_wp_update_wordpress_url' );
