<?php

require_once TBLIGHT_PLUGIN_PATH . 'admin/models/car.php';
$car_model = new CarModel();

$redirect_to_list_page = false;

if ( isset( $_POST['action'] ) && $_POST['action'] == 'save' ) { // FORM SUBMISSION
	$result = handle_form_submission();

	$post_data = sanitize_post( $_POST );
	$id        = $post_data['id'];

	if ( is_wp_error( $result ) ) {
		wp_die( $result->get_error_message() );
	} else {
		$new_item_id = $car_model->store( $post_data );

		if ( $new_item_id > 0 ) {
			wp_redirect( admin_url( 'admin.php?page=cars&action=show&id=' . $new_item_id . '&success=1' ) );
			exit;
		}
	}
}

if ( ! empty( $_GET['action'] ) ) {
	$id = ( isset( $_GET['id'] ) ) ? intval( $_GET['id'] ) : 0;

	if ( $_GET['action'] == 'show' ) {
		$item    = $car_model->getItemById( $id );
		$heading = 'Car Details';
		if ( ! empty( $item ) ) {
			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/cars/details.php';
		} else {
			$redirect_to_list_page = true;
		}
	} elseif ( $_GET['action'] == 'edit' ) {

		if ( $id == 0 ) { // new item
			$item    = $car_model->getDefaultData();
			$heading = 'Add a new Car';

			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/cars/edit.php';
		} else {
			$item = $car_model->getItemById( $id );

			if ( ! empty( $item ) ) {

				$heading = 'Edit Car';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/cars/edit.php';
			} else {
				$redirect_to_list_page = true;
			}
		}
	} elseif ( $_GET['action'] == 'status' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_status_car' ) ) {
			wp_die( 'Something went wrong!' );
		}

		$car_model->status( $id );

		// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		// add_query_arg() return the current url
		wp_redirect( admin_url( 'admin.php?page=cars' ) );
		exit;

	} elseif ( $_GET['action'] == 'delete' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_delete_car' ) ) {
			wp_die( 'Something went wrong!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You are not authorized!' );
		}

		$car_model->delete( $id );

		wp_redirect( admin_url( 'admin.php?page=cars' ) );
		exit;
	}
} else {
	$redirect_to_list_page = true;
}

if ( $redirect_to_list_page ) {
	require_once TBLIGHT_PLUGIN_PATH . 'admin/views/cars/list.php';
}

function handle_form_submission() {
	// do your validation, nonce and stuffs here

	// Instantiate the WP_Error object
	$error = new \WP_Error();

	// Make sure user supplies the car title
	if ( empty( $_POST['title'] ) ) {
		$error->add( 'empty', 'Title is required' );
	}

	if ( empty( $_POST['passenger_no'] ) || $_POST['passenger_no'] == 0 ) {
		$error->add( 'empty', 'Maximum Passengers is required' );
	}

	if ( empty( $_POST['unit_price'] ) || $_POST['unit_price'] == 0 ) {
		$error->add( 'empty', 'Price per Mile is required' );
	}

	// Send the result
	if ( ! empty( $error->get_error_codes() ) ) {
		return $error;
	}

	// Everything is fine
	return true;
}
