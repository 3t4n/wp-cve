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
use \FPFramework\Helpers\HTML;

class ResponsiveControl extends Field
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

		$this->field_options = [
			'fields' => $options->get('fields', [])
		];
	}

	/**
	 * Returns control HTML
	 * 
	 * @return  string
	 */
	private function getControlHTML()
	{
		$html = '';
		
		$fields = isset($this->options['fields']) ? $this->options['fields'] : [];
		$name = isset($this->options['name']) ? $this->options['name'] : '';

		$value = isset($this->options['value']) ? (array) $this->options['value'] : [];

		$selected_device = isset($value['type']) ? $value['type'] : 'desktop';

		if (empty($fields) || empty($name))
		{
			return '';
		}

		$devices = [
			'desktop',
			'tablet',
			'mobile'
		];

		foreach ($devices as $device)
		{
			$device_html = '';
			
			foreach ($fields as $field)
			{
				if (!is_array($field) || empty($field))
				{
					continue;
				}

				if (!$type = $field['type'])
				{
					continue;
				}

				if (isset($field['name']))
				{
					$field_name_clean = str_replace(['[', ']'], '', $field['name']);

					// append device on field's name
					$field['name'] = $name . '[' . $field['name'] . '][' . $device . ']';

					$fieldValue = null;
					$fieldUnit = null;

					if (isset($value[$field_name_clean]))
					{
						$tempFieldValue = (array) $value[$field_name_clean];

						// Its a Repeater field that has units attached, set the unit
						if (isset($tempFieldValue[$device]))
						{
							// Get the array of the value, in case we have a unit and value
							$tempFieldValueDevice = (array) $tempFieldValue[$device];

							$fieldUnit = isset($tempFieldValueDevice['unit']) ? $tempFieldValueDevice['unit'] : null;
							$fieldValue = isset($tempFieldValueDevice['value']) ? $tempFieldValueDevice['value'] : $tempFieldValue[$device];
						}
					}

					// Set field value
					$field['value'] = $fieldValue;

					// Set field unit
					$field['unit'] = $fieldUnit;
				}

				$fieldClass = '\FPFramework\Base\Fields\\' . $type;

				$fieldClass = new $fieldClass($field);

				ob_start();
				echo HTML::renderStartDiv([
					'class' => ['fpf-responsive-control-device-item-inner-field']
				]);

				// render field
				$fieldClass->render();

				echo HTML::renderEndDiv();
				
				$field_html = ob_get_contents();
				ob_end_clean();
				
				$device_html .= $field_html;
			}

			$payload = [
				'device' => $device,
				'html' => $device_html,
				'selected_device' => $selected_device,
			];

			$html .= fpframework()->renderer->fields->render('responsivecontrol_item', $payload, true);
		}

		return $html;
	}

	/**
	 * Runs before field renders.
	 * Set the HTML of the field before it renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		$this->options['html'] = $this->getControlHTML();

		// render responsive controls
		$this->renderResponsiveControls();

		// CSS
		wp_register_style(
			'fpframework-responsivecontrol-field',
			FPF_MEDIA_URL . 'admin/css/fpf_responsivecontrol.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-responsivecontrol-field' );
	}

	/**
	 * Render responsive controls
	 * 
	 * @return  void
	 */
	private function renderResponsiveControls()
	{
		$rcs_field_data = [
			'name' => rtrim($this->options['name_key'], ']') . '_responsive_controls]',
			'render_top' => false,
			'render_group' => false,
		];
		$rcs = new \FPFramework\Base\Fields\ResponsiveControls($rcs_field_data);
		
		ob_start();
		$rcs->render();
		$html = ob_get_contents();
		ob_end_clean();
		$this->options['field_top_responsive_controls'] = $html;
	}
}