<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */
namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Class LearnPressExport
 * @package Smackcoders\SMEXP
 */

class LearnPressExport extends ExportExtension{

	protected static $instance = null,$export_instance;	
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			LearnPressExport::$export_instance = ExportExtension::getInstance();
		}
		return self::$instance;
	}

	/**
	 * CustomerReviewExport constructor.
	 */
	public function __construct() {
		$this->plugin = Plugin::getInstance();
	}

	public function getCourseData($id)
	{
		global $wpdb;

		$get_section_details = $wpdb->get_results("SELECT section_id, section_name, section_description FROM {$wpdb->prefix}learnpress_sections WHERE section_course_id = $id ", ARRAY_A);
		$section_names = '';
		$section_descriptions = '';
		$get_lesson_name = '';
		$get_lesson_description = '';
		$get_lesson_duration = '';
		$get_lesson_preview = '';
		$get_quiz_name = '';
		$get_quiz_description = '';
		$get_quiz_meta = [];

		foreach($get_section_details as $section_details){
			$section_names .= $section_details['section_name'] . '|';
			$section_descriptions .= $section_details['section_description'] . '|';

			$section_id = $section_details['section_id'];
			$get_section_item_details = $wpdb->get_results("SELECT item_id, item_type FROM {$wpdb->prefix}learnpress_section_items WHERE section_id = $section_id ", ARRAY_A);

			$lesson_name = '';
			$lesson_description = '';
			$quiz_name = '';
			$quiz_description = '';
			$lesson_duration = '';
			$lesson_preview = '';
			$quiz_metas = [];

			foreach($get_section_item_details as $section_item_details){
				$section_item_id = $section_item_details['item_id'];
				if($section_item_details['item_type'] == 'lp_lesson'){
					$lesson_name .= $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts WHERE ID = $section_item_id ") . ', ';
					$lesson_description .= $wpdb->get_var("SELECT post_content FROM {$wpdb->prefix}posts WHERE ID = $section_item_id "). ', ';
					$lesson_duration .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $section_item_id AND meta_key = '_lp_duration' ") . ', ';
					$lesson_preview .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $section_item_id AND meta_key = '_lp_preview' ") . ', ';
				}
				elseif($section_item_details['item_type'] == 'lp_quiz'){
					$quiz_name .= $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts WHERE ID = $section_item_id ") . ', ';
					$quiz_description .= $wpdb->get_var("SELECT post_content FROM {$wpdb->prefix}posts WHERE ID = $section_item_id "). ', ';

					$quiz_meta = $wpdb->get_results("SELECT meta_key, meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $section_item_id AND meta_key LIKE '_lp_%' ", ARRAY_A);
					foreach($quiz_meta as $quiz_meta_values){
						$quiz_key = $quiz_meta_values['meta_key'];
						$quiz_value = $quiz_meta_values['meta_value'] . ', ';

						if($quiz_key != '_lp_hidden_questions'){
							if($quiz_key == '_lp_retake_count'){
								$quiz_key = '_lp_quiz_retake_count';
							}
							$quiz_metas[$quiz_key] = $quiz_value;
						}
					}
				}
			}

			$get_lesson_name .= rtrim($lesson_name, ', ') . '|';
			$get_lesson_description .= rtrim($lesson_description, ', ') . '|';
			$get_quiz_name .= rtrim($quiz_name, ', ') . '|';
			$get_quiz_description .= rtrim($quiz_description, ', ') . '|';
			$get_lesson_duration .= rtrim($lesson_duration, ', ') . '|';
			$get_lesson_preview .= rtrim($lesson_preview, ', ') . '|';

			foreach($quiz_metas as $quiz_meta_keys => $quiz_meta_values){	
				$get_quiz_meta[$quiz_meta_keys] = rtrim($quiz_meta_values, ', ') . '|';
			}
		}

		LearnPressExport::$export_instance->data[$id]['curriculum_name'] = rtrim($section_names, '|');
		LearnPressExport::$export_instance->data[$id]['curriculum_description'] = rtrim($section_descriptions, '|');
		LearnPressExport::$export_instance->data[$id]['lesson_name'] = rtrim($get_lesson_name, '|');
		LearnPressExport::$export_instance->data[$id]['lesson_description'] = rtrim($get_lesson_description, '|');
		LearnPressExport::$export_instance->data[$id]['quiz_name'] = rtrim($get_quiz_name, '|');
		LearnPressExport::$export_instance->data[$id]['quiz_description'] = rtrim($get_quiz_description, '|');
		LearnPressExport::$export_instance->data[$id]['_lp_lesson_duration'] = rtrim($get_lesson_duration, '|');
		LearnPressExport::$export_instance->data[$id]['_lp_preview'] = rtrim($get_lesson_preview, '|');

		foreach($get_quiz_meta as $get_quiz_meta_keys => $get_quiz_meta_values){
			LearnPressExport::$export_instance->data[$id][$get_quiz_meta_keys] = rtrim($get_quiz_meta_values, '|');
		}
	}

	public function getLessonData($id){
		global $wpdb;
		$lesson_duration = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $id AND meta_key = '_lp_duration' ");
		LearnPressExport::$export_instance->data[$id]['_lp_lesson_duration'] = $lesson_duration;

		$get_section_id = $wpdb->get_var("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $id AND item_type = 'lp_lesson' ");
		if(!empty($get_section_id)){
			$get_section_name = $wpdb->get_var("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");
			$get_section_course_id = $wpdb->get_var("SELECT section_course_id FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");

			LearnPressExport::$export_instance->data[$id]['curriculum_name'] = $get_section_name;
			LearnPressExport::$export_instance->data[$id]['course_id'] = $get_section_course_id;
		}
	}


	public function getQuizData($id){
		global $wpdb;
		$quiz_retake_count = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $id AND meta_key = '_lp_retake_count' ");
		LearnPressExport::$export_instance->data[$id]['_lp_quiz_retake_count'] = $quiz_retake_count;

		$get_section_id = $wpdb->get_var("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $id AND item_type = 'lp_quiz' ");
		if(!empty($get_section_id)){
			$get_section_name = $wpdb->get_var("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");
			$get_section_course_id = $wpdb->get_var("SELECT section_course_id FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");

			LearnPressExport::$export_instance->data[$id]['curriculum_name'] = $get_section_name;
			LearnPressExport::$export_instance->data[$id]['course_id'] = $get_section_course_id;
		}

		$get_question_title = '';
		$get_question_content = '';
		$get_question_mark = '';
		$get_question_explanation = '';
		$get_question_hint = '';
		$get_question_type = '';
		$get_option_value = '';

		$get_question_ids = $wpdb->get_results("SELECT question_id FROM {$wpdb->prefix}learnpress_quiz_questions WHERE quiz_id = $id ", ARRAY_A);
		foreach($get_question_ids as $question_ids){
			$question_id = $question_ids['question_id'];
			$get_question_title .= $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts WHERE ID = $question_id ") . ',';
			$get_question_content .= $wpdb->get_var("SELECT post_content FROM {$wpdb->prefix}posts WHERE ID = $question_id ") . ',';

			$get_question_mark .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $question_id AND meta_key = '_lp_mark' ") . ', ';	
			$get_question_explanation .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $question_id AND meta_key = '_lp_explanation' ") . ',';	
			$get_question_hint .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $question_id AND meta_key = '_lp_hint' ") . ',';	
			$get_question_type .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $question_id AND meta_key = '_lp_type' ") . ',';	

			$get_question_options = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}learnpress_question_answers WHERE question_id = $question_id ", ARRAY_A);
			$option_value = '';
			foreach($get_question_options as $question_option){
				if(empty($question_option['is_true'])){
					$question_option['is_true'] = 'no';
				}

				$option_value .= $question_option['title'] .'|'. $question_option['is_true'] . '->';
			}
			$get_option_value .=  rtrim($option_value, '->') . ',';
		}

		LearnPressExport::$export_instance->data[$id]['question_title'] = rtrim($get_question_title, ',');
		LearnPressExport::$export_instance->data[$id]['question_description'] = rtrim($get_question_content, ',');
		LearnPressExport::$export_instance->data[$id]['_lp_mark'] = rtrim($get_question_mark, ', ');
		LearnPressExport::$export_instance->data[$id]['_lp_explanation'] = rtrim($get_question_explanation, ',');
		LearnPressExport::$export_instance->data[$id]['_lp_hint'] = rtrim($get_question_hint, ',');
		LearnPressExport::$export_instance->data[$id]['_lp_type'] = rtrim($get_question_type, ',');
		LearnPressExport::$export_instance->data[$id]['question_options'] = rtrim($get_option_value, ',');

	}

	public function getQuestionData($id){
		global $wpdb;
		$get_quiz_id = $wpdb->get_var("SELECT quiz_id FROM {$wpdb->prefix}learnpress_quiz_questions WHERE question_id = $id ");

		$get_question_options = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}learnpress_question_answers WHERE question_id = $id ", ARRAY_A);
		$option_value = '';
		foreach($get_question_options as  $question_options){
			if(empty($question_options['is_true'])){
				$question_options['is_true'] = 'no';
			}
			$option_value .= $question_options['title'] .'|'. $question_options['is_true'] . '->';
		}

		if(!empty($get_quiz_id)){
			$get_section_id = $wpdb->get_var("SELECT section_id FROM {$wpdb->prefix}learnpress_section_items WHERE item_id = $get_quiz_id AND item_type = 'lp_quiz' ");
			if(!empty($get_section_id)){
				$get_section_name = $wpdb->get_var("SELECT section_name FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");
				$get_section_course_id = $wpdb->get_var("SELECT section_course_id FROM {$wpdb->prefix}learnpress_sections WHERE section_id = $get_section_id ");

				LearnPressExport::$export_instance->data[$id]['curriculum_name'] = $get_section_name;
				LearnPressExport::$export_instance->data[$id]['course_id'] = $get_section_course_id;
			}
			LearnPressExport::$export_instance->data[$id]['quiz_id'] = $get_quiz_id;
		}

		LearnPressExport::$export_instance->data[$id]['question_options'] = rtrim($option_value, '->');
	}
	public function getOrderData($id){
		global $wpdb;

		$order_status = $wpdb->get_var("SELECT post_status FROM {$wpdb->prefix}posts WHERE ID = $id ");
		$order_date = $wpdb->get_var("SELECT post_date FROM {$wpdb->prefix}posts WHERE ID = $id ");
		$order_total = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $id AND meta_key = '_order_total' ");
		$order_subtotal = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $id AND meta_key = '_order_subtotal' ");
		$user_id = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = $id AND meta_key = '_user_id' ");

		$get_order_items = $wpdb->get_results("SELECT order_item_id FROM {$wpdb->prefix}learnpress_order_items WHERE order_id = $id ",ARRAY_A);
		$course_id = '';
		$item_quantity = '';
		$item_total = '';
		$item_subtotal = '';
		foreach($get_order_items as $get_order_values){
			$order_item_id = $get_order_values['order_item_id'];

			$course_id .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = $order_item_id AND meta_key = '_course_id' ") . ', ';
			$item_quantity .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = $order_item_id AND meta_key = '_quantity' ") . ', ';
			$item_total .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = $order_item_id AND meta_key = '_subtotal' ") . ', ';
			$item_subtotal .= $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}learnpress_order_itemmeta WHERE learnpress_order_item_id = $order_item_id AND meta_key = '_total' ") . ', ';
		}

		LearnPressExport::$export_instance->data[$id]['order_status'] = $order_status;
		LearnPressExport::$export_instance->data[$id]['order_date'] = $order_date;
		LearnPressExport::$export_instance->data[$id]['_order_total'] = $order_total;
		LearnPressExport::$export_instance->data[$id]['_order_subtotal'] = $order_subtotal;
		LearnPressExport::$export_instance->data[$id]['user_id'] = $user_id;
		LearnPressExport::$export_instance->data[$id]['item_id'] = rtrim($course_id, ', ');
		LearnPressExport::$export_instance->data[$id]['item_quantity'] = rtrim($item_quantity, ', ');
		LearnPressExport::$export_instance->data[$id]['_item_total'] = rtrim($item_total, ', ');
		LearnPressExport::$export_instance->data[$id]['_item_subtotal'] = rtrim($item_subtotal, ', ');	
	}

}





global $learnpress_exp_class;
$learnpress_exp_class = new LearnPressExport();
