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

namespace FireBox\Core\Helpers\Form;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FireBox\Core\Helpers\BoxHelper;
use \FPFramework\Helpers\Plugins\FireBox\Form as FrameworkFireBoxForm;

class Form
{
	/**
	 * Checks whether a form is valid given its ID.
	 * 
	 * @param   string  $form_id
	 * 
	 * @return  string
	 */
	public static function isValid($form_id = null)
	{
		if (!$form_id)
		{
			return false;
		}

		// Validate Form ID
		// Get all popups
		$popups = BoxHelper::getAllBoxes(['publish', 'draft']);
		$popups = BoxHelper::produceKeyValueBoxes($popups->posts);

		// Whether the Form ID is valid
		$valid_form_id = false;

		if (!$forms = self::getForms())
		{
			return false;
		}

		foreach ($forms as $key => $form)
		{
			if ('form-' . $form['id'] !== $form_id)
			{
				continue;
			}

			return $form;
		}

		return;
	}

	/**
	 * Returns a list of forms with form id => form title key,value pair.
	 * 
	 * @return  array
	 */
	public static function getParsedForms()
	{
		// cache key
		$hash = md5('FireBox\Core\Belpers\Form::getParsedForms');

		// check cache
		if ($forms = wp_cache_get($hash))
		{
			return;
        }

		$forms = self::getForms();

		$parsed = [];

		foreach ($forms as $key => $value)
		{
			$parsed[$value['id']] = $value['name'];
		}
		
		// set cache
		wp_cache_set($hash, $parsed, $hash);
		
		return $parsed;
	}

	/**
	 * Gets all forms.
	 * 
	 * @return  array
	 */
	public static function getForms()
	{
		// Get all popups
		$popups_data = BoxHelper::getAllBoxes(['publish', 'draft']);
		$popups = BoxHelper::produceKeyValueBoxes($popups_data->posts);

		$forms = [];

		// Find forms
		foreach ($popups as $id => $title)
		{
			if (!has_block('firebox/form', $id))
			{
				continue;
			}
			
			$blocks = parse_blocks(get_the_content(null, false, $id));

			$popup_data = self::findPopupByID($id, $popups_data->posts);

			foreach ($blocks as $key => $block)
			{
				if (isset($block['innerBlocks']))
				{
					foreach ($block['innerBlocks'] as $innerBlock)
					{
						// Find form block
						if (!$form_block = FrameworkFireBoxForm::findRecursiveForm($innerBlock))
						{
							continue;
						}

						$atts = isset($form_block['attrs']) ? $form_block['attrs'] : false;
						if (!$atts)
						{
							continue;
						}
		
						$block_unique_id = isset($atts['uniqueId']) ? $atts['uniqueId'] : false;
						if (!$block_unique_id)
						{
							continue;
						}

						$forms[] = [
							'id' => $block_unique_id,
							'block' => $form_block,
							'created_at' => $popup_data->post_modified_gmt ? $popup_data->post_modified_gmt : $popup_data->post_date_gmt,
							'state' => $popup_data->post_status === 'publish' ? '1' : '0',
							'name' => isset($form_block['attrs']['formName']) ? $form_block['attrs']['formName'] : firebox()->_('FB_UNTITLED_FORM'),
							'fields' => self::getFormFields($form_block)
						];
					}
				}

				if ($block['blockName'] !== 'firebox/form')
				{
					continue;
				}

				$atts = isset($block['attrs']) ? $block['attrs'] : false;
				if (!$atts)
				{
					continue;
				}

				$block_unique_id = isset($atts['uniqueId']) ? $atts['uniqueId'] : false;
				if (!$block_unique_id)
				{
					continue;
				}

				$forms[] = [
					'id' => $block_unique_id,
					'block' => $block,
					'created_at' => $popup_data->post_modified_gmt ? $popup_data->post_modified_gmt : $popup_data->post_date_gmt,
					'state' => $popup_data->post_status === 'publish' ? '1' : '0',
					'name' => isset($block['attrs']['formName']) ? $block['attrs']['formName'] : firebox()->_('FB_UNTITLED_FORM'),
					'fields' => self::getFormFields($block)
				];
			}
		}

		return $forms;
	}

