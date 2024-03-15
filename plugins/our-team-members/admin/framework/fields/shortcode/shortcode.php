<?php
if (! defined ( 'ABSPATH' )) {
	die ();
} // Cannot access pages directly.
/**
 *
 * Field: shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 *         
 */
class WPSFramework_Option_shortcode extends WPSFramework_Options {
	public function __construct($field, $value = '', $unique = '') {
		parent::__construct ( $field, $value, $unique );
	}
	public function output() {

		global $post;
		$title 				= get_the_title( $post->ID );
		$shortcode_title 	= $title && $title != 'Auto Draft' ? ' title="'.$title.'"' : '';
		$shortcode_value 	= '[wpb-otm-shortcode '.$shortcode_title.' id="'.$post->ID.'"]';

		echo $this->element_before ();
		echo '<input type="text" name="' . $this->element_name () . '" value="' . esc_attr( $shortcode_value ) . '"' . $this->element_class () . $this->element_attributes () . '/>';
		echo $this->element_after ();
	}
}
