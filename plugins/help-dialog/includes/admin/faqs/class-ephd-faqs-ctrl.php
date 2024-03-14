<?php

defined( 'ABSPATH' ) || exit();

/**
 * Handle user submission from Help dialog FAQs
 */
class EPHD_FAQs_Ctrl {

	public function __construct() {

		add_action( 'wp_ajax_ephd_save_question_data', array( $this, 'save_question_data' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_question_data', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
		
		add_action( 'wp_ajax_ephd_get_question_data', array( $this, 'get_question_data' ) );
		add_action( 'wp_ajax_nopriv_ephd_get_question_data', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_delete_question', array( $this, 'delete_question' ) );
		add_action( 'wp_ajax_nopriv_ephd_delete_question', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_save_faqs', array( $this, 'save_faqs' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_faqs', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_load_faqs_form', array( $this, 'load_faqs_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_load_faqs_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_update_faqs_preview', array( $this, 'update_faqs_preview' ) );
		add_action( 'wp_ajax_nopriv_ephd_update_faqs_preview', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * Edit Question dialog: user added a new question or updated existing one.
	 */
	public function save_question_data() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$question_languages = EPHD_Utilities::post( 'question_languages', [] );
		if ( empty( $question_languages ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 300 ) );
		}

		$direction = EPHD_Utilities::post( 'direction' );

		$faqs_db_handler = new EPHD_FAQs_DB();

		// Retrieve faq_id (if question already exist with any language)
		$faq_id = (int)EPHD_Utilities::post( 'question_faq_id', 0 );

		$faq_status = 'publish';
		$questions_response_esc = [];
		$current_language = EPHD_Multilang_Utilities::get_current_language();
		foreach ( $question_languages as $lang ) {

			$question = stripslashes( EPHD_Utilities::post( 'question_title_' . $lang ) );
			if ( empty( $question ) ) {
				continue;
			}

			$answer = stripslashes( wpautop( EPHD_Utilities::post( 'question_content_' . $lang, '', 'wp_editor' ) ) );
			$id = (int)EPHD_Utilities::post( 'question_id_' . $lang );

			// create new post
			if ( empty( $id ) ) {
				$faq = $faqs_db_handler->insert_faq( 0, $faq_id, $question, $answer, $faq_status, $lang );
				if ( empty( $faq ) || is_wp_error( $faq ) ) {
					EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 301 ) );
				}

				// if faq_id is empty - retrieve it from the last inserted FAQ (in case of creating a new multilingual FAQ)
				if ( empty( $faq_id ) && isset( $faq->faq_id ) ) {
					$faq_id = $faq->faq_id;
				}

			// update existing post
			} else {
				$faq = $faqs_db_handler->update_faq( $id, $question, $answer, $faq_status );
				if ( is_wp_error( $faq ) || empty( $faq ) ) {
					EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 302, $faq ) );
				}
			}

			// return HTML only for the current language
			if ( $lang != $current_language ) {
				continue;
			}

			ob_start();

			EPHD_FAQs_Page::display_single_faq( array(
				'container_ID' => $faq->faq_id,
				'name'         => $faq->question,
				'modified'     => strtotime( $faq->date_modified ),
				'direction'    => $direction
			) );

			$html = ob_get_clean();

			$questions_response_esc[] = [
				'id'     => esc_attr( $faq->id ),
				'faq_id' => esc_attr( $faq->faq_id ),
				'title'  => esc_attr( $faq->question ),
				'html'   => wp_kses_post( $html )
			];
		}

		wp_die( wp_json_encode( array(
			'status'  => 'success',
			'message' => esc_html__( 'Question Saved', 'help-dialog' ) ,
			'data'    => $questions_response_esc
		) ) );
	}

	/**
	 * Retrieve question to show to user for edit in the dialog
	 */
	public function get_question_data() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$question_id = (int)EPHD_Utilities::post( 'question_id' );

		$faqs_db_handler = new EPHD_FAQs_DB();

