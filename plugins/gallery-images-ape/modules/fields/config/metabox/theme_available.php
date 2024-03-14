<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 9,
	'settings' => array(
		'id' => 'wpape_gallery_available_theme',
		'title' =>  __('Available Gallery Types', 'gallery-images-ape'),
		'screen' => array(WPAPE_GALLERY_POST),
		'context' => 'normal',
		'priority' => 'high',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'content' => 'template::content/theme_available/content',
	'fields' => array(),
);
