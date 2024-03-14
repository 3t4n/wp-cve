<?php

/**
 * Nouvello WeManage Worker Leads Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Chat
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Nouvello WeManage Worker Leads Class
 */
class Nouvello_WeManage_Worker_Leads
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		//init session
		add_filter('wpcf7_posted_data', array($this, 'filter_posted_data'), 10, 1);

		add_action('wpcf7_before_send_mail', array($this, 'nouvello_cf7_form_lead'), 10);
		add_action('elementor_pro/forms/new_record', array($this, 'nouvello_elementor_form_lead'), 10);
	}

	/**
	 * [nouvello_get_cf7_form description]
	 *
	 * @param  [type] $cf7 [description].
	 */
	public function nouvello_cf7_form_lead($cf7)
	{
		$submission = WPCF7_Submission::get_instance();

		if ($submission) {
			$data = array(
				'form_type' => 'contact_form7',
				'form_name' => $cf7->title(),
				'form_id' => $cf7->id(),
			);

			$posted_data = $submission->get_posted_data();

			$contact_form = $submission->get_contact_form();
			$tags = $contact_form->scan_form_tags();

			$data['form_data'] = $this->parse_cf7_form_data($posted_data, $tags);

			if ($data) {
				nouvello_wemanage_worker()->webhooks->nouvello_wemanage_webhooks_form_lead_data($data);
			}
		}
	}

	/**
	 * [parse_cf7_form_data description]
	 *
	 * @param  [type] $posted_data [description].
	 * @param  [type] $tags        [description].
	 * @return [type]              [description]
	 */
	public function parse_cf7_form_data($posted_data, $tags)
	{
		foreach ($posted_data as $field => $value) {
			foreach ($tags as $tag) {
				if ($tag->name == $field && isset($tag->basetype) && ('email' == $tag->basetype || 'tel' == $tag->basetype)) {
					$posted_data[$field] = $value . '|' . strtoupper($tag->basetype) . '|';
				}
			}
		}

		return $posted_data;
	}


	public static function filter_posted_data($posted_data)
	{

		try {

			$submission = WPCF7_Submission::get_instance();
			$form = $submission->get_contact_form();
			$form_id = $form->id();

			if (Nouvello_WeManage_Utm_CF7_Form::is_enabled($form)) :

				//prepare first time session
				$session_instance = Nouvello_WeManage_Utm_CF7_Session::instance();
				$session_instance->setup($form, $submission);

				$tags = $form->scan_form_tags();

				foreach ((array) $tags as $tag) :

					if (
						$tag->type === 'hidden'
						&& !empty($tag->options[0])
						&& !empty($tag->name)
						&& isset($posted_data[$tag->name])
						&& Nouvello_WeManage_Utm_Functions::has_merge_tag($tag->options[0])
					) :

						$posted_data[$tag->name] = Nouvello_WeManage_Utm_Functions::get_merge_tag_value($tag->options[0], $session_instance->get('user_synced_session'));

					endif;

				endforeach;

			endif;
		} catch (\Exception $e) {
		}

		$attribution = Nouvello_WeManage_Utm_Service::prepare_attribution_data_for_saving($session_instance->get('user_synced_session'), 'converted');

		$posted_data['utm_attribution'] = $attribution;

		return $posted_data;
	}



	/**
	 * [nouvello_get_cf7_form description]
	 *
	 * @param  [type] $elementor [description].
	 */
	public function nouvello_elementor_form_lead($elementor)
	{
		$form_name = $elementor->get_form_settings('form_name');
		$form_id = $elementor->get_form_settings('id');
		$raw_fields = $elementor->get('fields');

		$data = array();
		$data['form_type'] = 'elementor';
		$data['form_name'] = $form_name;
		$data['form_id'] = $form_id;
		$data['form_data'] = array();

		if ($raw_fields) {
			foreach ($raw_fields as $field) {
				$key = $this->get_elementor_form_key(
					$field,
					'title',
					array(
						'type',
						'id',
					)
				);
				$field_type = '';
				if ('email' == $field['type'] || 'tel' == $field['type']) {
					$field_type = '|' . strtoupper($field['type']) . '|';
				}

				$data['form_data'][$key] = $field['value'] . $field_type;
				if ('password' === $field['type']) {
					$data['form_data'][$key] = '********';
				}
			}
		}

		if ($data) {
			nouvello_wemanage_worker()->webhooks->nouvello_wemanage_webhooks_form_lead_data($data);
		}
	}

	/**
	 * [get_elementor_form_key description]
	 *
	 * @param  [type] $field                   [description].
	 * @param  [type] $primary_field_name      [description].
	 * @param  array  $alternative_field_names [description].
	 * @return [type]                          [description]
	 */
	public function get_elementor_form_key($field, $primary_field_name, $alternative_field_names = array())
	{
		$is_field_array = is_array($field);
		$primary_key = $is_field_array ? $field[$primary_field_name] : $field->{$primary_field_name};
		$extracted_alternative_key = array();
		foreach ($alternative_field_names as $name) {
			$key_value = $is_field_array ? $field[$name] : $field->{$name};
			array_push($extracted_alternative_key, $key_value);
		}
		$alternative_key = join('_', $extracted_alternative_key); // ie. "email_1".
		return '' != $primary_key ? $primary_key : $alternative_key;
	}
}
