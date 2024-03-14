<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class apeGalleryFieldsFieldFactory{

	const DEFAULT_CLASS_FIELD = 'apeGalleryFieldsField';

	protected function __construct() {}
	protected function __clone() {}

	public static function createField($postId, array $settings){
		
		if (empty($settings['type'])) {
			throw new Exception('Empty field type');
		}
		
	/*	if($settings['type']=='skip') return ;*/

		if (empty($settings['view'])) {
			throw new Exception('Empty field view');
		}

		$type = ucfirst(preg_replace_callback(
			'/(?:-|_)([a-z0-9])/i',
			function($matches) {
				return strtoupper($matches[1]);
			},
			$settings['type']
		));
		$view = ucfirst(preg_replace_callback(
			'/(?:-|_|\/)([a-z0-9])/i',
			function($matches) {
				return strtoupper($matches[1]);
			},
			$settings['view']
		));
		$classesChain = array(
			self::DEFAULT_CLASS_FIELD . $type . $view,
			self::DEFAULT_CLASS_FIELD . $type,
			self::DEFAULT_CLASS_FIELD
		);

		require_once WPAPE_GALLERY_FIELDS_PATH_FIELD . 'apeGalleryFieldsField.php';
		require_once WPAPE_GALLERY_FIELDS_PATH_FIELD . 'apeGalleryFieldsFieldCheckboxGroup.php';
		foreach ($classesChain as $className) {
			if (file_exists(WPAPE_GALLERY_FIELDS_PATH_FIELD . "{$className}.php")) {
				require_once WPAPE_GALLERY_FIELDS_PATH_FIELD . "{$className}.php";
				return new $className($postId, $settings);
			}
		}
		throw new Exception("Can't find field class");
	}
}
