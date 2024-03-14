<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle Help Dialog data
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Help_Dialog_Handler {

	const HELP_DIALOG_STATUS_PUBLIC = 'published';
	const HELP_DIALOG_STATUS_DRAFT = 'draft';

	/**
	 * Add default FAQs
	 */
	public static function add_default_faqs() {

		// retrieve all Widgets configuration - including default one; ignore errors
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// update locations
		$widgets_config[EPHD_Config_Specs::DEFAULT_ID]['location_pages_list'] = [EPHD_Config_Specs::HOME_PAGE];

		// create demo questions
		$demo_questions = self::get_demo_questions();
		foreach ( $demo_questions as $question ) {
			$question = self::create_sample_faq( $question->question, $question->answer );
			if ( empty( $question ) ) {
				continue;
			}

			array_push( $widgets_config[EPHD_Config_Specs::DEFAULT_ID]['faqs_sequence'], $question->faq_id );
		}

		// save Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Widgets configuration. (05)' );
		}
	}

	/**
	 * Return array of FAQs for demo purpose only
	 *
	 * @return array
	 */
	private static function get_demo_questions() {

		$faqs = [];

		$faqs_config = [
			[
				'question' => __( 'Where can I find documentation?', 'help-dialog' ),
				'answer' => esc_html__( 'EXAMPLE', 'help-dialog' ) . ' - ' . esc_html__( 'We have a detailed knowledge base about our product and services', 'help-dialog' ) .
				            ' <a href="https://www.helpdialog.com/documentation/" target="_blank">' . esc_html__( 'here', 'help-dialog' ) . '<span class="ephdfa ephdfa-external-link"></span></a>',
			],
			[
				'question' => __( 'Do you offer any discounts?', 'help-dialog' ),
				'answer' => __( 'EXAMPLE', 'help-dialog' ) . ' - ' . __( 'Currently, we have a sale for 20% off all regular priced merchandise in the store.', 'help-dialog' ),
			],
			[
				'question' => __( 'What payment methods do you accept?', 'help-dialog' ),
				'answer' => __( 'EXAMPLE', 'help-dialog' ) . ' - '. __( 'We accept all main methods of payments: VISA, Mastercard, and PayPal.', 'help-dialog' ),
			],
		];

		foreach ( $faqs_config as $faq_config ) {
			$new_faq = new stdClass();
			$new_faq->question = $faq_config['question'];
			$new_faq->answer = $faq_config['answer'];
			$faqs[] = $new_faq;
		}

		return $faqs;
	}

	/**
	 * Create sample FAQ
	 *
	 * @param $question
	 * @param $answer
	 *
	 * @return object|null
	 */
	private static function create_sample_faq( $question, $answer ) {

		// create question
		$faqs_db_handler = new EPHD_FAQs_DB();
		$faq = $faqs_db_handler->insert_faq( 0, 0, $question, $answer, 'publish' );
		if ( is_wp_error( $faq ) || empty( $faq ) ) {
			EPHD_Logging::add_log( 'Could not insert post for a new FAQ', $faq );
			return null;
		}

		return $faq;
	}
}
