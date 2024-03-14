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

namespace FireBox\Core\Form\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Field
{
	/**
	 * Field options.
	 * 
	 * @var  array
	 */
	protected $options = [];

	/**
	 * Validation message that appears when a field fails validation.
	 * 
	 * @var  string
	 */
	protected $validation_message = '';

	public function __construct($options = [])
	{
		// Merge default options with given options
		$this->options = array_merge($this->getDefaultOptions(), $options);

		// Then merge new options with each field options
		$this->options = array_merge($this->options, $this->getFieldOptions());
	}

	protected function getFieldOptions()
	{
		return [
			'required' => isset($this->options['required']) ? $this->options['required'] : true
		];
	}

	/**
	 * Returns the default field options.
	 * 
	 * @return  array
	 */
	private function getDefaultOptions()
	{
		return [
			'type' => $this->type,
			'name' => '',
			'placeholder' => ''
		];
	}

	/**
	 * Validate the field.
	 * 
	 * @param   mixed  $value
	 * 
	 * @return  void
	 */
	public function validate(&$value = '')
	{
		if ($this->isRequired() && empty($value))
		{
			$this->validation_message = firebox()->_('FB_THIS_IS_A_REQUIRED_FIELD');
			return false;
		}
		
		return true;
	}

	/**
	 * Returns whether the field is required.
	 * 
	 * @return  bool
	 */
	public function isRequired()
	{
		return $this->options['required'];
	}

	/**
	 * Prepare value to be displayed to the user as plain text
	 *
	 * @param  mixed $value
	 *
	 * @return string
	 */
	public function prepareValue($value)
	{
		if (is_bool($value))
		{
			return $value ? '1' : '0';
		}

		if (is_array($value))
		{
			return implode(', ', $value);
		}

		// Strings and numbers
		return \FireBox\Core\Helpers\Form\Field::escape($value);
	}

	public function getValue()
	{
		return isset($this->options['value']) ? $this->options['value'] : null;
	}

	public function prepareValueHTML($value)
	{
		return $this->prepareValue($value);
	}

	public function getValueRaw()
	{
		return $this->getValue();
	}

	public function getValueHTML()
	{
		return $this->prepareValueHTML($this->getValueRaw());
	}

	public function getValidationMessage()
	{
		return $this->validation_message;
	}

	public function setValue($value = '')
	{
		if (!$value)
		{
			return;
		}

		$this->options['value'] = $value;
	}

	public function setOptionValue($key = '', $value = '')
	{
		if (!$key || !$value)
		{
			return;
		}

		$this->options[$key] = $value;
	}

	public function getOptionValue($key = '')
	{
		if (!$key)
		{
			return;
		}

		return isset($this->options[$key]) ? $this->options[$key] : '';
	}

	public function addInputCSSClass($css = '')
	{
		$this->options['input_css_class'][] = $css;
	}
	
	/**	
	 * Return the field label.
	 * 
	 * @return  string
	 */
	public function getLabel()
	{
		return $this->getOptionValue('label');
	}

	/**
	 * Returns the field input.
	 * 
	 * @return  void
	 */
	protected function getInput() {}

	public function render()
	{
		ob_start();
		$this->getInput();
		$input = ob_get_contents();
		ob_end_clean();

		$payload = array_merge(
			$this->options,
			[
				'input' => $input
			]
		);

		return fpframework()->renderer->render('form/fields/tmpl', $payload, true);
	}
}