<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 8,
	'settings' => array(
		'id' => 'wpape_gallery_theme',
		'title' =>  __('Themes', 'gallery-images-ape'),
		'screen' => array(WPAPE_GALLERY_POST),
		'context' => 'normal',
		'priority' => 'high',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'fields' => array(
		array(
			'type' => 'themes',
			'view' => 'default',		
			'name' => 'themeId',
			'default' => '-1',
			'label' => __('Select Theme', 'gallery-images-ape'),
			'description' => __('Theme help you to configure all design of all interface elements of the gallery. Just open theme manager and create / edit / delete themes. You can create few design themes and use different theme in every gallery.', 'gallery-images-ape'),

		),
	),
);
