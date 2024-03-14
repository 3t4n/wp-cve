<?php

defined( 'ABSPATH' ) || exit();

/**
 * Handle user entries from Help Dialog Submissions
 */
class EPHD_Contact_Form_Ctrl {

	public function __construct() {

		/**
		 * Submissions
		 */
		// Delete all submissions
		add_action( 'wp_ajax_ephd_submissions_delete_all', array( $this, 'delete_all_submissions' ) );
		add_action( 'wp_ajax_nopriv_ephd_submissions_delete_all', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Load more submissions
		add_action( 'wp_ajax_ephd_submissions_load_more', array( $this, 'load_more_submissions' ) );
		add_action( 'wp_ajax_nopriv_ephd_submissions_load_more', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Save Contact Form Settings
		add_action( 'wp_ajax_ephd_save_contact_form_settings', array( $this, 'save_contact_form_settings' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_contact_form_settings', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		/**
		 * Designs
		 */
		// Save Contact Form
		/* TODO future add_action( 'wp_ajax_ephd_save_contact_form', array( $this, 'save_contact_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_contact_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Delete Contact Form
		add_action( 'wp_ajax_ephd_delete_contact_form', array( $this, 'delete_contact_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_delete_contact_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Load Contact Form
		add_action( 'wp_ajax_ephd_load_contact_form', array( $this, 'load_contact_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_load_contact_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Load full preview box for Contact Form
		add_action( 'wp_ajax_ephd_load_contact_form_preview', array( $this, 'load_contact_form_preview' ) );
		add_action( 'wp_ajax_nopriv_ephd_load_contact_form_preview', array( 'EPHD_Utilities', 'user_not_logged_in' ) ); */
	}

	/**
	 * Save Contact Form Settings
	 */

	public function save_contact_form_settings() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 45 ) );
		}

		// retrieve Global configuration specs
		$global_config_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		// retrieve and validate contact form submission email; type needs to be text
		$global_config['contact_submission_email'] = EPHD_Utilities::post( 'contact_submission_email', $global_config['contact_submission_email'], 'text', intval( $global_config_specs['contact_submission_email']['max'] ) );
		if ( ! empty( $global_config['contact_submission_email'] ) && ! is_email( $global_config['contact_submission_email'] ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'Please enter a valid email address.', 'help-dialog' ) );
		}

