<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 1,
	'settings' => array(
		'id' => 'wpape_gallery_field_images_ver2',
		'title' => __('Images', 'gallery-images-ape'),
		'screen' => array( WPAPE_GALLERY_POST ),
		'context' => 'normal',
		'priority' => 'high',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'fields' => array(
		array(
			'type' => 'text',
			'view' => 'images',
			'is_lock' => false,
			'prefix' => null,
			'name' => 'galleryImages',
			'default' => '',
		),
		
	)
);
