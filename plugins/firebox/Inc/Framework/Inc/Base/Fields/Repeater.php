<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Field;
use FPFramework\Libs\Registry;
use FPFramework\Helpers\FieldsHelper;
use FPFramework\Base\FieldsParser;

class Repeater extends Field
{
	/**
	 * Set specific field options
	 * 
	 * @param   array  $options
	 * 
	 * @return  void
	 */
	protected function setFieldOptions($options)
	{
		$options = new Registry($options);

		$btn_label = $options->get('btn_label');
		$btn_label = $btn_label === false ? '' : (!$btn_label ? 'FPF_ADD_ITEM' : $btn_label);
		
		$this->field_options = [
			'render_group' => $options->get('render_group', false),
			'default_values' => $options->get('default_values'),
			'default_html' => $options->get('default_html'),
			'fields' => $options->get('fields', []),
			'remove_action_class' => $options->get('remove_action_class', []),
			'actions_class' => $options->get('actions_class', []),
			'actions_prepend' => $options->get('actions_prepend', ''),
			'actions_append' => $options->get('actions_append', ''),
			'repeater_item_class' => $options->get('repeater_item_class', []),
			'btn_label' => $btn_label,
			'btn_class' => $options->get('btn_class', [])
		];
	}

	/**
	 * Returns the Repeater Default HTML
	 * 
	 * @return  string
	 */
	public function getRepeaterDefaultHTML()
	{
		if ($this->options['value'])
		{
			return;
		}
		
		if (!$this->options['default_values'])
		{
			return;
		}

		$fields_data = $this->getRepeaterFieldsData($this->options['default_values']);

		return $this->getRepeaterFieldsHTML($fields_data);
	}

	/**
	 * Returns the Repeater HTML
	 * 
	 * @return  string
	 */
	public function getRepeaterHTML()
	{
		$fields_data = $this->getRepeaterFieldsData($this->options['value']);

		return $this->getRepeaterFieldsHTML($fields_data);
	}

	/**
	 * Returns all HTML of the repeater item fields
	 * 
	 * @param   array  $fields_data
	 * 
	 * @return  string
	 */
	private function getRepeaterFieldsHTML($fields_data)
	{
		$options = new Registry($this->options);

		$fieldsParser = new FieldsParser([
			'fields_name_prefix' => $this->options['name_prefix']
		]);
		
		$repeater_html = [];
		
		$name = $options->get('name_key', '');
		
		$index = 1;
		foreach ($fields_data as $key => $fields)
		{
			foreach ($fields as &$field)
			{
				if (!isset($field['name']))
				{
					continue;
				}

				// update name of each field with correct index
				$field['name'] = $name . '.' . $index . '.' . $field['name'];
			}

			ob_start();
			$fieldsParser->renderFields($fields);
			$html = ob_get_contents();
			ob_end_clean();
		
			$repeater_html[] = $html;

			$index++;
		}

		return $repeater_html;
	}

	/**
	 * Returns all field data for the repeater field
	 * 
	 * @param   array  $data
	 * 
	 * @return  array
	 */
	private function getRepeaterFieldsData($data)
	{
		if (empty($data))
		{
			return [];
		}
		
		$data = (array) $data;

		if (empty($data))
		{
			return [];
		}

		$fields = $this->options['fields'];

		$index = 1;

		$fields_data = [];

		$data = array_values($data);
		
		foreach ($data as $_key => $_values)
		{
			$repeater_fields = [];
			foreach ($fields as $key => $field)
			{
				$name = isset($field['name']) ? $field['name'] : '';
				
				// set name and value
				if (!empty($name))
				{
					$default = isset($field['default']) ? $field['default'] : null;

					$field['value'] = FieldsHelper::findFieldValueInArray($name, $default, (array) $_values);
				}

				// add index to showon attribute
				if (isset($field['showon']))
				{
					$showon = $field['showon'];
					$showon = str_replace('ITEM_ID', $index, $showon);
					$field['showon'] = $showon;
				}

				// remove no-render classes
				if (isset($field['input_class']) && ($key = array_search('no-render', $field['input_class'])) !== false)
				{
					unset($field['input_class'][$key]);
				}

				$repeater_fields[] = $field;
			}

			$fields_data[] = $repeater_fields;
			$index++;
		}

		return $fields_data;
	}

	/**
	 * Returns the repeater fields template
	 * 
	 * @return  string
	 */
	private function getTemplate()
	{
		$options = new Registry($this->options);

		$html = '';

		$name = $options->get('name_key', '');
		$name_prefix = $options->get('name_prefix', '');
		
		$fields = $options->get('fields', []);

		// update name of fields
		foreach ($fields as &$field)
		{
			if (!isset($field['name']))
			{
				continue;
			}

			$field['name'] = $name . '.ITEM_ID.' . $field['name'];
		}

		$fieldsParser = new FieldsParser([
			'fields_name_prefix' => $name_prefix
		]);
		
		ob_start();
		$fieldsParser->renderFields($fields);
		$html = ob_get_contents();
		ob_end_clean();

		// add brackets between ITEM_ID in order to be able to update IDs for each repeater item via JS
		$html = str_replace('.ITEM_ID.', '[ITEM_ID]', $html);
		
		return $html;
	}

	/**
	 * Runs before field renders.
	 * Sets the value and template before rendering the field
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// get the saved repeater fields html
		$this->options['value'] = $this->getRepeaterHTML();

		$this->options['default_html'] = $this->getRepeaterDefaultHTML();

		// get the template
		$this->options['template'] = $this->getTemplate();

		$this->loadMedia();
	}

	/**
	 * Loads media tied to this Field
	 * 
	 * @return  void
	 */
	protected function loadMedia()
	{
		// CSS
		wp_register_style(
			'fpframework-repeater-field',
			FPF_MEDIA_URL . 'admin/css/fpf_repeater.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-repeater-field' );

		// Load Sortable
		wp_register_script(
			'fpframework-sortable-lib',
			FPF_MEDIA_URL . 'admin/js/sortable.min.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-sortable-lib' );
		
		// JS
		wp_register_script(
			'fpframework-repeater-field',
			FPF_MEDIA_URL . 'admin/js/fpf_repeater.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-repeater-field' );
	}
}