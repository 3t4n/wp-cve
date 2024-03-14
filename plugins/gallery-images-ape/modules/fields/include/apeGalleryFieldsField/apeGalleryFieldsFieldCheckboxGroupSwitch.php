<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsFieldCheckboxGroupSwitch extends apeGalleryFieldsFieldCheckboxGroup{
	
	protected function getDefaultOptions(){
		return array(
			'size' 		=> 'large',
			'onLabel' 	=> __('On', 'gallery-images-ape'),
			'offLabel' 	=> __('Off', 'gallery-images-ape'),
		);
	}

}