	public static function findPopupByID($id = null, $popups = [])
	{
		if (!$id || !$popups)
		{
			return;
		}
		
		foreach ($popups as $key => $popup)
		{
			if ($popup->ID !== $id)
			{
				continue;
			}

			return $popup;
		}
		
		return false;
	}
	
	/**
	 * Validates the form.
	 * 
	 * @param   array  $form_fields
	 * @param   array  $fields_values
	 * 
	 * @return  array
	 */
	public static function validate($form_fields = [], &$fields_values = [])
	{
		if (!$form_fields || !$fields_values)
		{
			return false;
		}

		$validation = [];

		// Remove honeypot field
		unset($fields_values['hnpt']);

		// Check honeypot
		if (isset($fields_values['hnpt']) && !empty($fields_values['hnpt']))
		{
			return [
				'error' => true,
				'message' => firebox()->_('FB_HONEYPOT_FIELD_TRIGGERED')
			];
		}

		// Ensure the fields values are based on valid form fields
		foreach ($fields_values as $field_name => $field_value)
		{
			$valid_value = count(array_filter($form_fields, function($field) use ($field_name) {
				return $field_name === $field->getOptionValue('name');
			}));

			if (!$valid_value)
			{
				unset($fields_values[$field_name]);
			}
		}

		$error_msgs = [];

		// Validate fields
		foreach ($form_fields as $field)
		{
			$field_name = $field->getOptionValue('name');
			
			// Whether this is an array that contains a "value" key that stores the value or if its the whole value of the key $field_name
			$field_value = isset($fields_values[$field_name]['value']) ? $fields_values[$field_name]['value'] : $fields_values[$field_name];

			// Validate class
			if (!$field->validate($field_value))
			{
				$validation_message = $field->getValidationMessage();
				$error_msgs[] = $field->getLabel() . ': ' . $validation_message;
				
				$validation[] = [
					'name' => $field_name,
					'label' => $field->getLabel(),
					'type' => $field->getOptionValue('type'),
					'validation_message' => $validation_message
				];
			}

			// Update field value after validation
			if (isset($fields_values[$field_name]['value']))
			{
				$fields_values[$field_name]['value'] = $field_value;
			}
			else
			{
				$fields_values[$field_name] = $field_value;
			}
		}

		return $validation ? ['error' => $validation, 'message' => implode('<br />', $error_msgs)] : $form_fields;
	}

	/**
	 * Finds all supported blocks recursively.
	 * 
	 * @param   array  $block
	 * @param   array  $supported_blocks
	 * 
	 * @return  array
	 */
	private static function findRecursiveBlocks($block, $supported_blocks)
	{
		$matching_blocks = [];

		if (in_array($block['blockName'], $supported_blocks))
		{
			$matching_blocks[] = $block;
		}

		if (!empty($block['innerBlocks']))
		{
			foreach ($block['innerBlocks'] as $innerBlockItem)
			{
				$innerBlocks = self::findRecursiveBlocks($innerBlockItem, $supported_blocks);

				if (!empty($innerBlocks))
				{
					$matching_blocks = array_merge($matching_blocks, $innerBlocks);
				}
			}
		}

		return $matching_blocks;
	}