		// save Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving global configuration. (46)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 46, $updated_global_config ) );
		}

		wp_die( wp_json_encode( array(
			'status'  => 'success',
			'message' => esc_html__( 'Configuration Saved', 'help-dialog' ),
		) ) );
	}

	/**
	 * Delete all Submissions
	 */
	public function delete_all_submissions() {

		// wp_die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// remove the submission
		$handler = new EPHD_Submissions_DB();
		$result = $handler->delete_all_submissions();
		if ( empty( $result ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 205 ) );
		}

		wp_die( wp_json_encode( array( 'status' => 'success', 'message' => esc_html__( 'All submissions removed.', 'help-dialog') ) ) );
	}

	/**
	 * Load more Submissions
	 */
	public function load_more_submissions() {

		// wp_die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		$page_number = (int)EPHD_Utilities::post( 'page_number', 1 );

		$handler = new EPHD_Submissions_DB();
		$submissions = $handler->get_submissions( $page_number );
		if ( is_wp_error( $submissions ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 210, $submissions ) );
		}

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => esc_html__( 'Submission loaded.', 'help-dialog'),
			'per_page'      => EPHD_Submissions_DB::PER_PAGE,
			'total_number'  => $handler->get_total_number_of_submissions(),
			'items'         => EPHD_HTML_Forms::get_html_table_rows(
									$submissions,
									EPHD_Submissions_DB::PRIMARY_KEY,
									EPHD_Submissions_DB::get_submission_column_fields(),
									EPHD_Submissions_DB::get_submission_row_fields(),
									EPHD_Submissions_DB::get_submission_optional_row_fields(),
									count( EPHD_Submissions_DB::get_submission_column_fields() ) + 1
							)
		) ) );
	}

	/**
	 * Save Contact Form
	 */
	/* FUTURE code
	public function save_contact_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Contact Form data from user input
		$contact_form = self::get_sanitized_contact_form_from_input();

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 215 ) );
		}

		// CREATE: generate new Contact Form if required
		if ( $contact_form['contact_form_id'] == 0 ) {
			$global_config['last_contact_form_id']++;
			$contact_form['contact_form_id'] = $global_config['last_contact_form_id'];
			$contact_forms_config[$contact_form['contact_form_id']] = array_merge( $contact_forms_config[EPHD_Config_Specs::DEFAULT_ID], $contact_form );

		// UPDATE: we can update only existing Contact Form
		} else {
			if ( ! isset( $contact_forms_config[$contact_form['contact_form_id']] ) ) {
				EPHD_Logging::add_log( 'FAQs does not exist (316)' );
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 216 ) );
			}
			$contact_forms_config[$contact_form['contact_form_id']] = array_merge( $contact_forms_config[$contact_form['contact_form_id']], $contact_form );
		}

		// SAVE: Contact Forms configuration
		// update last Contact Form id in Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global configuration. (218)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 218, $updated_global_config ) );
		}

		$contact_form_page_handler = EPHD_Contact_Form_Display::get_contact_form_page_handler( $contact_forms_config );
		if ( is_wp_error( $contact_form_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 219 ) );
		}

		// pass into JS updated form for the Contact Form Design
		$design_form_config = array(
			'class'         => 'ephd-cf__design-form ephd-cf__design-form--active',
			'html'          => $contact_form_page_handler->get_design_form_box_html( $updated_contact_form ),
			'return_html'   => true,
		);

		// pass into JS updated preview box for the Contact Form Design
		$design_preview_config = $contact_form_page_handler->get_config_of_contact_form_preview_box( $updated_contact_form, true, false );

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => esc_html__( 'Design Saved', 'help-dialog' ),
			'design_form'       => EPHD_HTML_Forms::admin_settings_box( $design_form_config ),
			'design_preview'    => EPHD_HTML_Forms::admin_settings_box( $design_preview_config ),
		) ) );
	}
	*/

	/**
	 * Delete Contact Form
	 */
	/* Future code
	public function delete_contact_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve configuration for all Contact Forms

		// retrieve Contact Form id (can delete only existing Contact Form)
		$contact_form_id = (int)EPHD_Utilities::post( 'contact_form_id' );
		if ( empty( $contact_form_id ) || ! isset( $contact_forms_config[$contact_form_id] ) ) {
			EPHD_Logging::add_log( 'Contact Form does not exist (221)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 221, $contact_forms_config ) );
		}

		// remove Contact Form
		unset( $contact_forms_config[$contact_form_id] );

		// update Contact Form configuration

		// retrieve Widgets configuration
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Logging::add_log( 'Failed to retrieve Widgets configuration (223)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 223, $widgets_config ) );
		}

		// use default Contact Form in those Widgets which use currently deleting Contact Form
		foreach ( $widgets_config as $widget_id => $widget ) {
			if ( $widget['contact_form_id'] == $contact_form_id ) {
				$widgets_config[$widget_id]['contact_form_id'] = EPHD_Config_Specs::DEFAULT_ID;
			}
		}

		// save Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving widgets configuration. (224)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 224, $updated_widgets_config ) );
		}

		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Contact Form Design Removed', 'help-dialog' ),
		) ) );
	}
	*/
	/**
	 * Return form HTML to create new or edit existing Contact form
	 */
	/* FUTURE code
	public function load_contact_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Contact Form id (0 if it is needed to create a new Contact Form)
		$contact_form_id = (int)EPHD_Utilities::post( 'contact_form_id', -1 );
		if ( $contact_form_id < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 225 ) );
		}

		// retrieve all Contact Forms configuration

		// retrieve current Contact Form configuration
		if ( isset( $contact_forms_config[$contact_form_id] ) ) {
			$contact_form = $contact_forms_config[$contact_form_id];

		// or use default to create a new one
		} else {
			$contact_form = $contact_forms_config[EPHD_Config_Specs::DEFAULT_ID];
			$contact_form['contact_form_id'] = 0;
		}

		$contact_form_page_handler = EPHD_Contact_Form_Display::get_contact_form_page_handler( $contact_forms_config );
		if ( is_wp_error( $contact_form_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 227 ) );
		}

		// pass into JS updated form for the Contact Form Design
		$design_form_config = array(
			'class'         => 'ephd-cf__design-form ephd-cf__design-form--active' . ( isset( $contact_forms_config[$contact_form_id] ) ? '' : ' ephd-cf__new-design-form' ),
			'html'          => $contact_form_page_handler->get_design_form_box_html( $contact_form ),
			'return_html'   => true,
		);

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'design_form'   => EPHD_HTML_Forms::admin_settings_box( $design_form_config ),
		) ) );
	}
	*/

	/**
	 * Return sanitized Contact Form data from request data
	 *
	 * @return array
	 */
	/* FTUTURE code
	private static function get_sanitized_contact_form_from_input() {

		$contact_form = [];

		// retrieve Contact Form id (0 if it is needed to create a new Contact Form)
		$contact_form['contact_form_id'] = (int)EPHD_Utilities::post( 'contact_form_id', -1 );
		if ( $contact_form['contact_form_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 230 ) );
		}

		// retrieve Contact Form name, it cannot be empty
		$contact_form['contact_form_name'] = EPHD_Utilities::post( 'contact_form_name' );
		if ( empty( $contact_form['contact_form_name'] ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'Design name cannot be empty.', 'help-dialog' ) );
		}

		$contact_form['contact_welcome_title'] = EPHD_Utilities::post( 'contact_welcome_title' );
		if ( empty( $contact_form['contact_welcome_title'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 231 ) );
		}

		$contact_form['contact_welcome_text'] = EPHD_Utilities::post( 'contact_welcome_text' );
		if ( empty( $contact_form['contact_welcome_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 239 ) );
		}

		$contact_form['contact_name_text'] = EPHD_Utilities::post( 'contact_name_text' );
		if ( empty( $contact_form['contact_name_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 232 ) );
		}

		$contact_form['contact_user_email_text'] = EPHD_Utilities::post( 'contact_user_email_text' );
		if ( empty( $contact_form['contact_user_email_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 233 ) );
		}

		$contact_form['contact_subject_text'] = EPHD_Utilities::post( 'contact_subject_text' );
		if ( empty( $contact_form['contact_subject_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 234 ) );
		}

		$contact_form['contact_comment_text'] = EPHD_Utilities::post( 'contact_comment_text' );
		if ( empty( $contact_form['contact_comment_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 235 ) );
		}

		$contact_form['contact_acceptance_text'] = EPHD_Utilities::post( 'contact_acceptance_text' );
		if ( empty( $contact_form['contact_acceptance_text'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 236 ) );
		}

		$contact_form['contact_button_title'] = EPHD_Utilities::post( 'contact_button_title' );
		if ( empty( $contact_form['contact_button_title'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 237 ) );
		}

		$contact_form['contact_success_message'] = EPHD_Utilities::post( 'contact_success_message' );
		if ( empty( $contact_form['contact_success_message'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 238 ) );
		}

		return $contact_form;
	}
	*/
	/**
	 * Return full HTML of Contact Form preview box
	 */
	/*
	public function load_contact_form_preview() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve configuration for all Contact Forms

		// retrieve Contact Form id (allow load preview only for existing Contact Forms)
		$contact_form_id = (int)EPHD_Utilities::post( 'contact_form_id' );
		if ( empty( $contact_form_id ) || ! isset( $contact_forms_config[$contact_form_id] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 241 ) );
		}

		$contact_form_page_handler = EPHD_Contact_Form_Display::get_contact_form_page_handler( $contact_forms_config );
		if ( is_wp_error( $contact_form_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 242 ) );
		}

		// pass into JS updated preview box for the Contact Form Design
		$design_preview_config = $contact_form_page_handler->get_config_of_contact_form_preview_box( $contact_forms_config[$contact_form_id], true, true, false );

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => 'success',
			'design_preview'    => EPHD_HTML_Forms::admin_settings_box( $design_preview_config ),
		) ) );
	}
	*/
}
