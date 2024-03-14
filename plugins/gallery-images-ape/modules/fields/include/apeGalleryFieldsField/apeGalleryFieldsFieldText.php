<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */


class apeGalleryFieldsFieldText extends apeGalleryFieldsField{
	
	protected function getDefaultOptions(){
		return array(
			'textBefore' => '',
			'textAfter' => '',
		);
	}
}
