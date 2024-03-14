<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsConfigReaderPhp implements apeGalleryFieldsConfigReaderInterface{

	public function read($filePath){
		return require $filePath;
	}
}