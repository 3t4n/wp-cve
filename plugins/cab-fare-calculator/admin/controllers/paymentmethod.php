<?php
require_once TBLIGHT_PLUGIN_PATH . 'admin/models/paymentmethod.php';
$payment_method_model = new PaymentmethodModel();

$redirect_to_list_page = false;

if ( isset( $_POST['action'] ) && $_POST['action'] == 'save' ) { // FORM SUBMISSION
	$result = handle_form_submission();

	$post_data = sanitize_post( $_POST );
	$id        = $post_data['id'];

	if ( is_wp_error( $result ) ) {
		wp_die( $result->get_error_message() );
	} else {
		$new_item_id = $payment_method_model->store( $post_data );

		if ( $new_item_id > 0 ) {
			wp_redirect( admin_url( 'admin.php?page=paymentmethods&success=1' ) );
			exit;
		}
	}
}

if ( ! empty( $_GET['action'] ) ) {
	$id = ( isset( $_GET['id'] ) ) ? intval( $_GET['id'] ) : 0;

	if ( $_GET['action'] == 'show' ) {
		$item    = $payment_method_model->getItemById( $id );
		$heading = 'Paymentmethod Details';
		if ( ! empty( $item ) ) {
			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/details.php';
		} else {
			$redirect_to_list_page = true;
		}
	} elseif ( $_GET['action'] == 'edit' ) {

		if ( $id == 0 ) { // new item
			$item    = $payment_method_model->getDefaultData();
			$heading = 'Add a new Paymentmethod';

			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/edit.php';
		} else {
			$item = $payment_method_model->getItemById( $id );

			if ( ! empty( $item ) ) {

				$data = json_decode( $item->payment_params );

				$heading = 'Edit Paymentmethod';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/edit.php';
			} else {
				$redirect_to_list_page = true;
			}
		}
	} elseif ( $_GET['action'] == 'status' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_status_paymentmethod' ) ) {
			wp_die( 'Something went wrong!' );
		}

		$payment_method_model->status( $id );

		wp_redirect( admin_url( 'admin.php?page=paymentmethods' ) );
		exit;

	} elseif ( $_GET['action'] == 'delete' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_delete_paymentmethod' ) ) {
			wp_die( 'Something went wrong!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You are not authorized!' );
		}

		$payment_method_model->delete( $id );

		wp_redirect( admin_url( 'admin.php?page=paymentmethods' ) );
		exit;
	}
} else {
	$redirect_to_list_page = true;
}

if ( $redirect_to_list_page ) {
	require_once TBLIGHT_PLUGIN_PATH . 'admin/views/paymentmethods/list.php';
}

function handle_form_submission() {

	// Instantiate the WP_Error object
	$error = new \WP_Error();

	// Make sure user supplies the paymentmethod title
	if ( empty( $_POST['title'] ) ) {
		$error->add( 'empty', 'Title is required' );
	}

	// Send the result
	if ( ! empty( $error->get_error_codes() ) ) {
		return $error;
	}

	// Everything is fine
	return true;
}
