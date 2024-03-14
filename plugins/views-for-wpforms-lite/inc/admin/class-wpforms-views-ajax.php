<?php

class WPForms_Views_Ajax {

	function __construct() {
		add_action( 'wp_ajax_views_get_form_fields', array( $this, 'get_form_fields' ) );
		add_action( 'wp_ajax_wpforms_views_get_form_fields', array( $this, 'get_form_fields' ) );

			// Create New View
		add_action( 'wp_ajax_wpf_views_create_view', array( $this, 'create_view' ) );
		// Save View
		add_action( 'wp_ajax_wpforms_save_view', array( $this, 'save_view' ) );
	}

	public function get_form_fields() {
		if ( empty( $_POST['form_id'] ) ) {
			return;
		}

			// Check for permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'views-for-wpforms-lite' ) );
		}

		echo wpforms_views_get_form_fields( sanitize_text_field( $_POST['form_id'] ) );
		wp_die();
	}


	public function create_view() {

		// Run a security check.
		if ( ! check_ajax_referer( 'wpf-views-create', 'create_nonce', false ) ) {
			wp_send_json_error( esc_html__( 'Your session expired. Please reload the page.', 'views-for-wpforms-lite' ) );
		}

		// Check for permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'views-for-wpforms-lite' ) );
		}

		$form_id = sanitize_text_field( $_POST['form_id'] );
		$title   = sanitize_text_field( $_POST['title'] );

		$row_id = 'row' . mt_rand( 100, 99999 );
		$col_id = 'column' . mt_rand( 100, 99999 );
		$view   = array(
			'formId'       => $form_id,
			'title'        => $title,
			'sections'     => array(
				'beforeloop' => array(
					'rows'    => array(),
					'tabname' => 'multipleentries',
					'label'   => 'Before Entry List Fields',
				),
				'loop'       => array(
					'rows'    => array( $row_id ),
					'tabname' => 'multipleentries',
					'label'   => 'Entry List Fields',
				),
				'afterloop'  => array(
					'rows'    => array(),
					'tabname' => 'multipleentries',
					'label'   => 'After Entry List Fields',
				),
				'singleloop' => array(
					'rows'    => array(),
					'tabname' => 'singleEntry',
					'label'   => 'Single Entry Fields',
				),
			),
			'rows'         => array( $row_id => array( 'cols' => array( $col_id ) ) ),
			'columns'      => array(
				$col_id => array(
					'fields' => array(),
					'size'   => '1',
					'title'  => 'Column',
				),
			),
			'fields'       => new stdClass(),
			'activeTab'    => 'multipleentries',
			'viewSettings' => array(
				'multipleentries' => array(
					'perPage'             => 25,
					'approvedSubmissions' => false,
				),
				'singleEntry'     => new stdClass(),
				'filter'          => new stdClass(),
				'sort'            => new stdClass(),
			),
			'viewType'     => 'table',
			'viewTheme'    => 'default',
		);

			// Create post object
			$new_view = array(
				'post_title'   => $title,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'wpforms-views',
			);

			// Insert the post into the database
			$view_id = wp_insert_post( $new_view );

			if ( ! is_wp_error( $view_id ) ) {
				update_post_meta( $view_id, 'view_settings', json_encode( $view ) );
				echo json_encode(
					array(
						'view_id' => $view_id,
						'result'  => 'success',
					)
				);
			} else {
				// there was an error in the post insertion,
				echo json_encode(
					array(
						'result'  => 'error',
						'message' => $view_id->get_error_message(),
					)
				);

			}
			die;
	}


	public function save_view() {

		$view    = $_POST['finaleViewSettings'];
		$view_id = sanitize_text_field( $_POST['_view_id'] );
		$title   = sanitize_text_field( $_POST['title'] );

		// Run a security check.
		if ( ! check_ajax_referer( 'wpf-views-builder', 'nonce', false ) ) {
			wp_send_json_error( esc_html__( 'Your session expired. Please reload the builder.', 'views-for-wpforms-lite' ) );
		}

		// Check for permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action.', 'views-for-wpforms-lite' ) );
		}

		if ( ! empty( $view_id ) ) {
			// update post title
			$post_update = array(
				'ID'         => $view_id,
				'post_title' => $title,
			);

			wp_update_post( $post_update );

			// Save View Settings
			update_post_meta( $view_id, 'view_settings', $view );

		}
		echo 'success';
		die;
	}


}
new WPForms_Views_Ajax();
