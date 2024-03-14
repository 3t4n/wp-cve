<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Form;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FireBox\Core\Helpers\Form\Form;
use \FireBox\Core\Helpers\BoxHelper;

class Ajax
{
	public function __construct()
	{
		$this->setupAjax();

		new Actions\Ajax();
    }
    
	/**
	 * Setup ajax requests
	 * 
	 * @return  void
	 */
	public function setupAjax()
	{
		add_action('wp_ajax_fb_form_submission_status_change', [$this, 'fb_form_submission_status_change']);

		add_action('wp_ajax_fb_form_submit', [$this, 'fb_form_submit']);
        add_action('wp_ajax_nopriv_fb_form_submit', [$this, 'fb_form_submit']);
    }

	/**
	 * Update submission status.
	 * 
	 * @return  void
	 */
	public function fb_form_submission_status_change()
	{
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fb_form_submission_action'))
        {
			echo wp_json_encode([
				'error' => true,
				'message' => 'Cannot verify request.'
			]);
			wp_die();
        }

		$submission_id = isset($_POST['submission_id']) ? sanitize_key($_POST['submission_id']) : '';
		$new_state = isset($_POST['new_state']) ? sanitize_key($_POST['new_state']) : '';

		$new_state = $new_state === 'publish' ? 1 : 0;
		
		if (!\FireBox\Core\Helpers\Form\Submission::updateState($submission_id, $new_state))
		{
			echo wp_json_encode([
				'error' => false,
				'message' => 'Submission state couldn\'t be updated.'
			]);
			wp_die();
		}

		echo wp_json_encode([
			'error' => false,
			'message' => 'Submission state updated successfully.'
		]);
		wp_die();
	}
    
    /**
     * Form submit.
     * 
     * @return void
     */
    public function fb_form_submit()
    {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fbox_js_nonce'))
        {
			echo wp_json_encode([
				'error' => true,
				'message' => 'Cannot verify request.'
			]);
			wp_die();
        }

		$form_data = isset($_POST['form_data']) ? sanitize_text_field($_POST['form_data']) : '';
		$form_data = $form_data ? json_decode(html_entity_decode(stripslashes($form_data)), true) : '';
		
		if (!$form_data)
		{
			echo wp_json_encode([
				'error' => true,
				'message' => 'Cannot submit form.'
			]);
			wp_die();
		}

		$form_id = isset($form_data['form_id']) ? $form_data['form_id'] : false;
		if (!$form_id)
		{
			echo wp_json_encode([
				'error' => true,
				'message' => 'Missing Form ID.'
			]);
			wp_die();
		}

		$values = isset($form_data['fields']) ? $form_data['fields'] : false;
		if (!$form_id)
		{
			echo wp_json_encode([
				'error' => true,
				'message' => 'Missing submission data.'
			]);
			wp_die();
		}

		if (!$form = Form::isValid($form_id))
		{
			echo wp_json_encode([
				'error' => true,
				'message' => 'This form does not exist.'
			]);
			wp_die();
		}

		$form_block = $form['block'];
		$form_fields = $form['fields'];
		
		$validated_fields = Form::validate($form_fields, $values);

		if (isset($validated_fields['error']))
		{
			$payload = [
				'error' => true,
				'message' => isset($validated_fields['message']) ? $validated_fields['message'] : 'Form is invalid.'
			];

			if (is_array($validated_fields['error']))
			{
				$payload['validation'] = $validated_fields['error'];
			}
			echo wp_json_encode($payload);
			wp_die();
		}

		// Get the Campaign ID
		$box_id = isset($_POST['box_id']) ? sanitize_key($_POST['box_id']) : false;

		$submission = [];

		/**
		 * Also set the popup log id in field values.
		 * 
		 * This is useful for analytics purposes, i.e. to track form conversions.
		 */
		$box_log_id = isset($_POST['box_log_id']) && $_POST['box_log_id'] ? sanitize_key($_POST['box_log_id']) : false;
		if ($box_log_id)
		{
			$values['hidden_field_box_log_id'] = [
				'id' => 'box_log_id',
				'value' => $box_log_id
			];

			/**
			 * Track conversion
			 */
			$factory = new \FPFramework\Base\Factory();
			$data = [
				'log_id' => $box_log_id,
				'event' => 'conversion',
				'event_source' => 'form',
				'event_label' => isset($form['block']['attrs']['formName']) ? $form['block']['attrs']['formName'] : 'FireBox #' . $box_id . ' Form',
				'date' => $factory->getDate()->format('Y-m-d H:i:s')
			];
	
			firebox()->tables->boxlogdetails->insert($data);
		}

		// Determine whether to store the submission and store it
		$storeSubmissions = isset($form_block['attrs']['storeSubmissions']) ? $form_block['attrs']['storeSubmissions'] : true;
		if (!$submission = Form::storeSubmission($form_id, $form_block, $validated_fields, $values, $storeSubmissions))
		{
			echo wp_json_encode([
				'error' => true,
				'message' => 'Could not save submission. Please try again.'
			]);
			wp_die();
		}

		// Replace Smart Tags
		Form::replaceSmartTags($form_block['attrs'], $values, $submission);

		// Get box
		$box = firebox()->box->get($box_id);

		// Holds the form actions
		$actions = [];

		// Determine whether to run actions and run any
		if (isset($form_block['attrs']['actions']) && is_array($form_block['attrs']['actions']) && count($form_block['attrs']['actions']))
		{
			if ($box_id)
			{
				$submission['box_id'] = (int) $box_id;
			}
			
			$actions = new \FireBox\Core\Form\Actions\Actions($form_block, $values, $submission);
			if (!$actions->run())
			{
				echo wp_json_encode([
					'error' => true,
					'message' => $actions->getErrorMessage()
				]);
				wp_die();
			}
		}

		$action = Form::getSubmissionAction($form_block['attrs']);

		/**
		 * Fires after a successful form submission.
		 * 
		 * @param  array  $box  	   The campaign settings
		 * @param  array  $values      The form values
		 * @param  array  $submission  The submission
		 */
		$values = array_map(function($value) {
			return isset($value['value']) ? $value['value'] : '';
		}, $values);
		do_action('firebox/form/success', $box, $values, $submission);

		echo wp_json_encode(array_merge([
			'error' => false
		], $action));
		wp_die();
    }
}