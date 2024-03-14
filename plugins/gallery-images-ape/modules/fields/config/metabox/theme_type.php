<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

$type = apeGalleryHelper::getThemeType();

return array(
	'active' => true,
	'order' => 20,
	'settings' => array(
		'id' => 'wpape-theme-type',
		'title' => __('Current Theme Type', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'priority' => 'high',
	),
	'view' => 'default',
	'state' => 'open',
	'content' => 'template::content/theme_type/type' . ( $type ? '_'.$type : '' ),
	'fields' => array(
		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'type',
			'default' => $type,
		),
	),
);
