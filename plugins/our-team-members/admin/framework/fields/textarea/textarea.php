<?php
if (! defined ( 'ABSPATH' )) {
	die ();
} // Cannot access pages directly.
/**
 *
 * Field: Textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *         
 */
class WPSFramework_Option_textarea extends WPSFramework_Options {
	public function __construct($field, $value = '', $unique = '') {
		parent::__construct ( $field, $value, $unique );
	}
	public function output() {
		echo $this->element_before ();
		echo $this->shortcode_generator ();
		echo '<textarea name="' . $this->element_name () . '"' . $this->element_class () . $this->element_attributes () . '>' . $this->element_value () . '</textarea>';
		echo $this->element_after ();
	}
	public function shortcode_generator() {
		if (isset ( $this->field ['shortcode'] ) ) {
            $label = (isset($this->field['shortcode_button_name'])) ? $this->field['shortcode_button_name'] : __("Add Shortcode",'wpsf-framework');
			echo '<a href="#" class="button button-primary wpsf-shortcode wpsf-shortcode-textarea">'.esc_html($label).'</a>';
		}
	}
}
