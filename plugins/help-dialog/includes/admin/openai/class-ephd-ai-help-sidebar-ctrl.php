<?php

/**
 * Handle user submission from AI Help Sidebar
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_AI_Help_Sidebar_Ctrl {

	public function __construct() {

		// Fix Question Spelling and Grammar
		add_action( 'wp_ajax_ephd_fix_question_spelling_and_grammar', array( $this, 'fix_question_spelling_and_grammar' ) );
		add_action( 'wp_ajax_nopriv_ephd_fix_question_spelling_and_grammar', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Fix Answer Spelling and Grammar
		add_action( 'wp_ajax_ephd_fix_answer_spelling_and_grammar', array( $this, 'fix_answer_spelling_and_grammar' ) );
		add_action( 'wp_ajax_nopriv_ephd_fix_answer_spelling_and_grammar', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Create five Question alternatives
		add_action( 'wp_ajax_ephd_create_five_question_alternatives', array( $this, 'create_five_question_alternatives' ) );
		add_action( 'wp_ajax_nopriv_ephd_create_five_question_alternatives', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Create five Answer alternatives
		add_action( 'wp_ajax_ephd_create_five_answer_alternatives', array( $this, 'create_five_answer_alternatives' ) );
		add_action( 'wp_ajax_nopriv_ephd_create_five_answer_alternatives', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		// Create answer based on question
		add_action( 'wp_ajax_ephd_create_answer_based_on_question', array( $this, 'create_answer_based_on_question' ) );
		add_action( 'wp_ajax_nopriv_ephd_create_answer_based_on_question', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * Fix Question Spelling and Grammar
	 */
	public function fix_question_spelling_and_grammar() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$input_text = EPHD_Utilities::post( 'input_text' );

		$openai_handler = new EPHD_OpenAI();
		$fixed_input_text = $openai_handler->edit(
			'text-davinci-edit-001',
			'Fix spelling and grammar of the following text',
			$input_text,
			0
		);

		// let user see error message from OpenAI API response
		if ( ! empty( $openai_handler->error_message ) ) {
			EPHD_Utilities::ajax_show_error_die( $openai_handler->error_message );
		}

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => __( 'Spelling and grammar have been successfully corrected.', 'help-dialog' ),
			'fixed_input_text'  => $fixed_input_text,
			'tokens_used'       => $openai_handler->tokens_used,
		) ) );
	}

	/**
	 * Fix Answer Spelling and Grammar
	 */
	public function fix_answer_spelling_and_grammar() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$input_text = EPHD_Utilities::post( 'input_text' );

		$openai_handler = new EPHD_OpenAI();
		$fixed_input_text = $openai_handler->edit(
			'text-davinci-edit-001',
			'Fix spelling and grammar of the following text',
			$input_text,
			0
		);

		// let user see error message from OpenAI API response
		if ( ! empty( $openai_handler->error_message ) ) {
			EPHD_Utilities::ajax_show_error_die( $openai_handler->error_message );
		}

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => __( 'Spelling and grammar have been successfully corrected.', 'help-dialog' ),
			'fixed_input_text'  => $fixed_input_text,
			'tokens_used'       => $openai_handler->tokens_used,
		) ) );
	}

	/**
	 * Create five Question alternatives
	 */
	public function create_five_question_alternatives() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$input_text = EPHD_Utilities::post( 'input_text' );

		$openai_handler = new EPHD_OpenAI();
		$completion_text = $openai_handler->complete(
			'text-davinci-003',
			'Create five alternatives of up to 200 characters each to this text: ' . $input_text,
			0.5
		);

		// let user see error message from OpenAI API response
		if ( ! empty( $openai_handler->error_message ) ) {
			EPHD_Utilities::ajax_show_error_die( $openai_handler->error_message );
		}

		// split single completion text to a list of alternatives
		$alternatives = preg_split( '/([1-9].\s|\n[1-9].\s)/', $completion_text );
		$filtered_alternatives = [];
		foreach ( $alternatives as $value ) {
			if ( ! empty( $value ) ) {
				$filtered_alternatives[] = trim( $value );
			}
		}

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => __( 'Successfully generated.', 'help-dialog' ),
			'alternatives'  => $filtered_alternatives,
			'tokens_used'   => $openai_handler->tokens_used,
			'raw_output'    => $completion_text,    // TODO: temporary solution for debug purpose to solve sometimes unexpected result in $filtered_alternatives
		) ) );
	}

	/**
	 * Create five Answer alternatives
	 */
	public function create_five_answer_alternatives() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$input_text = EPHD_Utilities::post( 'input_text' );

		$openai_handler = new EPHD_OpenAI();
		$completion_text = $openai_handler->complete(
			'text-davinci-003',
			'Create five alternatives of up to 1500 characters each to this text: ' . $input_text,
			0.5
		);

		// let user see error message from OpenAI API response
		if ( ! empty( $openai_handler->error_message ) ) {
			EPHD_Utilities::ajax_show_error_die( $openai_handler->error_message );
		}

		// split single completion text to a list of alternatives
		$alternatives = preg_split( '/([1-9].\s|\n[1-9].\s)/', $completion_text );
		$filtered_alternatives = [];
		foreach ( $alternatives as $value ) {
			if ( ! empty( $value ) ) {
				$filtered_alternatives[] = trim( $value );
			}
		}

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => __( 'Successfully generated.', 'help-dialog' ),
			'alternatives'  => $filtered_alternatives,
			'tokens_used'   => $openai_handler->tokens_used,
			'raw_output'    => $completion_text,    // TODO: temporary solution for debug purpose to solve sometimes unexpected result in $filtered_alternatives
		) ) );
	}

	/**
	 * Create answer based on question
	 */
	public function create_answer_based_on_question() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		$question_text = EPHD_Utilities::post( 'question_text' );

		$openai_handler = new EPHD_OpenAI();
		$generated_answer = $openai_handler->complete(
			'text-davinci-003',
			'Create a complete answer of up to 1500 characters based on the following question: ' . $question_text,
			0.5
		);

		// let user see error message from OpenAI API response
		if ( ! empty( $openai_handler->error_message ) ) {
			EPHD_Utilities::ajax_show_error_die( $openai_handler->error_message );
		}

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => __( 'Successfully generated.', 'help-dialog' ),
			'generated_answer'  => $generated_answer,
			'tokens_used'       => $openai_handler->tokens_used,
		) ) );
	}
}