	/**
	 * Return the form fields.
	 * 
	 * @param   array  $form
	 * 
	 * @return  array
	 */
	public static function getFormFields($form)
	{
		// Find all supported fields
		$supported_blocks = self::getSupportedBlocks();

		$form_fields = [];

		// Find form blocks
		foreach ($form['innerBlocks'] as $key => $block)
		{
			// Find supported block
			if (!$found_blocks = self::findRecursiveBlocks($block, $supported_blocks))
			{
				continue;
			}

			foreach ($found_blocks as $_block)
			{
				$field_payload = [
					'id' => $_block['attrs']['uniqueId'],
					'label' => Field::getFieldLabel($_block),
					'type' => Field::getFieldType($_block['blockName']),
					'name' => Field::getFieldName($_block)
				];
				$final_field_payload = array_merge($field_payload, $_block['attrs']);

				// If the dropdown field doesn't have any custom choices, use the default ones
				if ($final_field_payload['type'] === 'dropdown')
				{
					if (!isset($final_field_payload['choices']))
					{
						$final_field_payload['choices'] = [
							[
								'default' => false,
								'value' => 1,
								'label' => 'Choice 1',
								'image' => ''
							],
							[
								'default' => false,
								'value' => 2,
								'label' => 'Choice 2',
								'image' => ''
							],
							[
								'default' => false,
								'value' => 3,
								'label' => 'Choice 3',
								'image' => ''
							]
						];
					}
				}
	
				$form_fields[] = Field::getFieldClass($final_field_payload);
			}
		}

		return $form_fields;
	}

	/**
	 * Returns the form given its ID.
	 * 
	 * @param   string  $form_id
	 * 
	 * @return  array
	 */
	public static function getFormByID($form_id = null)
	{
		$forms = self::getForms();

		foreach ($forms as $form)
		{
			if ($form['id'] !== $form_id)
			{
				continue;
			}

			return $form;
		}

		return false;
	}

	/**
	 * Returns the supported blocks.
	 * 
	 * @param   bool   $clean   Whether to return only the name of the field without the prefix "firebox/"
	 * 
	 * @return  array
	 */
	public static function getSupportedBlocks($clean = false)
	{
		$blocks = array_diff(scandir(FBOX_PLUGIN_DIR . 'Inc/Core/Form/Fields/Fields'), ['index.php', '.', '..', '.DS_Store']);

		$data = [];

		foreach ($blocks as $key => $name)
		{
			// Strip .php
			$name = rtrim($name, '.php');

			$data[] = (!$clean ? 'firebox/' : '') . strtolower($name);
		}
		
		return $data;
	}

	/**
	 * Store submission.
	 * 
	 * @param   string  $form_id
	 * @param   array   $form_settings
	 * @param   array   $valid_fields
	 * @param   array   $fields_values
	 * @param   bool    $save
	 * 
	 * @return  array
	 */
	public static function storeSubmission($form_id, $form_settings, $valid_fields, $fields_values, $save = true)
	{
		$submissionDefaultState = isset($form_settings['attrs']['submissionDefaultState']) ? $form_settings['attrs']['submissionDefaultState'] : 1;

		if (!$submission_data = Submission::create($form_id, $submissionDefaultState, $save))
		{
			return false;
		}
		
		if (!$submission_meta_data = SubmissionMeta::create($submission_data['id'], $fields_values, $save))
		{
			return false;
		}

		return self::prepare($submission_data, $valid_fields, $fields_values);
	}

	/**
	 * Prepare fields.
	 * 
	 * @param   array  $submission
	 * @param   array  $valid_fields
	 * @param   array  $fields_values
	 * 
	 * @return  array
	 */
	private static function prepare($submission, $valid_fields, $fields_values)
	{
		$prepared_data = $submission;
		$prepared_data['prepared_fields'] = [];

		foreach ($valid_fields as $key => $field)
		{
			$field_name = $field->getOptionValue('name');
			$submitted_value = $fields_values[$field_name];

			$field->setValue($submitted_value['value']);

			$prepared_data['prepared_fields'][$field_name] = [
				'class' => $field,
				'value' => $field->prepareValue($submitted_value['value']),
                'value_html' => $field->prepareValueHTML($submitted_value['value']),
                'value_raw' => $submitted_value['value']
			];
		}
		
		return $prepared_data;
	}

