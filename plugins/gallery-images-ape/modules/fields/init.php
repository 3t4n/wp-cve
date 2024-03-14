<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

define('WPAPE_GALLERY_FIELDS_PATH', 		dirname(__FILE__) . '/');

define('WPAPE_GALLERY_FIELDS_PATH_CONFIG', 	WPAPE_GALLERY_FIELDS_PATH . 'config/');
define('WPAPE_GALLERY_FIELDS_SUB_FIELDS', 	WPAPE_GALLERY_FIELDS_PATH_CONFIG . 'metabox/sub-fields/');

define('WPAPE_GALLERY_FIELDS_PATH_FIELD', 	WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsField/');

define('WPAPE_GALLERY_FIELDS_TEMPLATE', 	WPAPE_GALLERY_FIELDS_PATH . 'template/');

define('WPAPE_GALLERY_FIELDS_URL', 			plugin_dir_url(__FILE__));

define('WPAPE_GALLERY_FIELDS_BODY_CLASS', 	'apeGalleryFields');

require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFields.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsHelper.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsConfig.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsConfig/apeGalleryFieldsConfigReaderInterface.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsConfig/apeGalleryFieldsConfigReader.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsMetaBoxClass.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsFieldFactory.php';
require_once WPAPE_GALLERY_FIELDS_PATH . 'include/apeGalleryFieldsView.php';

apeGalleryFields::getInstance()->init();