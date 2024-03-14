<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 0,
	'settings' => array(
		'id' => 'wpape-gallery-menu',
		'title' => __('Ape Gallery Menu Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_POST ),
		'context' => 'normal',
		'priority' => 'default',
	),
	'view' => 'default',
	'state' => 'close',
	'style' => null,
	'fields' => array(
		
		array(
			'type' => 'checkbox',
			'view' => 'switch/c2',
			'name' => 'menuSelf',
			'label' => __('Current button', 'gallery-images-ape'),
			'default' => true,
			"dependents" => array(
				'1' => array(
					'show' => array('#div-gallery-menu-options-block'),
				),
				'0' => array(
					'hide' => array('#div-gallery-menu-options-block'),
				)
			),
			'contentAfterBlock' => '<div id="div-gallery-menu-options-block">',
		),

		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'menuLabel',
			'label'=> __( 'Category Icon', 'gallery-images-ape' ),
			'description' =>	
				__('here you can define icon for top categories menu. This icon replace title on button.If this icon do not defined then you\'ll have just gallery title on button. Please select, icon ID from the ', 'gallery-images-ape').
				'<a href="https://fontawesome.com/icons?d=gallery&s=regular,solid&m=free" target="_blank">'
					.__('list of icons', 'gallery-images-ape')
				.'</a>',
			'default' => '',
			'attributes' => array(),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
			),
		),
		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'menuLabelText',
			'label'=> __( 'Category Label', 'gallery-images-ape' ),
			'description' => __('here you can define custom label for top categories menu. This label replace gallery title on button. If this label do not defined then you\'ll have just gallery title on button by default', 'gallery-images-ape'),
			'default' => '',
			'attributes' => array(),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
			),
			'contentAfterBlock' => '</div>',
		),

	),
);
