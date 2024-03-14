<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsFieldTextSlider extends apeGalleryFieldsField{

	protected function getDefaultOptions(){
		return array(
			'textBefore' => '',
			'textAfter' => '',
			'data-start' => 0,
			'data-end' => 100,
			'step' => 1
		);
	}

	protected function normalize($value){
		$min = isset($this->options['data-start']) ? $this->options['data-start'] : 0;
		$max = isset($this->options['data-end']) ? $this->options['data-end'] : 100;
		$step = isset($this->options['step']) ? absint($this->options['step']) : 1;

		$value = parent::normalize($value);

		if ($value < $min) {
			$value = $min;
		}
		if ($value > $max) {
			$value = $max;
		}
		if ($remainder = $value % $step) {
			$value = max($min, $value - $remainder);
		}

		return $value;
	}
}
