<?php 
/**
 * @package  WooCart
 */

namespace WscInc\Api\Callbacks;

use WscInc\Pages\Dashboard;

class FormCallbacks
{
	public function formSectionManager(){
	}

	// Sanitize the input submitted in the form or reset with default values.
	public function formSanitize( $input ){
		if($_POST["reset"]){
			$reset = new Dashboard;
			return $reset->resetFields();
		}
		return $input;
	}
	
	public function textField( $args ){
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$textbox = get_option( $option_name );
		$value = isset($textbox[$name]) ? ($textbox[$name]) : "";

		echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '">';
	}

	public function checkboxField( $args ){
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$checkbox = get_option( $option_name );
		$checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

		echo '<div class="ui-toggle"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
	}

	public function colorPicker( $args ){
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$color = get_option( $option_name );
		$value = isset($color[$name]) ? ($color[$name]) : "";

		echo '<input type="text" class="color-picker" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" >';
	}

	public function imageSelect( $args ){
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$image = get_option( $option_name );
		$value = isset($image[$name]) ? ($image[$name]) : "";

		echo '<input class="image-upload" id="' . $name . '" name="' . $option_name . '[' . $name . ']" type="hidden" value="' . $value . '">
			<img class="image-upload image-preview" src="'.$value.'" width="100px" height="100px" style="object-fit: contain;">
            <button type="button" class="button button-primary image-upload">Select Image</button>
            <button type="button" class="button remove-image">Remove Image</button>';
	}

	public function radioButton( $args ){
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$radios = get_option( $option_name );
		$value = isset($radios[$name]) ? ($radios[$name]) : "";

		foreach ($args['options'] as $option => $radio) {
			echo '<div class="radio"><input id="'.$radio.'" type="radio" class="ui-radio"  name="' . $option_name . '[' . $name . ']" value="'.$radio.'" '.($value == $radio ? "checked" : '').'><label  for="'.$radio.'" class="radio-label">'.$option.'</label></div>';
		}
	}
}