<?php

require_once TBLIGHT_PLUGIN_PATH . 'admin/models/order.php';
$order_model = new OrderModel();

$redirect_to_list_page = false;

if ( isset( $_POST['action'] ) && $_POST['action'] == 'save' ) { // FORM SUBMISSION
	$result = handle_form_submission();

	$post_data = sanitize_post( $_POST );
	$id        = $post_data['id'];

	if ( is_wp_error( $result ) ) {
		wp_die( $result->get_error_message() );
	} else {
		$new_item_id = $order_model->store( $post_data );

		if ( $new_item_id > 0 ) {
			wp_redirect( admin_url( 'admin.php?page=orders&action=show&id=' . $new_item_id . '&success=1' ) );
			exit;
		}
	}
}

if ( ! empty( $_GET['action'] ) ) {
	$id = ( isset( $_GET['id'] ) ) ? intval( $_GET['id'] ) : 0;

	if ( $_GET['action'] == 'show' ) {
		$item    = $order_model->getItemById( $id );
		$heading = 'Order Details';
		if ( ! empty( $item ) ) {
			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/orders/details.php';
		} else {
			$redirect_to_list_page = true;
		}
	} elseif ( $_GET['action'] == 'edit' ) {

		if ( $id == 0 ) { // new item
			$item    = $order_model->getDefaultData();
			$heading = 'Add a new Order';

			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/orders/edit.php';
		} else {
			require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';
			$elsettings = BookingHelper::config();

			$item = $order_model->getItemById( $id );

			$item->pickup_date = BookingHelper::date_format( $item->datetime1, 'Y-m-d', $elsettings );
			$item->pickup_hr   = BookingHelper::date_format( $item->datetime1, 'H', $elsettings );
			$item->pickup_min  = BookingHelper::date_format( $item->datetime1, 'i', $elsettings );

			if ( ! empty( $item ) ) {

				$heading = 'Edit Order';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/orders/edit.php';
			} else {
				$redirect_to_list_page = true;
			}
		}
	} elseif ( $_GET['action'] == 'status' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_status_order' ) ) {
			wp_die( 'Something went wrong!' );
		}

		$order_model->status( $id );

		wp_redirect( admin_url( 'admin.php?page=orders' ) );
		exit;
	} elseif ( $_GET['action'] == 'delete' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_delete_order' ) ) {
			wp_die( 'Something went wrong!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You are not authorized!' );
		}

		$order_model->delete( $id );

		wp_redirect( admin_url( 'admin.php?page=orders' ) );
		exit;
	}
} else {
	$redirect_to_list_page = true;
}

if ( $redirect_to_list_page ) {
	require_once TBLIGHT_PLUGIN_PATH . 'admin/views/orders/list.php';
}

function handle_form_submission() {
	// do your validation, nonce and stuffs here

	// Instantiate the WP_Error object
	$error = new \WP_Error();

	// Make sure user supplies the order names
	if ( empty( $_POST['names'] ) ) {
		$error->add( 'empty', 'Name is required' );
	}
	if ( empty( $_POST['email'] ) ) {
		$error->add( 'empty', 'Email is required' );
	}
	if ( empty( $_POST['selpassengers'] ) ) {
		$error->add( 'empty', 'Passenger is required' );
	}

	// Send the result
	if ( ! empty( $error->get_error_codes() ) ) {
		return $error;
	}

	// Everything is fine
	return true;
}