		$question_all_lang = $faqs_db_handler->get_faq_with_all_lang_by_id( $question_id );
		if ( is_wp_error( $question_all_lang ) || empty( $question_all_lang ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 303 ) );
		}

		$questions_esc = [];
		foreach ( $question_all_lang as $question ) {
			$questions_esc[$question->lang] = [
				'title'    => esc_attr( $question->question ),
				'content'  => wp_kses_post( $question->answer ),
				'id'       => $question->id,
				'faq_id' => $question->faq_id
			];
		}

		// if there no questions
		if ( empty( $questions_esc ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 305 ) );
		}

		wp_die( wp_json_encode( array(
			'status'   => 'success',
			'message'  => '',
			'data'     => $questions_esc,
		    'language' => esc_attr( EPHD_Multilang_Utilities::get_current_language('') )
		) ) );
	}
	
	/**
	 * Delete Question. All FAQs will be updated. It will be in the trash just in case 30 days, but user can't know this.
	 */
	public function delete_question() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$faq_id = (int) EPHD_Utilities::post( 'faq_id' );
		if ( ! $faq_id ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 310 ) );
		}

		$faqs_db_handler = new EPHD_FAQs_DB();
		$result = $faqs_db_handler->get_faq_with_all_lang_by_id( $faq_id );
		if ( is_wp_error( $result ) || empty( $result ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 311 ) );
		}

		if ( empty( $faqs_db_handler->delete_faq_by_id( $faq_id ) ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 312 ) );
		}

		// retrieve current FAQs configuration
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 313, $widgets_config ) );
		}

		// update FAQs configuration: delete the current Question's id from sequence
		$do_update_config = false;
		foreach ( $widgets_config as $index => $widget) {
			foreach ( $widget['faqs_sequence'] as $question_order => $question_id ) {
				if ( $question_id == $faq_id ) {
					unset( $widgets_config[$index]['faqs_sequence'][ $question_order ] );
					$do_update_config = true;
				}
			}
		}

		if ( $do_update_config ) {
			$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
			if ( is_wp_error( $updated_widgets_config ) ) {
				EPHD_Logging::add_log( 'Error occurred on saving FAQs configuration. (318)' );
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 318, $updated_widgets_config ) );
			}
		}

		// pass into JS updated form for the FAQs
		$faqs_page_handler = new EPHD_FAQs_Page( $widgets_config );

		// pass into JS updated preview box for the FAQs
		$faqs_preview = array();
		foreach ( $widgets_config as $widget_config ) {
			$questions = $faqs_page_handler->get_faqs_questions( $widget_config );
			$faqs_preview_config = array(
				'class'         => 'ephd-admin__item-preview ephd-admin__item-preview--' . $widget_config['widget_id'],
				'return_html'   => true,
				'html'          => EPHD_HTML_Admin::get_item_preview_box( $widget_config, array(
					'key'                   => 'widget',
					'sub_items_list'        => $questions,
					'sub_items_title'       => __( 'Questions', 'help-dialog' ),
					'icon_html'             => EPHD_FAQs_Page::get_faqs_icon_html(),
					'bottom_items_title'    => __( 'Used on', 'help-dialog' ) ) ) );

			$faqs_preview[$widget_config['widget_id']] = EPHD_HTML_Forms::admin_settings_box( $faqs_preview_config );
		}

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => esc_html__( 'Question Deleted', 'help-dialog' ),
			'faqs_preview'  => $faqs_preview
		) ) );
	}

	/**
	 * Save FAQs
	 */
	public function save_faqs() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve FAQs id from request (0 if it is needed to create a new FAQs)
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget_id < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 330 ) );
		}

		// retrieve FAQs name, it cannot be empty
		$faqs_name = 'Widget #' . $widget_id;
		/* $faqs_name = EPHD_Utilities::post( 'faqs_name' );
		if ( empty( $faqs_name ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'FAQs topic cannot be empty.', 'help-dialog' ) );
		} */

		// retrieve sequence of faqs ids from request
		$faqs_sequence = EPHD_Utilities::post( 'faqs_sequence', [] );
		if ( ! is_array( $faqs_sequence ) || empty( $faqs_sequence ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'FAQs should contain at least one question.', 'help-dialog' ) );
		}

		// retrieve all FAQs configuration
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 315, $widgets_config ) );
		}

		$widgets_config[$widget_id]['faqs_name'] = $faqs_name;
		$widgets_config[$widget_id]['faqs_sequence'] = $faqs_sequence;

		// SAVE: FAQs configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving FAQs configuration. (318)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 318, $updated_widgets_config ) );
		}
		$updated_widget = $updated_widgets_config[$widget_id];

		// pass into JS updated form for the FAQs
		$faqs_page_handler = new EPHD_FAQs_Page( $widgets_config );

		$faqs_form_config = array(
			'class'         => 'ephd-fp__faqs-form ephd-fp__faqs-form--active',
			'html'          => $faqs_page_handler->get_faqs_form_box_html( $updated_widget ),
			'return_html'   => true,
		);

		// pass into JS updated preview box for the FAQs
		$questions = $faqs_page_handler->get_faqs_questions( $updated_widget );
		$faqs_preview_config = array(
			'class'         => 'ephd-admin__item-preview ephd-admin__item-preview--' . $updated_widget['widget_id'],
			'return_html'   => true,
			'html'          => EPHD_HTML_Admin::get_item_preview_box( $updated_widget, array(
				'key'                   => 'widget',
				'sub_items_list'        => $questions,
				'sub_items_title'       => __( 'Questions', 'help-dialog' ),
				'icon_html'             => EPHD_FAQs_Page::get_faqs_icon_html(),
				'bottom_items_title'    => __( 'Used on', 'help-dialog' ) ) ) );

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => esc_html__( 'Configuration Saved', 'help-dialog' ),
			'faqs_form'     => EPHD_HTML_Forms::admin_settings_box( $faqs_form_config ),
			'faqs_preview'  => EPHD_HTML_Forms::admin_settings_box( $faqs_preview_config ),
		) ) );
	}

	/**
	 * Return form HTML to create new or edit existing FAQs
	 */
	public function load_faqs_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve FAQs id from request (0 if it is needed to create a new FAQs)
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget_id < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 330 ) );
		}

		// retrieve configuration for all FAQs
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 331, $widgets_config ) );
		}

		// retrieve current FAQs configuration
		if ( isset( $widgets_config[$widget_id] ) ) {
			$widget = $widgets_config[$widget_id];

		// or use default to create a new one
		} else {
			$widget = $widgets_config[EPHD_Config_Specs::DEFAULT_ID];
			$widget['faqs_sequence'] = [];

			// retrieve new one faqs name
			$faqs_name = 'Widget #' . $widget_id;
			/* $faqs_name = EPHD_Utilities::post( 'faqs_name', $widgets_config[EPHD_Config_Specs::DEFAULT_ID]['faqs_name'] );
			if ( empty( $faqs_name ) ) {
				EPHD_Utilities::ajax_show_error_die( __( 'FAQs name cannot be empty.', 'help-dialog' ) );
			} */
			$widget['faqs_name'] = $faqs_name;
		}

		// pass into JS the new FAQs as a new settings box
		$faqs_page_handler = new EPHD_FAQs_Page( $widgets_config );
		$faqs_form_config = array(
			'class'         => 'ephd-fp__faqs-form ephd-fp__faqs-form--active' . ( isset( $widgets_config[$widget_id] ) ? '' : ' ephd-fp__new-faqs-form' ),
			'html'          => $faqs_page_handler->get_faqs_form_box_html( $widget ),
			'return_html'   => true,
		);

		$hd_handler = new EPHD_Help_Dialog_View( $widget, true, true );

		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => 'success',
			'faqs_form' => EPHD_HTML_Forms::admin_settings_box( $faqs_form_config ),
			'preview'   => $hd_handler->output_help_dialog( true )
		) ) );
	}

	/**
	 * Update FAQs WIDGET preview - nothing is saving here
	 */
	public function update_faqs_preview() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve Widget id (0 if it is needed to create a new Widget)
		$widget['widget_id'] = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget['widget_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 70 ) );
		}

		// retrieve faqs_sequence
		$widget['faqs_sequence'] = EPHD_Utilities::post( 'faqs_sequence', [] );
		if ( ! is_array( $widget['faqs_sequence'] ) ) {
			$widget['faqs_sequence'] = array();
		}

		// retrieve configuration for all Widgets - user can preview existing Widget or be creating a Widget (when passed Widget id is 0)
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 60 ) );
		}

		// add missing fields for existing Widget
		$base_widget = isset( $widgets_config[$widget['widget_id']] )
			? $widgets_config[$widget['widget_id']]
			: $widgets_config[EPHD_Config_Specs::DEFAULT_ID];
		$widget = array_merge( $base_widget, $widget );

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 62 ) );
		}

		$hd_handler = new EPHD_Help_Dialog_View( $widget, true, true );

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'demo_styles'   => EPHD_Help_Dialog_View::insert_widget_inline_styles( $global_config, $widget, true ),
			'preview'       => $hd_handler->output_help_dialog( true ),
		) ) );
	}
}
