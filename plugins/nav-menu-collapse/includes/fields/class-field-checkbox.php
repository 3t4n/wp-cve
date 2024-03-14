<?php
/*!
 * Checkbox field functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Checkbox Field
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the checkbox field object.
 *
 * @since 2.0.0
 *
 * @uses Nav_Menu_Collapse_Field
 */
final class Nav_Menu_Collapse_Field_Checkbox extends Nav_Menu_Collapse_Field
{
	/**
	 * Get a default value based on the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name Name of the value to return.
	 * @return mixed        Default value if it exists, otherwise an empty string.
	 */
	protected function _default($name)
	{
		switch ($name)
		{
			/**
			 * Custom value for a checkbox field.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'checkbox_value':
			
			/**
			 * Label displayed next to the checkbox.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'field_label':
			
				return '';
		}

		return parent::_default($name);
	}
	
	/**
	 * Generate the output for the checkbox field.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  boolean $echo True if the checkbox field should be echoed.
	 * @return string        Generated checkbox field if $echo is false.
	 */
	public function output($echo = false)
	{
		$output = '';
		
		if (!empty($this->id))
		{
			if ($this->checkbox_value === '')
			{
				$this->checkbox_value = '1';
			}

			if (!empty($this->value))
			{
				$this->value = $this->checkbox_value;
			}
			
			$output = '<label class="nmc-description">'
				. '<input ' . checked($this->checkbox_value, $this->value, false) . ' ' . $this->_field_classes() . $this->input_attributes . ' type="checkbox" value="' . esc_attr($this->checkbox_value) . '" />'
				. $this->field_label
			. '</label>';
		}
		
		return parent::_output($output, 'checkbox', $echo);
	}
	
	/**
	 * Add confirmation fields to a meta box.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  Nav_Menu_Collapse_Meta_Box $meta_box    Meta box object to add the confirmation fields to.
	 * @param  string                     $field_label Label displayed next to the confirmation checkboxes.
	 * @param  string                     $label       Primary label for the checkbox fields.
	 * @param  string                     $name        Name of the main confirmation field.
	 * @param  string                     $value       Value for the main confirmation field.
	 * @return Nav_Menu_Collapse_Meta_Box              Meta box with the added confirmation fields.
	 */
	public static function add_confirmation($meta_box, $field_label, $label, $name, $value)
	{
		$confirmed = (!empty($value));

		$unconfirmed = new Nav_Menu_Collapse_Field_Checkbox(array
		(
			'field_label' => $field_label,
			'label' => $label,
			'name' => $name . Nav_Menu_Collapse_Constants::SETTING_UNCONFIRMED,
			'sanitization' => Nav_Menu_Collapse_Sanitization::EXCLUDE,

			'wrapper_classes' => ($confirmed)
			? 'nmc-hidden'
			: ''
		));
		
		$meta_box->add_fields(array
		(
			$unconfirmed,
			
			new Nav_Menu_Collapse_Field_Checkbox(array
			(
				'field_label' => $field_label,
				'name' => $name,
				'sanitization' => Nav_Menu_Collapse_Sanitization::CONFIRMATION,

				'conditions' => ($confirmed)
				? array()
				: array
				(
					array
					(
						'field' => $unconfirmed,
						'value' => '1'
					)
				),

				'label' => ($confirmed)
				? $label
				: sprintf
				(
					_x('Confirm %1$s', 'Label', 'nav-menu-collapse'),
					$label
				),

				'wrapper_classes' => ($confirmed)
				? ''
				: 'nmc-confirmation nmc-hidden'
			))
		));
		
		return $meta_box;
	}
}
