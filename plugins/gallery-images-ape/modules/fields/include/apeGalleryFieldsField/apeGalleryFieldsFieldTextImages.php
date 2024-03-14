<?php

class apeGalleryFieldsFieldTextImages extends apeGalleryFieldsField{
	protected function normalize($values){
		$values = parent::normalize($values);
	
		if( !is_array($values) ){
			$values = trim($values);
			
			if( is_string($values) && $values!='' ){
				$values = explode(',', $values);	
			} else {
				$values = array();	
			}

		}		
		
		return $values;
	}
}
