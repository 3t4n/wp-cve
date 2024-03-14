<?php

require_once TBLIGHT_PLUGIN_PATH . 'admin/models/config.php';
$config_model = new ConfigModel();

$redirect_to_list_page = false;

if ( isset( $_POST['action'] ) && $_POST['action'] == 'save' ) { // FORM SUBMISSION
	$result = handle_form_submission();

	$post_data = sanitize_post( $_POST );
	$id        = $post_data['id'];

	if ( is_wp_error( $result ) ) {
		wp_die( $result->get_error_message() );
	} else {
		// echo "<pre>"; print_r($_POST); die();
		$new_item_id = $config_model->store( $post_data );

		if ( $new_item_id > 0 ) {
			wp_redirect( admin_url( 'admin.php?page=configs&success=1' ) );
			exit;
		}
	}
}

if ( ! empty( $_GET['action'] ) ) {
	$id = ( isset( $_GET['id'] ) ) ? intval( $_GET['id'] ) : 0;

	if ( $_GET['action'] == 'show' ) {
		$item    = $config_model->getItemById( $id );
		$heading = 'Config Details';
		if ( ! empty( $item ) ) {
			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/details.php';
		} else {
			$redirect_to_list_page = true;
		}
	} elseif ( $_GET['action'] == 'edit' ) {

		if ( $id == 0 ) { // new item
			$item    = $config_model->getDefaultData();
			$heading = 'Add a new Config';

			require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit.php';
		} elseif ( $id == 1 ) { // general settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'General Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_general.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 2 ) { // price settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'General Price Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_price.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 3 ) { // map settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'Map Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_map.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 4 ) { // base settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'Base Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_base.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 5 ) { // orderemail settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'Order Email Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_orderemail.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 6 ) { // terms settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'Terms Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_terms.php';
			} else {
				$redirect_to_list_page = true;
			}
		} elseif ( $id == 7 ) { // design settings
			$item = $config_model->getItemById( $id );
			$data = json_decode( $item->text );
			// echo "<pre>"; print_r($data); echo "</pre>";
			if ( ! empty( $item ) ) {

				$heading = 'Design Settings';
				require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/edit_design.php';
			} else {
				$redirect_to_list_page = true;
			}
		}
	} elseif ( $_GET['action'] == 'delete' ) {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'tblight_delete_config' ) ) {
			wp_die( 'Something went wrong!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You are not authorized!' );
		}

		$config_model->delete( $id );

		wp_redirect( admin_url( 'admin.php?page=configs' ) );
		exit;
	}
} else {
	$redirect_to_list_page = true;
}

if ( $redirect_to_list_page ) {
	require_once TBLIGHT_PLUGIN_PATH . 'admin/views/configs/list.php';
}

function handle_form_submission() {
	// do your validation, nonce and stuffs here

	// Instantiate the WP_Error object
	$error = new \WP_Error();

	// Make sure user supplies the config title
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