	/**
	 * Ensure the popup has unique Form IDs.
	 * 
	 * @param   string  $content
	 * 
	 * @return  void
	 */
	public static function ensureUniqueFormIDs(&$content)
	{
		// Get forms
		$forms = self::getForms();

		// Get form IDs in content
		$pattern = '/wp:firebox\/form {"uniqueId":"(.*?)"/';

		// Find matches
		preg_match_all($pattern, $content, $matches);

		// Ensure we have at least one form in the popup
		if (!isset($matches[1]) || empty($matches[1]))
		{
			return;
		}

		$old_form_ids_in_popup = $matches[1];
		$new_form_ids_in_popup = [];

		// Find new IDs
		foreach ($old_form_ids_in_popup as $key => $id)
		{
			while (true)
			{
				$form = array_filter($forms, function($form_item) use ($id) {
					return $id === $form_item['id'];
				});
				$form_id = reset($form);

				// Form ID is unique
				if (!$form_id)
				{
					$new_form_ids_in_popup[] = $id;
					break;
				}
				
				// Form ID is not unique, generate new
				$id = md5(uniqid());
				$id = substr($id, 0, 12);

				// Add dash after 6th character
				$id = substr_replace($id, '-', 6, 0);
				
				// Add dash after 10th character
				$id = substr_replace($id, '-', 11, 0);
			}
		}

		if (count($old_form_ids_in_popup) !== count($new_form_ids_in_popup))
		{
			return;
		}
		
		// Replace old IDs with new IDs
		foreach ($old_form_ids_in_popup as $index => $id)
		{
			// Replace unique ID
			$content = str_replace('"uniqueId":"' . $id . '"', '"uniqueId":"' . $new_form_ids_in_popup[$index] . '"', $content);

			// Replace all other instances
			$content = str_replace('form-' . $id, 'form-' . $new_form_ids_in_popup[$index], $content);
		}
	}

	/**
	 * Replaces Smart Tags.
	 * 
	 * @param   array  $attrs
	 * @param   array  $fields_values
	 * @param   array  $submission
	 * 
	 * @return  void
	 */
	public static function replaceSmartTags(&$attrs, $fields_values, $submission)
	{
		// Replace Smart Tags
		$tags = new \FPFramework\Base\SmartTags\SmartTags();
		
		// register FB Smart Tags
		$tags->register('\FireBox\Core\Form\SmartTags', FBOX_BASE_FOLDER . '/Inc/Core/Form/SmartTags', [
			'field_values' => $fields_values,
			'submission' => $submission
		]);

		$attrs = $tags->replace($attrs);
	}

	/**
	 * Returns the submission action.
	 * 
	 * @param   array  $attrs
	 * 
	 * @return  array
	 */
	public static function getSubmissionAction($attrs)
	{
		$action = isset($attrs['submissionAction']) ? $attrs['submissionAction'] : 'message';

		$payload = [
			'action' => $action,
			'message' => $action === 'message' ? (isset($attrs['messageAfterSuccess']) ? $attrs['messageAfterSuccess'] : 'Thanks for contacting us! We will get in touch with you shortly.') : '',
			'resetForm' => isset($attrs['resetForm']) ? $attrs['resetForm'] : true,
			'hideForm' => isset($attrs['hideForm']) ? $attrs['hideForm'] : true
		];

		if ($action === 'redirect')
		{
			$payload['redirectURL'] = isset($attrs['redirectURL']) ? $attrs['redirectURL'] : '';
		}

		return $payload;
	}

	public static function getSubmissions($form_id = null)
	{
		if (!$form_id)
		{
			return;
		}

		$data = [
			'where' => [
				'form_id' => " = '" . esc_sql($form_id) . "'"
			],
			'limit' => 1000
		];

		$submissions = firebox()->tables->submission->getResults($data, true);

		foreach ($submissions as &$submission)
		{
			$submission->meta = SubmissionMeta::getMeta($submission->id);
		}
		
		return $submissions;
	}
}